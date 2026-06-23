<?php
// controllers/AuthController.php

// Defensive check: Ensure this file is only accessed through our single entry point
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * Handles processing of the registration form submission
 * @param PDO $pdo The active database connection instance
 */
function handleRegistration($pdo)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?route=register");
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id  = intval($_POST['role_id'] ?? 0);

    if (empty($username) || empty($email) || empty($password) || empty($role_id)) {
        die("Error: All form fields are required.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (role_id, username, email, password_hash) 
                VALUES (:role_id, :username, :email, :password_hash)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':role_id'       => $role_id,
            ':username'      => $username,
            ':email'         => $email,
            ':password_hash' => $hashedPassword,
        ]);

        $_SESSION['success_message'] = "Registration successful! You can now log in.";
        header("Location: index.php?route=login");
        exit;
    } catch (PDOException $e) {
        die("Database error during registration: " . $e->getMessage());
    }
}

/**
 * Handles authentication checks and processing for user login attempts
 * @param PDO $pdo The active database connection instance
 */
function handleLogin($pdo)
{
    // If the user is already logged in, redirect them away from the login panel to the dashboard
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php?route=dashboard");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error_message'] = "Both fields are strictly required.";
            header("Location: index.php?route=login");
            exit;
        }

        try {
            $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                if (isset($user['status']) && $user['status'] === 'suspended') {
                    $_SESSION['error_message'] = "Your administrative access has been deactivated.";
                    header("Location: index.php?route=login");
                    exit;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];

                header("Location: index.php?route=dashboard");
                exit;
            } else {
                $_SESSION['error_message'] = "Invalid administrative credentials provided.";
                header("Location: index.php?route=login");
                exit;
            }
        } catch (PDOException $e) {
            die("Database error during login processing: " . $e->getMessage());
        }
    }

    include dirname(__DIR__) . '/views/login.php';
}

/**
 * Handles destroying the session and logging the user out completely
 */
function handleLogout()
{
    // 1. Unset all session variables
    $_SESSION = [];

    // 2. Erase the session cookie completely from the user's browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 3. Destroy the session on the server side
    session_destroy();

    // 4. Restart a clean session to carry the logged out message safely
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['success_message'] = "You have been securely logged out.";
    header("Location: index.php?route=login");
    exit;
}
