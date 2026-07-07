<?php

$currentRoute = $_GET['route'] ?? 'dashboard';

$isDashboard = $currentRoute === 'dashboard';

$isMembers = in_array(
    $currentRoute,
    [
        'members',
        'member_profile',
        'add_member'
    ],
    true
);

$isLoans =
    str_contains(
        $currentRoute,
        'amortization'
    );

$isLedger =
    str_contains(
        $currentRoute,
        'ledger'
    );

$isAdmins =
    $currentRoute === 'admins';

$isLogs =
    $currentRoute === 'activity_logs';

?>

<nav class="app-nav">

    <a
        href="<?= url('dashboard'); ?>"
        class="app-nav__link <?= $isDashboard ? 'app-nav__link--active' : ''; ?>">

        <i class="fas fa-house"></i>

        Dashboard

    </a>

    <a
        href="<?= url('members'); ?>"
        class="app-nav__link <?= $isMembers ? 'app-nav__link--active' : ''; ?>">

        <i class="fas fa-users"></i>

        Members

    </a>

    <a
        href="<?= url('amortization_dashboard'); ?>"
        class="app-nav__link <?= $isLoans ? 'app-nav__link--active' : ''; ?>">

        <i class="fas fa-money-bill-wave"></i>

        Loans

    </a>

    <a
        href="<?= url('ledger'); ?>"
        class="app-nav__link <?= $isLedger ? 'app-nav__link--active' : ''; ?>">

        <i class="fas fa-book"></i>

        Ledger

    </a>

    <?php if ((int) ($_SESSION['role_id'] ?? 0) === 1): ?>

        <a
            href="<?= url('admins'); ?>"
            class="app-nav__link <?= $isAdmins ? 'app-nav__link--active' : ''; ?>">

            <i class="fas fa-user-shield"></i>

            Admins

        </a>

        <a
            href="<?= url('activity_logs'); ?>"
            class="app-nav__link <?= $isLogs ? 'app-nav__link--active' : ''; ?>">

            <i class="fas fa-chart-line"></i>

            Logs

        </a>

    <?php endif; ?>

</nav>