<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="login">

    <?php if (isset($_GET['error']) && $_GET['error'] === 'suspended'): ?>
        <div class="alert alert--danger">
            <strong>Access Denied:</strong>
            Your account has been suspended by an administrator.
            Please contact support.
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert--danger">
            <?= htmlspecialchars($_SESSION['error_message']); ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert--info">
            <?= htmlspecialchars($_SESSION['success_message']); ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <div class="login__card">

        <h2 class="login__title">
            Administrative Login
        </h2>

        <form class="form" action="index.php?route=login" method="POST">

            <div class="form__group">
                <label class="form__label" for="username">
                    Username
                </label>

                <input
                    id="username"
                    class="form__control"
                    type="text"
                    name="username"
                    required>
            </div>

            <div class="form__group">
                <label class="form__label" for="password">
                    Password
                </label>

                <input
                    id="password"
                    class="form__control"
                    type="password"
                    name="password"
                    required>
            </div>

            <button class="btn btn--primary btn--block" type="submit">
                Login
            </button>

        </form>

    </div>

</div>