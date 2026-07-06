<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * ==========================================================
 * Activity Logs
 * ==========================================================
 */
function handleActivityLogs(PDO $pdo): string
{
    checkAuthenticated($pdo);

    try {

        $stmt = $pdo->query("
            SELECT
                id,
                user_id,
                username,
                action,
                details,
                ip_address,
                created_at
            FROM activity_logs
            ORDER BY created_at DESC, id DESC
        ");

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return render('activity_logs', [
            'logs' => $logs
        ]);
    } catch (PDOException $e) {

        error_log($e->getMessage());

        $_SESSION['error_message'] =
            'Unable to load activity logs.';

        return render('activity_logs', [
            'logs' => []
        ]);
    }
}
