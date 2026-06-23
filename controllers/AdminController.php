<?php
// controllers/AdminController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * Fetches all registered system operators along with their descriptive role names
 * @param PDO $pdo The active database connection instance
 */
function handleAdminList($pdo)
{
    checkAuthenticated($pdo);

    try {
        $sql = "SELECT u.id, u.username, u.email, u.status, r.role_name 
                FROM users u
                INNER JOIN roles r ON u.role_id = r.id
                ORDER BY u.id DESC";

        $stmt = $pdo->query($sql);
        $members = $stmt->fetchAll();

        include dirname(__DIR__) . '/views/admin.php';
    } catch (PDOException $e) {
        die("Database error fetching administrator records: " . $e->getMessage());
    }
}

/**
 * Toggles an administrator's status between 'active' and 'suspended'
 * @param PDO $pdo The active database connection instance
 */
function handleToggleStatus($pdo)
{
    checkAuthenticated($pdo);

    $userId = intval($_GET['id'] ?? 0);

    if ($userId <= 0) {
        $_SESSION['error_message'] = "Invalid user identification provided.";
        header("Location: index.php?route=admins");
        exit;
    }

    try {
        // Fetch target user context along with their explicit role mapping
        $stmt = $pdo->prepare("SELECT u.status, u.username, u.role_id, r.role_name FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE u.id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
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

        // 2. BACKEND PERMISSION CHECK: Staff users (role_id = 2) cannot alter Admin accounts (role_id = 1)
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2 && $user['role_id'] == 1) {
            $_SESSION['error_message'] = "Security Exception: Staff accounts are completely restricted from changing Admin states.";
            header("Location: index.php?route=admins");
            exit;
        }

        $newStatus = ($user['status'] === 'active') ? 'suspended' : 'active';

        $updateStmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        $updateStmt->execute([
            ':status' => $newStatus,
            ':id'     => $userId
        ]);

        $_SESSION['success_message'] = "Account status for '{$user['username']}' updated to " . strtoupper($newStatus) . " successfully.";
        header("Location: index.php?route=admins");
        exit;
    } catch (PDOException $e) {
        die("Database error during status toggle adjustment: " . $e->getMessage());
    }
}
