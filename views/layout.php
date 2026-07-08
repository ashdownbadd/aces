<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$route = $_GET['route'] ?? 'dashboard';

$isLoginPage = in_array($route, ['login'], true);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include __DIR__ . '/partials/head.php'; ?>

</head>

<body>

    <?php if ($isLoginPage): ?>

        <?= $content ?>

    <?php else: ?>

        <?php include __DIR__ . '/partials/header.php'; ?>

        <?php if (isset($_SESSION['user_id'])): ?>

            <?php include __DIR__ . '/partials/navbar.php'; ?>

        <?php endif; ?>

        <main class="container">

            <?= $content ?>

        </main>

        <?php include __DIR__ . '/partials/footer.php'; ?>

    <?php endif; ?>

    <?php include __DIR__ . '/partials/scripts.php'; ?>

</body>

</html>