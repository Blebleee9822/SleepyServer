<?php
session_start();

// ==========================================
// 🚨 ADMIN LOGIN CREDENTIALS 🚨
// Change these to your desired username and password
// ==========================================
$admin_username = 'admin';
$admin_password = 'team3';
// ==========================================

// Handle Logout if the URL contains ?logout=1
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Redirect to dashboard if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

$error = '';

// Check login submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $admin_username && $pass === $admin_password) {
        // Success! Set session and redirect to the dashboard
        $_SESSION['logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login - Contract Vault</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-950 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white border border-gray-300 rounded shadow-xl p-8 w-full max-w-sm">
        
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold tracking-tight text-gray-900 mb-1">Contract Vault</h1>
            <p class="text-xs text-gray-500 font-mono uppercase tracking-widest">Restricted Access</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 border border-red-200 text-xs text-center p-2 rounded mb-4 font-semibold">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Username</label>
                <input type="text" name="username" required autofocus class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-500 text-sm">
            </div>
            
            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 rounded transition-colors text-sm mt-2">
                Authorize Session
            </button>
        </form>

    </div>

</body>
</html>
