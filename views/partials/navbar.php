<?php
$currentRoute = $_GET['route'] ?? 'dashboard';
?>

<nav class="app-nav">

    <a href="index.php?route=dashboard"
        class="app-nav__link <?= $currentRoute === 'dashboard' ? 'app-nav__link--active' : '' ?>">
        <i class="fas fa-house"></i>
        Dashboard
    </a>

    <a href="index.php?route=members"
        class="app-nav__link <?= in_array($currentRoute, ['members', 'member_profile', 'add_member']) ? 'app-nav__link--active' : '' ?>">
        <i class="fas fa-users"></i>
        Members
    </a>

    <a href="index.php?route=amortization_dashboard"
        class="app-nav__link <?= str_contains($currentRoute, 'amortization') ? 'app-nav__link--active' : '' ?>">
        <i class="fas fa-money-bill-wave"></i>
        Loans
    </a>

    <a href="index.php?route=ledger"
        class="app-nav__link <?= str_contains($currentRoute, 'ledger') ? 'app-nav__link--active' : '' ?>">
        <i class="fas fa-book"></i>
        Ledger
    </a>

    <?php if (($_SESSION['role_id'] ?? 0) == 1): ?>

        <a href="index.php?route=admins"
            class="app-nav__link <?= $currentRoute === 'admins' ? 'app-nav__link--active' : '' ?>">
            <i class="fas fa-user-shield"></i>
            Admins
        </a>

        <a href="index.php?route=activity_logs"
            class="app-nav__link <?= $currentRoute === 'activity_logs' ? 'app-nav__link--active' : '' ?>">
            <i class="fas fa-chart-line"></i>
            Logs
        </a>

    <?php endif; ?>

</nav>