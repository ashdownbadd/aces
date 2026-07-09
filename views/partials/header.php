<header class="app-header">

    <div class="app-header__brand">

        <a href="<?= url('dashboard'); ?>" class="app-header__logo">

            <i class="fas fa-building"></i>

            <span class="app-header__title">

                <?= htmlspecialchars($app['name']) ?>

            </span>

        </a>

    </div>

    <?php if (isset($_SESSION['user_id'])): ?>

        <div class="app-header__user">

            <span class="app-header__welcome">

                Welcome,

                <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>

            </span>

            <a
                href="<?= url('logout'); ?>"
                class="btn btn--danger btn--sm">

                <i class="fas fa-right-from-bracket"></i>

                Logout

            </a>

        </div>

    <?php endif; ?>

</header>