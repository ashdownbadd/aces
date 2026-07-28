<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$financial = $member['financial_summary'] ?? [];

$shareCapital = $financial['share_capital'] ?? 0;
$savingsBalance = $financial['savings_balance'];
$loanBalance = $financial['loan_balance'];
$netPosition = $financial['net_position'] ?? $shareCapital;

$hasLoan = !empty($member['active_loan']);

ob_start();

?>

<div class="stats">

    <!-- Share Capital -->

    <div class="stats__card stats__card--gold">

        <div class="stats__content">

            <span class="stats__label">

                Share Capital

            </span>

            <span class="stats__value">

                ₱<?= number_format($shareCapital, 2); ?>

            </span>

            <span class="stats__description">

                Current Balance

            </span>

        </div>

        <div class="stats__icon">

            💰

        </div>

    </div>

    <!-- Savings -->

    <div class="stats__card stats__card--primary">

        <div class="stats__content">

            <span class="stats__label">

                Savings

            </span>

            <span class="stats__value">

                <?= $savingsBalance === null
                    ? 'Not Enrolled'
                    : '₱' . number_format($savingsBalance, 2); ?>

            </span>

            <span class="stats__description">

                Savings Module

            </span>

        </div>

        <div class="stats__icon">

            🏦

        </div>

    </div>

    <!-- Active Loan -->

    <div class="stats__card stats__card--warning">

        <div class="stats__content">

            <span class="stats__label">

                Active Loan

            </span>

            <span class="stats__value">

                <?= $hasLoan
                    ? '₱' . number_format(
                        $member['active_loan']['remaining_balance'] ?? 0,
                        2
                    )
                    : '₱0.00'; ?>

            </span>

            <span class="stats__description">

                <?= $hasLoan
                    ? 'Due ' . display_value(
                        $member['active_loan']['next_due_date'] ?? null
                    )
                    : 'No Active Loan'; ?>

            </span>

        </div>

        <div class="stats__icon">

            📄

        </div>

    </div>

    <!-- Net Position -->

    <div class="stats__card stats__card--success">

        <div class="stats__content">

            <span class="stats__label">

                Net Position

            </span>

            <span class="stats__value">

                ₱<?= number_format($netPosition, 2); ?>

            </span>

            <span class="stats__description">

                Share Capital − Loans

            </span>

        </div>

        <div class="stats__icon">

            📈

        </div>

    </div>

</div>

<?php

$body = ob_get_clean();

c('card', [

    'title' => 'Financial Summary',

    'subtitle' => 'Member financial overview',

    'body' => $body

]);
