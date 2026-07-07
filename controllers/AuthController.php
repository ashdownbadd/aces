<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * Handles processing of the registration form submission
 * @param PDO $pdo The active database connection instance
 */
function handleRegistration(PDO $pdo): string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return render('register');
    }

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id  = (int) ($_POST['role_id'] ?? 0);

    if (
        $username === '' ||
        $email === '' ||
        $password === '' ||
        $role_id <= 0
    ) {

        flashError(
            'All fields are required.'
        );

        return render('register');
    }

    try {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $pdo->prepare("
            INSERT INTO users
            (
                role_id,
                username,
                email,
                password_hash
            )
            VALUES
            (
                :role_id,
                :username,
                :email,
                :password_hash
            )
        ");

        $stmt->execute([

            ':role_id'       => $role_id,
            ':username'      => $username,
            ':email'         => $email,
            ':password_hash' => $hashedPassword

        ]);

        redirectSuccess(
            'login',
            'Registration successful! You can now log in.'
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        flashError(
            'Registration failed.'
        );

        return render('register');
    }
}
/**
 * Handles authentication checks and processing for user login attempts
 * @param PDO $pdo The active database connection instance
 */
function handleLogin(PDO $pdo): string
{
    if (isset($_SESSION['user_id'])) {
        redirect('dashboard');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {

            redirectError(
                'login',
                'Both fields are required.'
            );
        }

        try {

            $stmt = $pdo->prepare("
                SELECT *
                FROM users
                WHERE username = ?
                LIMIT 1
            ");

            $stmt->execute([$username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$user ||
                !password_verify($password, $user['password_hash'])
            ) {

                redirectError(
                    'login',
                    'Invalid username or password.'
                );
            }

            if ($user['status'] !== 'active') {

                redirectError(
                    'login',
                    'Your account has been deactivated.'
                );
            }

            session_regenerate_id(true);

            $_SESSION['user_id']  = (int) $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id']  = (int) $user['role_id'];

            redirect('dashboard');
        } catch (PDOException $e) {

            error_log($e->getMessage());

            redirectError(
                'login',
                'Unable to log in. Please try again.'
            );
        }
    }

    return render('login');
}

/**
 * Handles destroying the session and logging the user out completely
 */
function handleLogout(): void
{
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

    redirectSuccess(
        'login',
        'You have been securely logged out.'
    );
}
