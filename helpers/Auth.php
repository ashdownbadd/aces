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
            LIMIT 1
        ");

        $stmt->execute([
            $_SESSION['user_id']
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['status'] !== 'active') {

            $_SESSION = [];

            if (ini_get('session.use_cookies')) {

                $params = session_get_cookie_params();

                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            flashError(
                'Your operator profile has been suspended or deactivated.'
            );

            redirect('login');
        }
    }
}

if (!function_exists('requireAdmin')) {

    function requireAdmin(): void
    {
        if ((int) ($_SESSION['role_id'] ?? 0) !== 1) {

            redirectError(
                'dashboard',
                'Administrator privileges required.'
            );
        }
    }
}
