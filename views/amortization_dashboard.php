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
            href="<?= url('dashboard') ?>"
            class="btn btn--secondary">
            ← Back to Dashboard
        </a>

    </div>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Portfolio Overview

                </h2>

                <p class="section__description">

                    Current financial position of all approved loans.

                </p>

            </div>

        </div>

        <div class="section__body">

            <div class="stats">

                <div class="stats__card stats__card--primary">

                    <div class="stats__content">

                        <span class="stats__label">

                            Total Disbursed

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($totalDisbursed, 2) ?>

                        </span>

                        <span class="stats__description">

                            Released loan principal

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-hand-holding-dollar"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--success">

                    <div class="stats__content">

                        <span class="stats__label">

                            Projected Revenue

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($projectedRevenue, 2) ?>

                        </span>

                        <span class="stats__description">

                            Expected interest income

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-chart-line"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--info">

                    <div class="stats__content">

                        <span class="stats__label">

                            Collected

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($collectedToDate, 2) ?>

                        </span>

                        <span class="stats__description">

                            Payments received

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-money-bill-wave"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--danger">

                    <div class="stats__content">

                        <span class="stats__label">

                            Portfolio At Risk

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($portfolioAtRisk, 2) ?>

                        </span>

                        <span class="stats__description">

                            Outstanding overdue balance

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-triangle-exclamation"></i>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Loan Portfolio

                </h2>

                <p class="section__description">

                    Active cooperative loan accounts.

                </p>

            </div>

            <div class="loan-toolbar">

                <div class="loan-toolbar__actions">

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

            </div>

        </div>

        <div class="section__body">

            <table class="table">

                <thead>

                    <tr>

                        <th>Borrower</th>

                        <th>Loan Type</th>

                        <th>Amortization</th>

                        <th>Principal</th>

                        <th>Term</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($loans)): ?>

                        <tr>

                            <td colspan="7" class="table__empty">

                                No approved loan accounts found.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($loans as $loan): ?>

                            <tr>

                                <td>

                                    <div class="loan-table__member">

                                        <strong>

                                            <?= htmlspecialchars(
                                                $loan['last_name']
                                                    . ', '
                                                    . $loan['first_name']
                                            ) ?>

                                        </strong>

                                        <small>

                                            <?= htmlspecialchars(
                                                $loan['member_number']
                                            ) ?>

                                        </small>

                                    </div>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $loan['loan_type']
                                    ) ?>

                                </td>

                                <td>

                                    <?php
                                    $amortization = $loan['amortization_type'];

                                    if (
                                        $loan['loan_type']
                                        === 'Micro-Finance Loan'
                                    ) {

                                        $amortization .=
                                            ' (' .
                                            $loan['payment_frequency'] .
                                            ')';
                                    }
                                    ?>

                                    <span class="badge">

                                        <?= htmlspecialchars(
                                            $amortization
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="loan-table__amount">

                                        ₱<?= number_format(
                                                $loan['principal'],
                                                2
                                            ) ?>

                                    </span>

                                </td>

                                <td>

                                    <?= (int) $loan['terms'] ?>

                                    Months

                                </td>

                                <td>

                                    <?php

                                    $status =
                                        strtolower(
                                            $loan['soa_status']
                                        );

                                    $class =
                                        $status === 'fully paid'
                                        ? 'loan-status--paid'
                                        : 'loan-status--active';

                                    ?>

                                    <span
                                        class="loan-status <?= $class ?>">

                                        <?= htmlspecialchars(
                                            $loan['soa_status']
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <div class="loan-table__actions">

                                        <a
                                            href="<?= url(
                                                        'view_loan&id='
                                                            . $loan['id']
                                                    ) ?>"
                                            class="btn btn--success">

                                            <i class="fas fa-eye"></i>

                                            View

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</div>