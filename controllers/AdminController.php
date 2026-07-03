<?php
// controllers/AdminController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * Fetches all registered system operators along with their descriptive role names
 * SECURED: Only administrators can view this listing
 */
function handleAdminList(PDO $pdo): string
{
    checkAuthenticated($pdo);

    if (($_SESSION['role_id'] ?? 0) != 1) {
        $_SESSION['error_message'] =
            "Access Denied: Administrative security privileges required.";

        header("Location: index.php?route=dashboard");
        exit;
    }

    try {

        $stmt = $pdo->query("
            SELECT
                u.id,
                u.username,
                u.email,
                u.status,
                u.role_id,
                r.role_name
            FROM users u
            INNER JOIN roles r
                ON u.role_id = r.id
            ORDER BY u.id DESC
        ");

        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();

        include dirname(__DIR__) . '/views/admin.php';

        return ob_get_clean();
    } catch (PDOException $e) {

        die("Database error fetching administrator records: " . $e->getMessage());
    }
}
/**
 * Toggles an administrator's status between 'active' and 'suspended'
 * SECURED: Restricts staff from altering any status accounts
 */
function handleToggleStatus($pdo)
{
    checkAuthenticated($pdo);

    // CRITICAL ACCESS LOCK: Only actual Admins can run account mutations
    if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
        $_SESSION['error_message'] = "Security Violation: Staff accounts are completely restricted from changing account statuses.";
        header("Location: index.php?route=dashboard");
        exit;
    }

    $userId = intval($_GET['id'] ?? 0);

    if ($userId <= 0) {
        $_SESSION['error_message'] = "Invalid user identification provided.";
        header("Location: index.php?route=admins");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, role_id, status FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error_message'] = "User record not found.";
            header("Location: index.php?route=admins");
            exit;
        }

        // 1. Prevent self-lockout
        if ($userId === $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Security constraint: You cannot suspend your own administrative session!";
            header("Location: index.php?route=admins");
            exit;
        }

        $newStatus = ($user['status'] === 'active') ? 'suspended' : 'active';

        $updateStmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        $updateStmt->execute([
            ':status' => $newStatus,
            ':id'     => $userId
        ]);

        // LOG ACTION FOOTPRINT
        logSystemActivity($pdo, "USER_STATUS_TOGGLE", "Changed status of operator #{$userId} ({$user['username']}) to " . strtoupper($newStatus));

        $_SESSION['success_message'] = "Account status for '{$user['username']}' updated to " . strtoupper($newStatus) . " successfully.";
        header("Location: index.php?route=admins");
        exit;
    } catch (PDOException $e) {
        die("Database error updating status trace: " . $e->getMessage());
    }
}

/**
 * Toggles an operator's role between Administrator (1) and Staff (2)
 * SECURED: Restricts staff from promoting or demoting anyone
 */
function handleToggleRole($pdo)
{
    checkAuthenticated($pdo);

    // CRITICAL ACCESS LOCK: Only actual Admins can modify user clearance ranks
    if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
        $_SESSION['error_message'] = "Security Violation: Staff accounts are completely restricted from altering user clearance ranks.";
        header("Location: index.php?route=dashboard");
        exit;
    }

    $userId = intval($_GET['id'] ?? 0);

    if ($userId <= 0) {
        $_SESSION['error_message'] = "Invalid user identification provided.";
        header("Location: index.php?route=admins");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, role_id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error_message'] = "User record not found.";
            header("Location: index.php?route=admins");
            exit;
        }

        // 1. Prevent self-demotion
        if ($userId === $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Security constraint: You cannot demote your own active administrative session!";
            header("Location: index.php?route=admins");
            exit;
        }

        // 2. Determine new role (If 1, change to 2. If 2, change to 1)
        $newRole = (intval($user['role_id']) === 1) ? 2 : 1;
        $roleLabel = ($newRole === 1) ? 'ADMINISTRATOR' : 'STAFF';

        $updateStmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        $updateStmt->execute([$newRole, $userId]);

        // LOG ACTION FOOTPRINT
        logSystemActivity($pdo, "USER_ROLE_TOGGLE", "Altered role of operator #{$userId} ({$user['username']}) to {$roleLabel}");

        $_SESSION['success_message'] = "Account clearance for '{$user['username']}' updated to {$roleLabel} successfully.";
        header("Location: index.php?route=admins");
        exit;
    } catch (PDOException $e) {
        die("Database error updating user authorization rank: " . $e->getMessage());
    }
}
