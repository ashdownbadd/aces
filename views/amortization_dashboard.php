<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page loan-page">

    <?php

    c('page_header', [
        'title' => 'Loan Portfolio',
        'description' => 'Manage cooperative loan accounts, monitor portfolio performance, and access borrower statements.'
    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="page__actions">

        <a
            href="<?= url('create_loan') ?>"
            class="btn btn--primary">

            <i class="fas fa-plus"></i>

            New Loan

        </a>

        <?php if ((int) ($_SESSION['role_id'] ?? 0) === 1): ?>

            <a
                href="<?= url('pending_loans_queue') ?>"
                class="btn btn--warning">

                <i class="fas fa-clock"></i>

                Pending Queue

            </a>

        <?php endif; ?>

    </div>

    <section class="section">

        <div class="section__body">

            <div class="stats-grid">

                <div class="stat-card stat-card--primary">

                    <div class="stat-card__header">

                        <div class="stat-card__heading">

                            <p class="stat-card__title">
                                Total Disbursed
                            </p>

                        </div>

                        <div class="stat-card__icon">

                            <i class="fas fa-hand-holding-dollar"></i>

                        </div>

                    </div>

                    <div class="stat-card__body">

                        <h3 class="stat-card__value">

                            ₱<?= number_format($totalDisbursed, 2) ?>

                        </h3>

                        <p class="stat-card__subtitle">

                            Released loan principal

                        </p>

                    </div>

                </div>

                <div class="stat-card stat-card--success">

                    <div class="stat-card__header">

                        <div class="stat-card__heading">

                            <p class="stat-card__title">
                                Projected Revenue
                            </p>

                        </div>

                        <div class="stat-card__icon">

                            <i class="fas fa-chart-line"></i>

                        </div>

                    </div>

                    <div class="stat-card__body">

                        <h3 class="stat-card__value">

                            ₱<?= number_format($projectedRevenue, 2) ?>

                        </h3>

                        <p class="stat-card__subtitle">

                            Expected interest income

                        </p>

                    </div>

                </div>

                <div class="stat-card stat-card--info">

                    <div class="stat-card__header">

                        <div class="stat-card__heading">

                            <p class="stat-card__title">
                                Collected
                            </p>

                        </div>

                        <div class="stat-card__icon">

                            <i class="fas fa-money-bill-wave"></i>

                        </div>

                    </div>

                    <div class="stat-card__body">

                        <h3 class="stat-card__value">

                            ₱<?= number_format($collectedToDate, 2) ?>

                        </h3>

                        <p class="stat-card__subtitle">

                            Payments received

                        </p>

                    </div>

                </div>

                <div class="stat-card stat-card--danger">

                    <div class="stat-card__header">

                        <div class="stat-card__heading">

                            <p class="stat-card__title">
                                Portfolio At Risk
                            </p>

                        </div>

                        <div class="stat-card__icon">

                            <i class="fas fa-triangle-exclamation"></i>

                        </div>

                    </div>

                    <div class="stat-card__body">

                        <h3 class="stat-card__value">

                            ₱<?= number_format($portfolioAtRisk, 2) ?>

                        </h3>

                        <p class="stat-card__subtitle">

                            Outstanding overdue balance

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="section">

        <div class="section__body">

            <div class="table">

                <div class="table__caption">

                    <div class="table__caption-title">

                        Loan Accounts

                    </div>

                </div>

                <div class="table__scroll">

                    <table>

                        <thead>

                            <tr>

                                <th>Borrower</th>
                                <th>Loan Type</th>
                                <th>Amortization</th>
                                <th>Principal</th>
                                <th>Term</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($loans)): ?>

                                <tr>

                                    <td
                                        colspan="6"
                                        class="table__empty">

                                        No approved loan accounts found.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($loans as $loan): ?>

                                    <?php

                                    $amortization = $loan['amortization_type'];

                                    if ($loan['loan_type'] === 'Micro-Finance Loan') {

                                        $amortization .=
                                            ' • '
                                            . $loan['payment_frequency'];
                                    }

                                    $status = strtolower($loan['soa_status']);

                                    $statusClass = match ($status) {

                                        'fully paid' => 'badge badge--success',

                                        'active' => 'badge badge--primary',

                                        'overdue' => 'badge badge--danger',

                                        default => 'badge badge--secondary'
                                    };

                                    ?>

                                    <tr
                                        onclick="window.location='<?= url('view_loan', ['id' => $loan['id']]) ?>'"
                                        style="cursor: pointer;">

                                        <td>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $loan['last_name']
                                                        . ', '
                                                        . $loan['first_name']
                                                ) ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                <?= htmlspecialchars($loan['member_number']) ?>

                                            </small>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars(
                                                $loan['loan_type']
                                            ) ?>

                                        </td>

                                        <td>

                                            <span class="badge badge--secondary">

                                                <?= htmlspecialchars(
                                                    $amortization
                                                ) ?>

                                            </span>

                                        </td>

                                        <td class="table__number">

                                            ₱<?= number_format($loan['principal'], 2) ?>

                                        </td>

                                        <td>

                                            <?= (int) $loan['terms'] ?>

                                            Months

                                        </td>

                                        <td>

                                            <span class="<?= $statusClass ?>">

                                                <?= htmlspecialchars(
                                                    $loan['soa_status']
                                                ) ?>

                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

</div>