<?php

if (!function_exists('checkAuthenticated')) {

    function checkAuthenticated(PDO $pdo): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $stmt = $pdo->prepare("
            SELECT status
            FROM users
            WHERE id = ?
        ");

        $stmt->execute([$_SESSION['user_id']]);

        $user = $stmt->fetch();

        if (!$user || $user['status'] !== 'active') {

            session_unset();
            session_destroy();

            session_start();

            flashError(
                'Your operator profile has been suspended or deactivated.'
            );

            redirect('login');
        }
    }
}
