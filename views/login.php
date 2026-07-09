<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="login-page">

    <div class="login-page__content">

        <div class="login-logo">

            <i class="fas fa-building-columns"></i>

        </div>

        <div class="login-brand">

            <span class="login-brand__eyebrow">

                COOPERATIVE MANAGEMENT

            </span>

            <h1 class="login-brand__title">

                <?= htmlspecialchars($app['name']) ?>

            </h1>

            <p class="login-brand__subtitle">

                <?= htmlspecialchars($app['full_name']) ?>

            </p>

            <span class="login-brand__portal">

                <?= htmlspecialchars($app['portal']) ?>

            </span>

        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'suspended'): ?>

            <div class="alert alert--danger">

                <strong>Access Denied.</strong>

                Your account has been suspended.

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

        <form
            class="login-form"
            action="<?= url('login'); ?>"
            method="POST">

            <div class="form-group">

                <label class="form-label">

                    Username

                </label>

                <input

                    class="form-control"

                    type="text"

                    name="username"

                    autocomplete="username"

                    autofocus

                    required>

            </div>

            <div class="form-group">

                <label class="form-label">

                    Password

                </label>

                <div class="password-field">

                    <input

                        id="password"

                        class="form-control"

                        type="password"

                        name="password"

                        autocomplete="current-password"

                        required>

                    <button

                        type="button"

                        class="password-toggle"

                        id="togglePassword"

                        aria-label="Show password">

                        <i class="fas fa-eye"></i>

                    </button>

                </div>

            </div>

            <button
                class="btn btn--primary btn--block"
                type="submit">

                Sign In

            </button>

        </form>

        <p class="login-page__copyright">

            <?= htmlspecialchars($app['copyright']) ?>

        </p>

    </div>

</div>