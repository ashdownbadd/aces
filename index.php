<?php
// index.php

declare(strict_types=1);

define('ALLOW_ACCESS', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/MemberController.php';
require_once __DIR__ . '/controllers/LedgerController.php';
require_once __DIR__ . '/controllers/AmortizationController.php';

require_once __DIR__ . '/helpers/View.php';

/*
|--------------------------------------------------------------------------
| Global Helper Functions
|--------------------------------------------------------------------------
*/

function checkAuthenticated(PDO $pdo): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?route=login');
        exit;
    }

    $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch();

    if (!$user || $user['status'] !== 'active') {

        session_unset();
        session_destroy();

        session_start();

        $_SESSION['error_message'] =
            "Your operator profile has been suspended or deactivated.";

        header('Location: index.php?route=login');
        exit;
    }
}

function logSystemActivity(PDO $pdo, string $action, string $details): void
{
    try {

        $stmt = $pdo->prepare("
            INSERT INTO activity_logs
            (user_id, username, action, details, ip_address)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([

            $_SESSION['user_id'] ?? null,

            $_SESSION['username'] ?? 'System',

            $action,

            $details,

            $_SERVER['REMOTE_ADDR'] ?? 'Unknown'

        ]);
    } catch (PDOException $e) {

        error_log($e->getMessage());
    }
}

/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/

$route = $_GET['route'] ?? 'dashboard';

$content = '';

switch ($route) {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    case 'login':
        $content = handleLogin($pdo);
        break;

    case 'logout':
        handleLogout();
        break;

    case 'register':
        $content = handleRegistration($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    case 'dashboard':
        $content = handleDashboard($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Administrators
    |--------------------------------------------------------------------------
    */

    case 'admins':
        $content = handleAdminList($pdo);
        break;

    case 'toggle_status':
        handleToggleStatus($pdo);
        break;

    case 'toggle_role':
        handleToggleRole($pdo);
        break;

    case 'activity_logs':
        $content = handleActivityLogs($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Members
    |--------------------------------------------------------------------------
    */

    case 'members':
        $content = handleCoopMemberList($pdo);
        break;

    case 'add_member':
        $content = handleCreateCoopMember($pdo);
        break;

    case 'member_profile':
        $content = handleMemberProfile($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Ledger
    |--------------------------------------------------------------------------
    */

    case 'ledger':
        $content = handleLedgerDashboard($pdo);
        break;

    case 'add_ledger_entry':
        $content = handleCreateLedgerEntry($pdo);
        break;

    case 'ledger_statement':
        $content = handleLedgerStatement($pdo);
        break;

    case 'pending_approvals':
        $content = handlePendingApprovals($pdo);
        break;

    case 'approve_ledger_entry':
        handleApproveVoucher($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Loans
    |--------------------------------------------------------------------------
    */

    case 'amortization_dashboard':
        $content = handleAmortizationDashboard($pdo);
        break;

    case 'create_loan':
        $content = handleCreateLoan($pdo);
        break;

    case 'view_loan':
        $content = handleViewLoan($pdo);
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
        $content = handlePendingLoansQueue($pdo);
        break;

    case 'process_loan_approval':
        handleProcessLoanApproval($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | 404
    |--------------------------------------------------------------------------
    */

    default:
        http_response_code(404);
        die('404 - Route not found.');
}

/*
|--------------------------------------------------------------------------
| Layout
|--------------------------------------------------------------------------
*/

include __DIR__ . '/views/layout.php';
