<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle Preflight OPTIONS requests for CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ==========================================
// 🚨 SECURITY LOCK 🚨
// Verify session is active before allowing API actions
// ==========================================
session_start();
if (empty($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(["detail" => "Unauthorized access. Please log in."]);
    exit();
}
// ==========================================

// ==========================================
// 🚨 IONOS DATABASE CREDENTIALS 🚨
// Replace these with your IONOS MySQL details
// ==========================================
$db_host = 'dbxxxxxxxx.hosting-data.io'; // e.g. db123456789.hosting-data.io
$db_name = 'dbxxxxxxxx';                 // e.g. db123456789
$db_user = 'dboxxxxxxxx';                 // e.g. dbo123456789
$db_pass = 'YourSecretPassword!';
// ==========================================

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["detail" => "Database connection failed: " . $e->getMessage()]);
    exit();
}

function init_db($pdo) {
    // Create Contracts Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contracts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) UNIQUE NOT NULL,
            vendor VARCHAR(150) NOT NULL,
            type VARCHAR(50) NOT NULL,
            amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            expiry_date DATE NOT NULL,
            auto_renew TINYINT(1) NOT NULL DEFAULT 0,
            auto_pay TINYINT(1) NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Create Statements Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS statements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            contract_id INT NOT NULL,
            date DATE NOT NULL,
            invoice_id VARCHAR(100) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(100) NOT NULL,
            FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Create Logs Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp DATETIME NOT NULL,
            message TEXT NOT NULL,
            vendor VARCHAR(150),
            associated_amount DECIMAL(10,2),
            associated_expiry DATE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Seed Data if contracts table is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM contracts");
    if ($stmt->fetchColumn() == 0) {
        $today = new DateTime();

        $seed_contracts = [
            ["SLK-105", "Slack Technologies", "SaaS", 1450.00, (clone $today)->modify('+14 days')->format('Y-m-d'), 1, 1],
            ["AWS-482", "Amazon Web Services", "Vendor", 4890.00, (clone $today)->modify('+45 days')->format('Y-m-d'), 1, 0],
            ["SFC-773", "Salesforce CRM", "SaaS", 850.00, (clone $today)->modify('+25 days')->format('Y-m-d'), 1, 0],
            ["NDA-012", "Standard IP Protection", "NDA", 0.00, (clone $today)->modify('+180 days')->format('Y-m-d'), 0, 0]
        ];

        $insertContract = $pdo->prepare("INSERT INTO contracts (code, vendor, type, amount, expiry_date, auto_renew, auto_pay) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertStatement = $pdo->prepare("INSERT INTO statements (contract_id, date, invoice_id, amount, status) VALUES (?, ?, ?, ?, ?)");

        foreach ($seed_contracts as $c) {
            $insertContract->execute($c);
            $contract_id = $pdo->lastInsertId();

            if ($c[2] !== "NDA") {
                $statements = [];
                if ($c[0] === "SLK-105") {
                    $statements = [
                        ["2026-07-01", "INV-SLK-492", 1450.00, "Cleared via Auto-Pay"],
                        ["2026-06-01", "INV-SLK-381", 1450.00, "Cleared via Auto-Pay"],
                        ["2026-05-01", "INV-SLK-201", 1450.00, "Cleared via Auto-Pay"]
                    ];
                } elseif ($c[0] === "AWS-482") {
                    $statements = [
                        ["2026-07-05", "INV-AWS-721", 4890.00, "Settled Manually"],
                        ["2026-06-05", "INV-AWS-510", 4890.00, "Settled Manually"]
                    ];
                } elseif ($c[0] === "SFC-773") {
                    $statements = [
                        ["2026-06-18", "INV-SFC-194", 850.00, "Settled Manually"]
                    ];
                }

                foreach ($statements as $s) {
                    $insertStatement->execute([$contract_id, $s[0], $s[1], $s[2], $s[3]]);
                }
            }
        }
    }
}

// Run DB Init on every execution to ensure tables exist
init_db($pdo);

function logEvent($pdo, $message, $vendor = null, $amount = null, $expiry = null) {
    $stmt = $pdo->prepare("INSERT INTO logs (timestamp, message, vendor, associated_amount, associated_expiry) VALUES (NOW(), ?, ?, ?, ?)");
    $stmt->execute([$message, $vendor, $amount, $expiry]);
}

// Get the requested route from the URL parameter or path info
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Helper to parse JSON input
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

// Routing mechanism based on URL string matching
if (strpos($request_uri, '/api.php/contracts') !== false || strpos($request_uri, '/contracts') !== false) {

    // Check if it's a specific contract endpoint (e.g., /contracts/123/autopay or /contracts/CODE/statements)
    preg_match('/contracts\/([a-zA-Z0-9-]+)(\/(.*))?/', $request_uri, $matches);
    $contract_id_or_code = isset($matches[1]) ? $matches[1] : null;
    $sub_action = isset($matches[3]) ? $matches[3] : null;

    if ($method === 'GET' && !$contract_id_or_code) {
        // GET /contracts
        $stmt = $pdo->query("SELECT * FROM contracts ORDER BY expiry_date ASC");
        $rows = $stmt->fetchAll();

        $response = array_map(function($r) {
            return [
                "id" => $r["id"],
                "code" => $r["code"],
                "vendor" => $r["vendor"],
                "type" => $r["type"],
                "amount" => (float)$r["amount"],
                "expiryDate" => $r["expiry_date"],
                "autoRenew" => (bool)$r["auto_renew"],
                "autoPay" => (bool)$r["auto_pay"]
            ];
        }, $rows);

        echo json_encode($response);
        exit();
    }

    elseif ($method === 'POST' && !$contract_id_or_code) {
        // POST /contracts
        if (!isset($input['vendor'], $input['type'], $input['expiryDate'])) {
            http_response_code(400);
            echo json_encode(["detail" => "Missing required fields."]);
            exit();
        }

        $clean_vendor = preg_replace('/[^a-zA-Z0-9]/', '', $input['vendor']);
        $prefix = strlen($clean_vendor) >= 3 ? strtoupper(substr($clean_vendor, 0, 3)) : "CON";
        $code = $prefix . "-" . rand(100, 999);
        $amount = isset($input['amount']) ? (float)$input['amount'] : 0.00;
        $autoRenew = isset($input['autoRenew']) && $input['autoRenew'] ? 1 : 0;
        $autoPay = isset($input['autoPay']) && $input['autoPay'] ? 1 : 0;

        try {
            $stmt = $pdo->prepare("INSERT INTO contracts (code, vendor, type, amount, expiry_date, auto_renew, auto_pay) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$code, $input['vendor'], $input['type'], $amount, $input['expiryDate'], $autoRenew, $autoPay]);
            $contract_id = $pdo->lastInsertId();

            logEvent($pdo, "New contract registered.", $input['vendor'], $amount, $input['expiryDate']);

            echo json_encode([
                "message" => "Contract registered successfully",
                "code" => $code,
                "id" => $contract_id,
                "expiryDate" => $input['expiryDate']
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["detail" => "Database write error: " . $e->getMessage()]);
        }
        exit();
    }

    elseif ($method === 'DELETE' && $contract_id_or_code && !$sub_action) {
        // DELETE /contracts/{id}
        $stmt = $pdo->prepare("SELECT vendor, code FROM contracts WHERE id = ?");
        $stmt->execute([$contract_id_or_code]);
        $row = $stmt->fetch();

        if (!$row) {
            http_response_code(404);
            echo json_encode(["detail" => "Contract entity not found."]);
            exit();
        }

        $pdo->prepare("DELETE FROM contracts WHERE id = ?")->execute([$contract_id_or_code]);
        logEvent($pdo, "Contract deleted from vault.", $row['vendor'], null, null);

        echo json_encode([
            "message" => "Record successfully wiped",
            "vendor" => $row['vendor'],
            "code" => $row['code']
        ]);
        exit();
    }

    elseif ($method === 'PATCH' && $contract_id_or_code && $sub_action === 'autopay') {
        // PATCH /contracts/{id}/autopay
        $stmt = $pdo->prepare("SELECT vendor, expiry_date, amount FROM contracts WHERE id = ?");
        $stmt->execute([$contract_id_or_code]);
        $row = $stmt->fetch();

        if (!$row) {
            http_response_code(404);
            echo json_encode(["detail" => "Contract not found."]);
            exit();
        }

        $autoPayState = isset($input['autoPay']) && $input['autoPay'] ? 1 : 0;
        $pdo->prepare("UPDATE contracts SET auto_pay = ? WHERE id = ?")->execute([$autoPayState, $contract_id_or_code]);

        $stateText = $autoPayState ? "enabled" : "disabled";
        logEvent($pdo, "Auto-Pay status manually updated to $stateText.", $row['vendor'], $row['amount'], $row['expiry_date']);

        echo json_encode([
            "message" => "Auto-Pay toggle set",
            "vendor" => $row["vendor"],
            "expiryDate" => $row["expiry_date"],
            "amount" => $row["amount"]
        ]);
        exit();
    }

    elseif ($method === 'GET' && $contract_id_or_code && $sub_action === 'statements') {
        // GET /contracts/{code}/statements
        $stmt = $pdo->prepare("SELECT * FROM contracts WHERE code = ?");
        $stmt->execute([strtoupper($contract_id_or_code)]);
        $contract_row = $stmt->fetch();

        if (!$contract_row) {
            http_response_code(404);
            echo json_encode(["detail" => "Vendor code unrecognized."]);
            exit();
        }

        $stmt = $pdo->prepare("SELECT * FROM statements WHERE contract_id = ? ORDER BY date DESC");
        $stmt->execute([$contract_row['id']]);
        $statement_rows = $stmt->fetchAll();

        $statements_list = array_map(function($s) {
            return [
                "date" => $s["date"],
                "id" => $s["invoice_id"],
                "amount" => (float)$s["amount"],
                "status" => $s["status"]
            ];
        }, $statement_rows);

        echo json_encode([
            "id" => $contract_row["id"],
            "code" => $contract_row["code"],
            "vendor" => $contract_row["vendor"],
            "type" => $contract_row["type"],
            "amount" => (float)$contract_row["amount"],
            "expiryDate" => $contract_row["expiry_date"],
            "autoRenew" => (bool)$contract_row["auto_renew"],
            "autoPay" => (bool)$contract_row["auto_pay"],
            "statements" => $statements_list
        ]);
        exit();
    }
}

elseif (strpos($request_uri, '/simulator/alerts') !== false && $method === 'POST') {
    $stmt = $pdo->query("SELECT * FROM contracts");
    $rows = $stmt->fetchAll();

    $alerts = [];
    $today = new DateTime();

    foreach ($rows as $r) {
        $expiry = new DateTime($r["expiry_date"]);
        $diff = $today->diff($expiry);
        $days_left = (int)$diff->format("%r%a"); // includes negative signs

        if ($days_left > 0 && $days_left <= 30 && $r["auto_renew"]) {
            $alerts[] = [
                "vendor" => $r["vendor"],
                "code" => $r["code"],
                "daysLeft" => $days_left,
                "amount" => (float)$r["amount"],
                "expiryDate" => $r["expiry_date"]
            ];
            logEvent($pdo, "30-Day Warning Alert Triggered. Action required.", $r["vendor"], $r["amount"], $r["expiry_date"]);
        }
    }

    echo json_encode([
        "checkedCount" => count($rows),
        "alertsTriggered" => $alerts,
        "timestamp" => date("H:i:s")
    ]);
    exit();
}

elseif (strpos($request_uri, '/simulator/autopay') !== false && $method === 'POST') {
    $stmt = $pdo->query("SELECT * FROM contracts");
    $rows = $stmt->fetchAll();

    $today = new DateTime();
    $today_str = $today->format("Y-m-d");
    $processed = [];

    $insertStatement = $pdo->prepare("INSERT INTO statements (contract_id, date, invoice_id, amount, status) VALUES (?, ?, ?, ?, ?)");

    foreach ($rows as $r) {
        $expiry = new DateTime($r["expiry_date"]);
        $diff = $today->diff($expiry);
        $days_left = (int)$diff->format("%r%a");

        if ($days_left > 0 && $days_left <= 30 && $r["auto_renew"] && $r["auto_pay"] && $r["amount"] > 0) {
            $prefix = explode("-", $r["code"])[0];
            $new_invoice_id = "INV-" . $prefix . "-" . rand(100, 999);

            $insertStatement->execute([$r["id"], $today_str, $new_invoice_id, $r["amount"], "Cleared via Auto-Pay"]);

            $processed[] = [
                "vendor" => $r["vendor"],
                "code" => $r["code"],
                "amount" => (float)$r["amount"],
                "invoiceId" => $new_invoice_id,
                "expiryDate" => $r["expiry_date"]
            ];

            logEvent($pdo, "Stripe Gateway charged amount automatically.", $r["vendor"], $r["amount"], $r["expiry_date"]);
        }
    }

    echo json_encode([
        "processedCount" => count($processed),
        "transactions" => $processed,
        "timestamp" => date("H:i:s")
    ]);
    exit();
}

elseif (strpos($request_uri, '/logs/clear') !== false && $method === 'POST') {
    $pdo->exec("DELETE FROM logs");
    echo json_encode(["message" => "Logs cleared"]);
    exit();
}

elseif (strpos($request_uri, '/logs') !== false && $method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM logs ORDER BY id ASC");
    $rows = $stmt->fetchAll();

    $response = array_map(function($r) {
        return [
            "id" => $r["id"],
            "timestamp" => $r["timestamp"],
            "message" => $r["message"],
            "vendor" => $r["vendor"],
            "amount" => $r["associated_amount"] !== null ? (float)$r["associated_amount"] : null,
            "expiryDate" => $r["associated_expiry"]
        ];
    }, $rows);

    echo json_encode($response);
    exit();
}

// Fallback for 404 Routes
http_response_code(404);
echo json_encode(["detail" => "Endpoint not found."]);
?>
