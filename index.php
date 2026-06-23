<?php
// index.php

// Define application core security access token
define('ALLOW_ACCESS', true);

// Start native session state engine
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Establish core system requirements
require_once __DIR__ . '/config/db.php';

// 2. Move all controller requirements to the top (Globally available)
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/MemberController.php';
require_once __DIR__ . '/controllers/LedgerController.php';
require_once __DIR__ . '/controllers/AmortizationController.php';

function checkAuthenticated($pdo)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?route=login");
        exit;
    }

    // Query the database to check current status
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Check if the account is currently 'suspended'
    if (!$user || (isset($user['status']) && $user['status'] === 'suspended')) {
        session_destroy();
        // Redirect with a specific 'error' flag
        header("Location: index.php?route=login&error=suspended");
        exit;
    }
}

// 3. Extract requested module route parameter safely
$route = $_GET['route'] ?? (isset($_SESSION['user_id']) ? 'dashboard' : 'login');

// 4. Clean Central Application Core Routing Engine Switch (No inner requires)
switch ($route) {
    // --- AUTHENTICATION INTERFACE ROUTING ---
    case 'login':
        handleLogin($pdo);
        break;

    case 'logout':
        handleLogout();
        break;

    case 'dashboard':
        handleDashboard($pdo);
        break;

    case 'admins':
        handleAdminList($pdo);
        break;

    case 'toggle_status':
        handleToggleStatus($pdo);
        break;

    // --- COOPERATIVE MEMBERSHIP DIRECTORY MODULE ---
    case 'members':
        handleCoopMemberList($pdo);
        break;

    case 'member_profile':
        handleMemberProfile($pdo);
        break;

    case 'add_coop_member':
        handleCreateCoopMember($pdo);
        break;

    // --- GENERAL LEDGER SUBSYSTEM MODULE ---
    case 'ledger':
        handleLedgerDashboard($pdo);
        break;

    case 'add_ledger_entry':
        handleCreateLedgerEntry($pdo);
        break;

    case 'ledger_statement':
        handleLedgerStatement($pdo);
        break;

    case 'pending_approvals':
        handlePendingApprovals($pdo);
        break;

    case 'approve_ledger_entry':
        handleApproveVoucher($pdo);
        break;

    // --- AMORTIZATION CALCULATOR SUBSYSTEM MODULE ---
    case 'amortization_dashboard':
        handleAmortizationDashboard($pdo);
        break;

    case 'create_loan':
        handleCreateLoan($pdo);
        break;

    case 'view_loan':
        handleViewLoan($pdo);
        break;

    case 'apply_loan_payment':
        handleApplyPayment($pdo);
        break;

    case 'print_soa':
        handlePrintSOA($pdo);
        break;

    case 'edit_schedule_period':
        handleEditSchedulePeriod($pdo);
        break;

    case 'pending_loans_queue':
        handlePendingLoansQueue($pdo);
        break;

    case 'process_loan_approval':
        handleProcessLoanApproval($pdo);
        break;

    default:
        http_response_code(404);
        echo "<h2 style='color:red; font-family:Arial;'>404 Error: Core module endpoint [ " . htmlspecialchars($route) . " ] not found.</h2>";
        echo "<p style='font-family:Arial;'><a href='index.php?route=login'>← Return to login panel</a></p>";
        break;
}
