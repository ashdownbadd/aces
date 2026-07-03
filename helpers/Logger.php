<?php

if (!function_exists('logSystemActivity')) {

    function logSystemActivity(
        PDO $pdo,
        string $action,
        string $details
    ): void {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO activity_logs
                (
                    user_id,
                    username,
                    action,
                    details,
                    ip_address
                )
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
}
