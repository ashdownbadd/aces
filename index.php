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

    // Query the database to check current status in real-time
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Catch if the account is non-existent, inactive, or suspended
    if (!$user || (isset($user['status']) && $user['status'] !== 'active')) {
        session_unset();
        session_destroy();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['error_message'] = "Your operator profile has been suspended or deactivated. Contact an Administrator.";
        header("Location: index.php?route=login");
        exit;
    }
}

/**
 * Global Audit Logging System Tracker Hook
 */
function logSystemActivity($pdo, $action, $details)
{
    $userId   = $_SESSION['user_id'] ?? null;
    $username = $_SESSION['username'] ?? 'System/Anonymous';
    $ip       = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    try {
        $sql = "INSERT INTO activity_logs (user_id, username, action, details, ip_address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $username, $action, $details]);
    } catch (PDOException $e) {
        error_log("Audit logging failed: " . $e->getMessage());
    }
}

// --- CENTRAL APP GATEWAY SWITCH ROUTER ---
$route = $_GET['route'] ?? 'dashboard';

switch ($route) {
    // --- AUTHENTICATION ACTIONS LAYER ---
    case 'login':
        handleLogin($pdo);
        break;

    case 'logout':
        handleLogout();
        break;

    case 'register':
        handleRegistration($pdo);
        break;

    case 'dashboard':
        handleDashboard($pdo);
        break;

    // --- COOPERATIVE OPERATORS PROFILE CONTROL LAYER ---
    case 'admins':
        handleAdminList($pdo);
        break;

    case 'toggle_status':
        handleToggleStatus($pdo);
        break;

    case 'toggle_role':
        handleToggleRole($pdo);
        break;

    case 'activity_logs':
        checkAuthenticated($pdo);
        if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
            $_SESSION['error_message'] = "Access Denied: High security clearance requirements mismatch.";
            header("Location: index.php?route=dashboard");
            exit;
        }
        try {
            $logs = $pdo->query("SELECT * FROM activity_logs ORDER BY id DESC LIMIT 500")->fetchAll();
            include __DIR__ . '/views/activity_logs.php';
        } catch (PDOException $e) {
            die("Database audit logs fetch failure: " . $e->getMessage());
        }
        break;

    // --- SYSTEM REGISTERED MEMBERS DIRECTORY MODULE ---
    case 'members':
        handleCoopMemberList($pdo);
        break;

    case 'add_member':
        handleCreateCoopMember($pdo);
        break;

    case 'view_member':
        handleMemberProfile($pdo);
        break;

    // --- GENERAL ACCOUNTING LEDGER FRAMEWORK MODULE ---
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
        die("Error 404: The system command or module address requested cannot be resolved.");
}
