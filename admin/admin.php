<?php
session_start();
// Security Check: If they are not logged in, kick them back to the login page!
if (empty($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Vault & Renewal Watchdog</title>
    <!-- Tailwind CSS for clean layout utility -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-950 min-h-screen pb-20 md:pb-0 md:pr-24">

    <!-- Main Content Container -->
    <div class="max-w-5xl mx-auto p-4 md:p-8 space-y-6">
        
        <!-- Header -->
        <header class="border-b border-gray-300 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900">Contract Vault</h1>
                <p class="text-sm text-gray-600">Track vendor auto-renewals, generate access codes, and view statement cycles.</p>
            </div>
            <div class="text-xs text-gray-500 font-mono bg-white border border-gray-300 px-3 py-1.5 rounded">
                System Status: Active
            </div>
        </header>

        <!-- Contracts List Table -->
        <div class="bg-white border border-gray-300 rounded shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-300 bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-700">Monitored Contracts</h2>
                
                <div class="flex items-center gap-2">
                    <select id="filter-type" onchange="renderContracts()" class="text-xs bg-white border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        <option value="all">All Types</option>
                        <option value="SaaS">SaaS Only</option>
                        <option value="NDA">NDAs Only</option>
                        <option value="Vendor">Other Vendors</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-300 text-gray-600 uppercase tracking-wider font-semibold">
                            <th class="p-3">Vendor & Code</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Renewal Date</th>
                            <th class="p-3">Cost (Monthly)</th>
                            <th class="p-3">Auto-Pay Guard</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="contracts-table-body" class="divide-y divide-gray-200">
                        <!-- Populated via Javascript -->
                    </tbody>
                </table>
                <div id="empty-state" class="hidden p-8 text-center text-gray-500">
                    No active contracts match this filter. Use the "Add Contract" button in the sidebar to add one.
                </div>
            </div>
        </div>

        <!-- Billing Cycles & Statements Box -->
        <div class="bg-white border border-gray-300 rounded shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-300 bg-gray-50">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-700">Billing Cycles & Statement Ledger</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Enter a vendor's unique code to view billing history and upcoming cycles.</p>
            </div>
            
            <div class="p-4 bg-gray-50/50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 max-w-md">
                    <input type="text" id="billing-lookup-code" placeholder="Enter Vendor Code (e.g., SLK-105)" class="text-xs uppercase bg-white border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-slate-500 flex-grow font-mono">
                    <button onclick="lookupBillingCode()" class="text-xs bg-slate-800 hover:bg-slate-900 text-white font-semibold px-4 py-2 rounded transition-colors whitespace-nowrap">
                        Retrieve Statement
                    </button>
                </div>
                <div id="lookup-feedback" class="text-[11px] text-red-600 mt-1.5 hidden">
                    <!-- Error or success feedback displayed here -->
                </div>
            </div>

            <!-- Statement Content Container -->
            <div id="statement-output-container" class="p-4 min-h-[120px] flex flex-col justify-center text-center">
                <!-- Initial Empty state -->
                <div id="statement-empty-state" class="text-gray-500 text-xs py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-10 h-10 mx-auto mb-2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Enter a vendor code from the table above to visualize scheduled billing timelines, history logs, and invoices.
                </div>

                <!-- Live Statement Render Profile -->
                <div id="statement-profile" class="hidden text-left text-xs space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-gray-400">Vendor Entity</span>
                            <span id="stmt-vendor-name" class="font-bold text-sm text-slate-900">-</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-gray-400">Billing Interval</span>
                            <span id="stmt-interval" class="font-medium text-slate-800">Monthly Cycle</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-gray-400">Payment Status</span>
                            <span id="stmt-method" class="font-semibold text-emerald-700">-</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] uppercase font-bold text-gray-400 mb-2">Itemized Statements & Invoice Receipts</h4>
                        <div class="border border-gray-200 rounded overflow-hidden">
                            <table class="w-full text-left text-[11px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase tracking-wider text-[9px]">
                                        <th class="p-2">Statement Date</th>
                                        <th class="p-2">Invoice ID</th>
                                        <th class="p-2">Amount</th>
                                        <th class="p-2 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="stmt-table-body" class="divide-y divide-gray-100">
                                    <!-- Dynamically filled with statements -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation dock: Pinned to right side on desktop, bottom on mobile devices -->
    <nav class="fixed bottom-0 left-0 w-full h-16 md:h-screen md:w-20 md:top-0 md:left-auto md:right-0 bg-slate-900 border-t md:border-t-0 md:border-l border-slate-800 text-white flex flex-row md:flex-col items-center justify-around md:justify-start md:py-8 md:gap-8 z-40 shadow-lg">
        
        <!-- Add New Contract Button -->
        <button onclick="openModal('modal-add-contract')" class="flex flex-col items-center justify-center gap-1 hover:text-emerald-400 group focus:outline-none transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-105 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span class="text-[10px] uppercase font-semibold tracking-wider">Add</span>
        </button>

        <!-- Watchdog Simulator Button -->
        <button onclick="openModal('modal-simulator')" class="flex flex-col items-center justify-center gap-1 hover:text-amber-400 group focus:outline-none transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-105 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
            <span class="text-[10px] uppercase font-semibold tracking-wider">Simulate</span>
        </button>

        <!-- System Logs Console Button -->
        <button onclick="openModal('modal-logs')" class="flex flex-col items-center justify-center gap-1 hover:text-cyan-400 group focus:outline-none transition-colors relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-105 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
            </svg>
            <span class="text-[10px] uppercase font-semibold tracking-wider">History Log</span>
            <div id="unread-dot" class="absolute top-0 right-1 w-2.5 h-2.5 bg-cyan-500 rounded-full border border-slate-900 hidden"></div>
        </button>

        <!-- Logout Button -->
        <a href="index.php?logout=1" class="flex flex-col items-center justify-center gap-1 hover:text-red-400 group focus:outline-none transition-colors md:mt-auto md:mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-105 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
            <span class="text-[10px] uppercase font-semibold tracking-wider">Logout</span>
        </a>
    </nav>

    <!-- Modal 1: Add New Contract Form Popup -->
    <div id="modal-add-contract" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden" onclick="closeModalOnBackdrop(event, 'modal-add-contract')">
        <div class="bg-white border border-gray-300 rounded-lg shadow-xl p-6 w-full max-w-md relative" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeModal('modal-add-contract')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-700 border-b border-gray-200 pb-2 mb-4">Register New Contract</h2>
            
            <form id="contract-form" onsubmit="addContract(event)" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Vendor / Entity Name</label>
                    <input type="text" id="input-vendor" required placeholder="e.g., Slack, AWS" class="w-full px-2.5 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Contract Type</label>
                        <select id="input-type" onchange="handleTypeChange()" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-500">
                            <option value="SaaS">SaaS</option>
                            <option value="NDA">NDA</option>
                            <option value="Vendor">Other Vendor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Cost / Value</label>
                        <input type="number" id="input-cost" placeholder="e.g., 200" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Renewal / Expiration Date</label>
                    <input type="date" id="input-date" required class="w-full px-2 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-500">
                </div>

                <div class="space-y-3 pt-3 border-t border-gray-100">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox" id="input-autorenew" checked class="mt-0.5 rounded border-gray-300 text-slate-800 focus:ring-0">
                        <div>
                            <span class="font-semibold text-gray-700">Auto-Renewal Clause Active</span>
                            <p class="text-[10px] text-gray-500">Contract has clauses that renew automatically if not canceled.</p>
                        </div>
                    </label>

                    <label id="autopay-container" class="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox" id="input-autopay" class="mt-0.5 rounded border-gray-300 text-slate-800 focus:ring-0">
                        <div>
                            <span class="font-semibold text-gray-700">Pre-Authorize Auto-Pay Guard</span>
                            <p class="text-[10px] text-gray-500">Authorize system to settle the payment automatically via Stripe when 30 days remain.</p>
                        </div>
                    </label>
                </div>

                <button type="submit" class="w-full mt-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 rounded transition-colors text-center">
                    Save Contract Details
                </button>
            </form>
        </div>
    </div>

    <!-- Modal 2: Watchdog Simulator Controls Popup -->
    <div id="modal-simulator" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden" onclick="closeModalOnBackdrop(event, 'modal-simulator')">
        <div class="bg-white border border-gray-300 rounded-lg shadow-xl p-6 w-full max-w-md relative" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeModal('modal-simulator')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 border-b border-gray-200 pb-2 mb-3">Watchdog Simulation Controls</h3>
            <p class="text-[11px] text-gray-600 mb-4">Simulate backend automated processes to see how alerts and payment rules handle deadlines.</p>
            
            <div class="space-y-3 text-xs">
                <button onclick="runAlertCheck(); openModal('modal-logs');" class="w-full text-left bg-gray-100 hover:bg-gray-200 border border-gray-300 px-3 py-2.5 rounded flex items-center justify-between">
                    <span class="font-medium text-gray-700">Check for Pending 30-Day Warnings</span>
                    <span class="text-[10px] text-gray-500 font-mono">Run Engine</span>
                </button>
                <button onclick="runAutoPayCheck(); openModal('modal-logs');" class="w-full text-left bg-slate-100 hover:bg-slate-200 border border-gray-300 px-3 py-2.5 rounded flex items-center justify-between">
                    <span class="font-semibold text-slate-950">Run Automated Auto-Pay Event</span>
                    <span class="text-[10px] text-slate-500 font-mono">Stripe API</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 3: Terminal / Event Log Console Popup (Ref: image_6dbf9f.png) -->
    <div id="modal-logs" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden" onclick="closeModalOnBackdrop(event, 'modal-logs')">
        <div class="bg-slate-950 border border-slate-800 rounded-lg shadow-2xl p-5 w-full max-w-2xl h-[450px] flex flex-col relative" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeModal('modal-logs')" class="absolute top-4 right-4 text-slate-500 hover:text-slate-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="flex items-center justify-between border-b border-slate-800 pb-2 mb-3">
                <h3 class="text-xs font-bold font-mono text-slate-400 uppercase tracking-wider">System Event Log & Output Console</h3>
                <button onclick="clearConsole()" class="text-[10px] font-mono text-slate-500 hover:text-slate-300 pr-6">Clear Logs</button>
            </div>
            
            <div id="console-logs" class="flex-grow overflow-y-auto font-mono text-xs text-slate-300 space-y-2 pr-1">
                <!-- Dynamic logs (History log lines with individual information detail nodes) populate here -->
            </div>
        </div>
    </div>

    <!-- Modal 4: Selected Log Metadata Detail Card Popup -->
    <div id="modal-log-details" class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4 z-[60] hidden" onclick="closeModal('modal-log-details')">
        <div class="bg-white text-slate-950 border border-slate-300 rounded-lg shadow-2xl p-6 w-full max-w-sm relative" onclick="event.stopPropagation()">
            <!-- Close button -->
            <button onclick="closeModal('modal-log-details')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-gray-200">
                <div class="w-6 h-6 rounded-full flex items-center justify-center bg-blue-100 text-blue-700 font-serif italic font-bold">i</div>
                <h4 class="text-sm font-bold uppercase tracking-wide text-slate-800">Event Properties</h4>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-gray-400">Associated Vendor</span>
                    <span id="detail-vendor-name" class="font-semibold text-sm text-slate-900">-</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-gray-400">Time Registered / Activated</span>
                    <span id="detail-activated-date" class="font-mono text-slate-800">-</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-gray-400">Contract Expiration / Renewal</span>
                    <span id="detail-expiry-date" class="font-mono text-slate-800">-</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-gray-400">Value Amount</span>
                    <span id="detail-amount" class="font-semibold text-slate-900">-</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-gray-400">Event Action Log</span>
                    <p id="detail-action-text" class="bg-gray-50 border border-gray-200 p-2 rounded text-gray-700 italic font-mono text-[11px]"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = "api.php";
        let contracts = [];
        let systemLogs = [];
        let currentlyLoadedCode = ""; // Keep track of which statement profile is in viewport

        // Initialize application on window load by calling backend API
        window.onload = async function() {
            await loadContracts();
            await loadLogs();
        };

        // Modal Utility Helpers
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            if (id === 'modal-logs') {
                document.getElementById('unread-dot').classList.add('hidden');
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function closeModalOnBackdrop(event, id) {
            if (event.target === document.getElementById(id)) {
                closeModal(id);
            }
        }

        async function loadContracts() {
            try {
                const res = await fetch(`${API_URL}/contracts`);
                if (!res.ok) throw new Error("Failed to load contracts from API server.");
                contracts = await res.json();
                renderContracts();
            } catch (err) {
                console.error(err);
                logMessage("Connection failure: " + err.message);
            }
        }

        async function loadLogs() {
            try {
                const res = await fetch(`${API_URL}/logs`);
                if (res.ok) {
                    systemLogs = await res.json();
                    renderLogs();
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Render standard contracts table
        function renderContracts() {
            const tableBody = document.getElementById('contracts-table-body');
            const emptyState = document.getElementById('empty-state');
            const filterType = document.getElementById('filter-type').value;

            tableBody.innerHTML = '';

            const filteredContracts = contracts.filter(c => {
                if (filterType === 'all') return true;
                return c.type === filterType;
            });

            if (filteredContracts.length === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }

            filteredContracts.forEach(contract => {
                const daysLeft = calculateDaysRemaining(contract.expiryDate || contract.expiry_date);
                const expiryDateStr = contract.expiryDate || contract.expiry_date;
                
                // Construct basic warning strings
                let warningLabel = '';
                if (daysLeft <= 0) {
                    warningLabel = '<span class="text-gray-500 font-bold">[EXPIRED]</span>';
                } else if (daysLeft <= 30) {
                    warningLabel = `<span class="text-red-600 font-bold">[${daysLeft} days left]</span>`;
                } else {
                    warningLabel = `<span class="text-green-700 font-medium">[${daysLeft} days left]</span>`;
                }

                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50 border-b border-gray-200";
                tr.innerHTML = `
                    <td class="p-3">
                        <div class="font-bold text-gray-900">${escapeHTML(contract.vendor)}</div>
                        <div class="text-[10px] text-gray-500 font-mono mt-0.5">Code: <span class="bg-slate-100 border border-slate-300 px-1 py-0.5 rounded font-bold text-slate-800 cursor-pointer hover:bg-slate-200" title="Click to copy to search" onclick="copyCodeToSearch('${contract.code}')">${contract.code}</span></div>
                    </td>
                    <td class="p-3">${contract.type}</td>
                    <td class="p-3">
                        <div>${expiryDateStr}</div>
                        <div class="text-[10px] mt-0.5">${warningLabel}</div>
                    </td>
                    <td class="p-3 font-semibold text-gray-800">
                        ${contract.type === 'NDA' ? 'N/A' : '£' + Number(contract.amount).toLocaleString('en-GB')}
                    </td>
                    <td class="p-3">
                        ${contract.type === 'NDA' ? '<span class="text-gray-400">—</span>' : `
                            <div class="flex items-center gap-1.5">
                                <input type="checkbox" ${contract.autoPay || contract.auto_pay ? 'checked' : ''} 
                                    onchange="toggleAutoPay(${contract.id}, this.checked)" 
                                    class="rounded border-gray-300 text-slate-800 focus:ring-0">
                                <span class="${contract.autoPay || contract.auto_pay ? 'text-green-800 font-semibold' : 'text-gray-500'}">Authorized</span>
                            </div>
                        `}
                    </td>
                    <td class="p-3 text-right">
                        <button onclick="deleteContract(${contract.id})" class="text-red-600 hover:text-red-900 font-semibold hover:underline">
                            Delete
                        </button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        }

        function calculateDaysRemaining(dateString) {
            const expiry = new Date(dateString);
            const today = new Date();
            expiry.setHours(0,0,0,0);
            today.setHours(0,0,0,0);
            return Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));
        }

        function handleTypeChange() {
            const type = document.getElementById('input-type').value;
            const costField = document.getElementById('input-cost');
            const autoPayContainer = document.getElementById('autopay-container');

            if (type === 'NDA') {
                costField.value = '0';
                costField.disabled = true;
                autoPayContainer.style.display = 'none';
                document.getElementById('input-autopay').checked = false;
            } else {
                costField.disabled = false;
                autoPayContainer.style.display = 'flex';
            }
        }

        async function toggleAutoPay(id, isChecked) {
            try {
                const res = await fetch(`${API_URL}/contracts/${id}/autopay`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ autoPay: isChecked })
                });
                if (!res.ok) throw new Error("Failed to update auto-pay on server.");
                
                await loadContracts();
                await loadLogs();
                
                // Refresh statement box if currently inspecting this vendor
                if (currentlyLoadedCode) {
                    const activeContract = contracts.find(c => c.code === currentlyLoadedCode);
                    if (activeContract) {
                        renderActiveStatement(activeContract);
                    }
                }
            } catch (err) {
                console.error(err);
                logMessage("Action failure: " + err.message);
            }
        }

        async function addContract(e) {
            e.preventDefault();
            const vendor = document.getElementById('input-vendor').value.trim();
            const type = document.getElementById('input-type').value;
            const amount = type === 'NDA' ? 0 : parseFloat(document.getElementById('input-cost').value || 0);
            const date = document.getElementById('input-date').value;
            const autoRenew = document.getElementById('input-autorenew').checked;
            const autoPay = document.getElementById('input-autopay').checked;

            if (!vendor || !date) return;

            try {
                const res = await fetch(`${API_URL}/contracts`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        vendor,
                        type,
                        amount,
                        expiryDate: date,
                        autoRenew,
                        autoPay
                    })
                });

                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.detail || "Database registration failed.");
                }

                // Reset fields & Reload UI
                document.getElementById('contract-form').reset();
                handleTypeChange();
                closeModal('modal-add-contract');
                
                await loadContracts();
                await loadLogs();
            } catch (err) {
                console.error(err);
                logMessage("Creation failed: " + err.message);
            }
        }

        async function deleteContract(id) {
            try {
                const res = await fetch(`${API_URL}/contracts/${id}`, {
                    method: 'DELETE'
                });
                if (!res.ok) throw new Error("Could not delete contract from backend.");
                
                await loadContracts();
                await loadLogs();
                clearStatementView();
            } catch (err) {
                console.error(err);
                logMessage("Deletion failed: " + err.message);
            }
        }

        // Billing Cycles and Statement Search Logic
        function copyCodeToSearch(code) {
            document.getElementById('billing-lookup-code').value = code;
            lookupBillingCode();
        }

        async function lookupBillingCode() {
            const rawInput = document.getElementById('billing-lookup-code').value.trim();
            const feedback = document.getElementById('lookup-feedback');
            
            if (!rawInput) {
                showLookupError("Please enter a valid vendor code.");
                return;
            }

            const cleanCode = rawInput.toUpperCase();
            feedback.classList.add('hidden');

            try {
                const res = await fetch(`${API_URL}/contracts/${cleanCode}/statements`);
                if (!res.ok) {
                    const errorDetails = await res.json();
                    throw new Error(errorDetails.detail || "No contract matches this code.");
                }
                const contractData = await res.json();
                currentlyLoadedCode = cleanCode;
                renderActiveStatement(contractData);
            } catch (err) {
                showLookupError(err.message);
                clearStatementView();
            }
        }

        function showLookupError(msg) {
            const feedback = document.getElementById('lookup-feedback');
            feedback.innerText = msg;
            feedback.classList.remove('hidden');
        }

        function clearStatementView() {
            currentlyLoadedCode = "";
            document.getElementById('statement-profile').classList.add('hidden');
            document.getElementById('statement-empty-state').classList.remove('hidden');
        }

        function renderActiveStatement(contract) {
            document.getElementById('statement-empty-state').classList.add('hidden');
            const profile = document.getElementById('statement-profile');
            profile.classList.remove('hidden');

            const expiryDateStr = contract.expiryDate || contract.expiry_date;
            document.getElementById('stmt-vendor-name').innerText = `${contract.vendor} (${contract.code})`;
            
            // Check recurring value properties
            if (contract.type === 'NDA') {
                document.getElementById('stmt-interval').innerText = "None (Non-Disclosure Agreement)";
                document.getElementById('stmt-method').innerText = "No payment parameters configured";
                document.getElementById('stmt-method').className = "font-medium text-gray-500";
            } else {
                const dateParts = expiryDateStr.split('-');
                const renewalDay = dateParts.length === 3 ? dateParts[2] : "01";
                document.getElementById('stmt-interval').innerText = `Monthly recurring on renewal date (${renewalDay}th)`;
                
                if (contract.autoPay || contract.auto_pay) {
                    document.getElementById('stmt-method').innerText = "Stripe Gateway Guard (Active Auto-Pay)";
                    document.getElementById('stmt-method').className = "font-bold text-emerald-700";
                } else {
                    document.getElementById('stmt-method').innerText = "Manual Direct Deposit (Auto-Pay Off)";
                    document.getElementById('stmt-method').className = "font-medium text-amber-600";
                }
            }

            // Fill the statements table
            const tbody = document.getElementById('stmt-table-body');
            tbody.innerHTML = '';

            const statements = contract.statements || [];

            if (statements.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="p-3 text-center text-gray-400 italic">No transactional invoices generated yet for this record.</td></tr>`;
            } else {
                statements.forEach(stmt => {
                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-gray-50 border-b border-gray-100";
                    tr.innerHTML = `
                        <td class="p-2 font-mono">${stmt.date || stmt.statement_date}</td>
                        <td class="p-2 font-mono text-slate-500">${stmt.invoice_id || stmt.id}</td>
                        <td class="p-2 font-semibold text-slate-800">£${stmt.amount.toLocaleString('en-GB')}</td>
                        <td class="p-2 text-right">
                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold ${stmt.status.includes('Auto-Pay') || stmt.status.includes('Settled') || stmt.status.includes('Cleared') ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'}">
                                ${stmt.status}
                            </span>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }

        async function runAlertCheck() {
            try {
                const res = await fetch(`${API_URL}/simulator/alerts`, { method: 'POST' });
                if (!res.ok) throw new Error("Simulation workflow failed.");
                await loadLogs();
                openModal('modal-logs');
            } catch (err) {
                console.error(err);
                logMessage("Simulation failure: " + err.message);
            }
        }

        async function runAutoPayCheck() {
            try {
                const res = await fetch(`${API_URL}/simulator/autopay`, { method: 'POST' });
                if (!res.ok) throw new Error("Stripe auto-pay execution failed.");
                
                await loadContracts();
                await loadLogs();
                openModal('modal-logs');
                
                // Refresh live statement box if user is actively watching a paid vendor code
                if (currentlyLoadedCode) {
                    await lookupBillingCode();
                }
            } catch (err) {
                console.error(err);
                logMessage("Simulation failure: " + err.message);
            }
        }

        // Output and History Log Helpers
        function logMessage(text, metadataObj = null) {
            console.log("Client log:", text, metadataObj);
            const consoleBox = document.getElementById('console-logs');
            const logRow = document.createElement('div');
            logRow.className = "flex items-center justify-between py-1.5 px-2 hover:bg-slate-900 border-b border-slate-900/50 rounded gap-4 transition-colors group";
            
            const contentSpan = document.createElement('span');
            contentSpan.innerHTML = `<span class="text-slate-500 font-mono">[Local]</span> ${escapeHTML(text)}`;
            contentSpan.className = "break-words flex-grow max-w-[85%]";

            logRow.appendChild(contentSpan);
            consoleBox.appendChild(logRow);
            consoleBox.scrollTop = consoleBox.scrollHeight;
        }

        // Render logs in detail pane with explicit circular info trigger button (i)
        function renderLogs() {
            const consoleBox = document.getElementById('console-logs');
            consoleBox.innerHTML = '';

            systemLogs.forEach(log => {
                const logRow = document.createElement('div');
                logRow.className = "flex items-center justify-between py-1.5 px-2 hover:bg-slate-900 border-b border-slate-900/50 rounded gap-4 transition-colors group";
                
                // Left hand log string matching visual layouts in image_6dbf9f.png
                const contentSpan = document.createElement('span');
                contentSpan.innerHTML = `<span class="text-slate-500 font-mono">[${log.time || log.timestamp.split('T')[1].split('.')[0]}]</span> ${escapeHTML(log.text || log.message)}`;
                contentSpan.className = "break-words flex-grow max-w-[85%]";

                // Right hand circular information detail icon node (i)
                const infoBtn = document.createElement('button');
                infoBtn.onclick = () => showLogDetails(log.id);
                infoBtn.className = "w-5 h-5 rounded-full flex items-center justify-center bg-slate-800 text-slate-300 hover:bg-cyan-900 hover:text-cyan-200 transition-colors flex-shrink-0 focus:outline-none";
                infoBtn.title = "View Event Details";
                infoBtn.innerHTML = `<span class="font-serif italic font-extrabold text-[11px] leading-none mb-0.5">i</span>`;

                logRow.appendChild(contentSpan);
                logRow.appendChild(infoBtn);
                consoleBox.appendChild(logRow);
            });

            // Keep scrolling log bottom active
            consoleBox.scrollTop = consoleBox.scrollHeight;
        }

        // Open detailed properties modal for specific event items
        function showLogDetails(logId) {
            const matchedLog = systemLogs.find(l => l.id === logId);
            if (!matchedLog) return;

            const vendorName = matchedLog.vendor || matchedLog.associated_vendor || "General System";
            const dateActivated = matchedLog.dateActivated || matchedLog.timestamp || "-";
            const expiryDate = matchedLog.expiryDate || matchedLog.associated_expiry || "-";
            const amountVal = matchedLog.amount || matchedLog.associated_amount || "-";
            const textVal = matchedLog.text || matchedLog.message || "";

            document.getElementById('detail-vendor-name').innerText = vendorName;
            document.getElementById('detail-activated-date').innerText = dateActivated;
            document.getElementById('detail-expiry-date').innerText = expiryDate;
            document.getElementById('detail-amount').innerText = amountVal;
            document.getElementById('detail-action-text').innerText = textVal;

            openModal('modal-log-details');
        }

        async function clearConsole() {
            try {
                const res = await fetch(`${API_URL}/logs/clear`, { method: 'POST' });
                if (!res.ok) throw new Error("Could not clear database event logs.");
                systemLogs = [];
                renderLogs();
            } catch (err) {
                console.error(err);
            }
        }

        function escapeHTML(str) {
            if (!str) return '';
            return str.replace(/[&<>'"]/g, 
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                }[tag] || tag)
            );
        }
    </script>
</body>
</html>
