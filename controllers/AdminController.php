<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
function handleAdminList(PDO $pdo): string
{
    checkAuthenticated($pdo);
    requireAdmin();

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

            ORDER BY
                u.id DESC
        ");

        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return render(
            'admin',
            [
                'members' => $members
            ]
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'dashboard',
            'Unable to load administrator records.'
        );
    }
}

function handleToggleStatus(PDO $pdo): void
{
    checkAuthenticated($pdo);
    requireAdmin();

    $userId = (int) ($_GET['id'] ?? 0);

    if ($userId <= 0) {

        redirectError(
            'admins',
            'Invalid user identification provided.'
        );
    }

    try {

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                role_id,
                status
            FROM users
            WHERE id = ?
        ");

        $stmt->execute([$userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            redirectError(
                'admins',
                'User record not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Self Suspension
        |--------------------------------------------------------------------------
        */

        if ($userId === (int) $_SESSION['user_id']) {

            redirectError(
                'admins',
                'You cannot suspend your own account.'
            );
        }

        $newStatus =
            $user['status'] === 'active'
            ? 'suspended'
            : 'active';

        $stmt = $pdo->prepare("
            UPDATE users
            SET status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $newStatus,
            $userId
        ]);

        logSystemActivity(
            $pdo,
            'USER_STATUS_TOGGLE',
            "Changed status of operator #{$userId} ({$user['username']}) to " . strtoupper($newStatus)
        );

        redirectSuccess(
            'admins',
            "Account status for '{$user['username']}' updated to " . strtoupper($newStatus) . '.'
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'admins',
            'Unable to update account status.'
        );
    }
}

function handleToggleRole(PDO $pdo): void
{
    checkAuthenticated($pdo);
    requireAdmin();

    $userId = (int) ($_GET['id'] ?? 0);

    if ($userId <= 0) {

        redirectError(
            'admins',
            'Invalid user identification provided.'
        );
    }

    try {

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                role_id
            FROM users
            WHERE id = ?
        ");

        $stmt->execute([$userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            redirectError(
                'admins',
                'User record not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Self Demotion
        |--------------------------------------------------------------------------
        */

        if ($userId === (int) $_SESSION['user_id']) {

            redirectError(
                'admins',
                'You cannot change your own role.'
            );
        }

        $newRole =
            ((int) $user['role_id'] === 1)
            ? 2
            : 1;

        $roleLabel =
            $newRole === 1
            ? 'ADMINISTRATOR'
            : 'STAFF';

        $stmt = $pdo->prepare("
            UPDATE users
            SET role_id = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $newRole,
            $userId
        ]);

        logSystemActivity(
            $pdo,
            'USER_ROLE_TOGGLE',
            "Altered role of operator #{$userId} ({$user['username']}) to {$roleLabel}"
        );

        redirectSuccess(
            'admins',
            "Account clearance for '{$user['username']}' updated to {$roleLabel}."
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'admins',
            'Unable to update account role.'
        );
    }
}
