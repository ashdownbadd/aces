<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

/*
|--------------------------------------------------------------------------
| Dashboard Totals
|--------------------------------------------------------------------------
*/

$totalRemPrincipal = 0;
$totalRemInterest  = 0;
$totalRemPenalty   = 0;

foreach ($rows as $row) {

    if ($row['status'] === 'paid') {
        continue;
    }

    $totalRemPrincipal += (float) $row['rem_principal'];
    $totalRemInterest  += (float) $row['rem_interest'];
    $totalRemPenalty   += (float) $row['rem_penalty'];
}

$grandTotalDue =
    $totalRemPrincipal +
    $totalRemInterest +
    $totalRemPenalty;

?>

<div class="page loan-page">

    <?php

    c('page_header', [

        'title' => 'Loan Account',

        'description' =>
        htmlspecialchars(
            $loanData['last_name']
                . ', '
                . $loanData['first_name']
        )
            . ' • '
            . htmlspecialchars($loanData['loan_type'])

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="page__actions">

        <a
            href="<?= url('amortization_dashboard') ?>"
            class="btn btn--secondary">

            <i class="fas fa-arrow-left"></i>

            Back to Loan Portfolio

        </a>

        <a
            href="<?= url(
                        'print_soa',
                        [
                            'id' => $loanData['id']
                        ]
                    ) ?>"
            target="_blank"
            class="btn btn--primary">

            <i class="fas fa-print"></i>

            Print SOA

        </a>

    </div>

    <section class="section">

        <div class="section__body">

            <div class="stats">

                <div class="stats__card stats__card--primary">

                    <div class="stats__content">

                        <span class="stats__label">

                            Remaining Principal

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($totalRemPrincipal, 2) ?>

                        </span>

                        <span class="stats__description">

                            Outstanding principal balance

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-wallet"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--warning">

                    <div class="stats__content">

                        <span class="stats__label">

                            Remaining Interest

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($totalRemInterest, 2) ?>

                        </span>

                        <span class="stats__description">

                            Unpaid accrued interest

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-percent"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--danger">

                    <div class="stats__content">

                        <span class="stats__label">

                            Penalties

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($totalRemPenalty, 2) ?>

                        </span>

                        <span class="stats__description">

                            Accumulated penalties

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-triangle-exclamation"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--success">

                    <div class="stats__content">

                        <span class="stats__label">

                            Total Due

                        </span>

                        <span class="stats__value">

                            ₱<?= number_format($grandTotalDue, 2) ?>

                        </span>

                        <span class="stats__description">

                            Current outstanding obligation

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-calculator"></i>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Borrower Information

                </h2>

                <p class="section__description">

                    Cooperative member and loan account details.

                </p>

            </div>

        </div>

        <div class="section__body">

            <div class="form-grid form-grid--2">

                <div class="loan-panel">

                    <h3 class="loan-panel__title">

                        Member Profile

                    </h3>

                    <div class="loan-summary__row">

                        <span>Name</span>

                        <strong>

                            <?= htmlspecialchars(
                                $loanData['last_name']
                                    . ', '
                                    . $loanData['first_name']
                            ) ?>

                        </strong>

                    </div>

                    <div class="loan-summary__row">

                        <span>Member Number</span>

                        <strong>

                            <?= htmlspecialchars(
                                $loanData['member_number']
                            ) ?>

                        </strong>

                    </div>

                    <div class="loan-summary__row">

                        <span>Loan Type</span>

                        <strong>

                            <?= htmlspecialchars(
                                $loanData['loan_type']
                            ) ?>

                        </strong>

                    </div>

                    <div class="loan-summary__row">

                        <span>Collateral</span>

                        <strong>

                            <?= htmlspecialchars(
                                $loanData['collateral']
                            ) ?>

                        </strong>

                    </div>

                </div>

                <div class="loan-panel loan-panel--summary">

                    <h3 class="loan-panel__title">

                        Account Balance

                    </h3>

                    <div class="loan-summary__row">

                        <span>

                            Remaining Principal

                        </span>

                        <strong>

                            ₱<?= number_format(
                                    $totalRemPrincipal,
                                    2
                                ) ?>

                        </strong>

                    </div>

                    <div class="loan-summary__row">

                        <span>

                            Remaining Interest

                        </span>

                        <strong>

                            ₱<?= number_format(
                                    $totalRemInterest,
                                    2
                                ) ?>

                        </strong>

                    </div>

                    <div class="loan-summary__row">

                        <span>

                            Penalties

                        </span>

                        <strong>

                            ₱<?= number_format(
                                    $totalRemPenalty,
                                    2
                                ) ?>

                        </strong>

                    </div>

                    <div class="loan-summary__total">

                        <span>Total Due</span>

                        <span>

                            ₱<?= number_format(
                                    $grandTotalDue,
                                    2
                                ) ?>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Record Payment

                </h2>

                <p class="section__description">

                    Apply a payment using the waterfall distribution algorithm.

                </p>

            </div>

        </div>

        <div class="section__body">

            <form
                action="<?= url('apply_loan_payment') ?>"
                method="POST"
                class="form">

                <input
                    type="hidden"
                    name="loan_id"
                    value="<?= (int) $loanData['id'] ?>">

                <div class="form-inline">

                    <div class="form-group">

                        <label
                            class="form-label"
                            for="payment_amount">

                            Payment Amount

                        </label>

                        <input
                            class="form-control"
                            type="number"
                            id="payment_amount"
                            name="payment_amount"
                            step="0.01"
                            placeholder="0.00"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn btn--success">

                        <i class="fas fa-money-bill-wave"></i>

                        Apply Payment

                    </button>

                </div>

            </form>

        </div>

    </section>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Amortization Schedule

                </h2>

                <p class="section__description">

                    Monitor every payment period and perform administrative adjustments.

                </p>

            </div>

        </div>

        <div class="section__body">

            <table class="table">

                <thead>

                    <tr>

                        <th>Period</th>

                        <th>Due Date</th>

                        <th>Principal</th>

                        <th>Interest</th>

                        <th>Penalty</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($rows as $row): ?>

                        <tr>

                            <td>

                                P-<?= (int) $row['period'] ?>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $row['due_date']
                                ) ?>

                            </td>

                            <td>

                                ₱<?= number_format(
                                        $row['principal'],
                                        2
                                    ) ?>

                                <br>

                                <small>

                                    Remaining:
                                    ₱<?= number_format(
                                            $row['rem_principal'],
                                            2
                                        ) ?>

                                </small>

                            </td>

                            <td>

                                ₱<?= number_format(
                                        $row['interest'],
                                        2
                                    ) ?>

                                <br>

                                <small>

                                    Remaining:
                                    ₱<?= number_format(
                                            $row['rem_interest'],
                                            2
                                        ) ?>

                                </small>

                            </td>

                            <td>

                                ₱<?= number_format(
                                        $row['rem_penalty'],
                                        2
                                    ) ?>

                            </td>

                            <td>

                                <?php

                                $statusClass = match ($row['status']) {

                                    'paid' => 'loan-status loan-status--paid',

                                    'overdue' => 'loan-status loan-status--overdue',

                                    default => 'loan-status loan-status--active'
                                };

                                ?>

                                <span class="<?= $statusClass ?>">

                                    <?= strtoupper(
                                        htmlspecialchars($row['status'])
                                    ) ?>

                                </span>

                            </td>

                            <td>

                                <form
                                    action="<?= url('edit_schedule_period') ?>"
                                    method="POST"
                                    class="form-inline">

                                    <input
                                        type="hidden"
                                        name="schedule_id"
                                        value="<?= (int) $row['id'] ?>">

                                    <input
                                        type="hidden"
                                        name="loan_id"
                                        value="<?= (int) $loanData['id'] ?>">

                                    <input
                                        class="form-control"
                                        type="date"
                                        name="due_date"
                                        value="<?= htmlspecialchars(
                                                    $row['due_date']
                                                ) ?>">

                                    <input
                                        class="form-control"
                                        type="number"
                                        step="0.01"
                                        name="rem_penalty"
                                        value="<?= htmlspecialchars(
                                                    $row['rem_penalty']
                                                ) ?>">

                                    <input
                                        class="form-control"
                                        type="text"
                                        name="remarks"
                                        placeholder="Remarks">

                                    <button
                                        class="btn btn--warning"
                                        type="submit">

                                        Update

                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </section>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Payment History

                </h2>

                <p class="section__description">

                    Complete ledger of applied payments.

                </p>

            </div>

        </div>

        <div class="section__body">

            <table class="table">

                <thead>

                    <tr>

                        <th>Date</th>

                        <th>Amount</th>

                        <th>Penalty</th>

                        <th>Interest</th>

                        <th>Principal</th>

                        <th>Excess</th>

                        <th>Remarks</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($ledger)): ?>

                        <tr>

                            <td colspan="7" class="table__empty">

                                No payment history available.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($ledger as $log): ?>

                            <tr>

                                <td>

                                    <?= htmlspecialchars(
                                        $log['datetime']
                                    ) ?>

                                </td>

                                <td>

                                    ₱<?= number_format(
                                            $log['amount_paid'],
                                            2
                                        ) ?>

                                </td>

                                <td>

                                    ₱<?= number_format(
                                            $log['penalty_applied'],
                                            2
                                        ) ?>

                                </td>

                                <td>

                                    ₱<?= number_format(
                                            $log['interest_applied'],
                                            2
                                        ) ?>

                                </td>

                                <td>

                                    ₱<?= number_format(
                                            $log['principal_applied'],
                                            2
                                        ) ?>

                                </td>

                                <td>

                                    ₱<?= number_format(
                                            $log['excess'],
                                            2
                                        ) ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $log['remarks']
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</div>