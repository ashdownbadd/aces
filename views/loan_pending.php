<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page loan-page">

    <?php

    c('page_header', [

        'title' => 'Pending Loan Applications',

        'description' =>
        'Review, approve, or reject newly submitted loan applications before activation.'

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="page__actions">

        <a
            href="index.php?route=amortization_dashboard"
            class="btn btn--secondary">

            <i class="fas fa-arrow-left"></i>

            Back to Loan Portfolio

        </a>

    </div>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Verification Queue

                </h2>

                <p class="section__description">

                    Applications waiting for administrator approval.

                </p>

            </div>

        </div>

        <div class="section__body">

            <table class="table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Borrower</th>

                        <th>Loan Type</th>

                        <th>Principal</th>

                        <th>Terms</th>

                        <th>Collateral</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($pending_loans)): ?>

                        <tr>

                            <td colspan="7" class="table__empty">

                                🎉 No pending loan applications.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($pending_loans as $loan): ?>

                            <tr>

                                <td>

                                    <strong>

                                        #<?= (int) $loan['id'] ?>

                                    </strong>

                                </td>

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

                                            Member No.
                                            <?= htmlspecialchars(
                                                $loan['member_number']
                                            ) ?>

                                        </small>

                                    </div>

                                </td>

                                <td>

                                    <span class="badge badge--secondary">

                                        <?= htmlspecialchars(
                                            $loan['loan_type']
                                        ) ?>

                                    </span>

                                </td>

                                <td class="loan-table__amount">

                                    ₱<?= number_format(
                                            $loan['principal'],
                                            2
                                        ) ?>

                                </td>

                                <td>

                                    <?= (int) $loan['terms'] ?>

                                    Months

                                    <br>

                                    <small>

                                        <?= htmlspecialchars(
                                            $loan['interest_rate']
                                        ) ?>%

                                        •

                                        <?= htmlspecialchars(
                                            $loan['payment_frequency']
                                        ) ?>

                                        •

                                        <?= htmlspecialchars(
                                            $loan['amortization_type']
                                        ) ?>

                                    </small>

                                </td>

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $loan['collateral']
                                        ) ?>

                                    </strong>

                                    <?php if (!empty($loan['tct_no'])): ?>

                                        <br>

                                        <small>

                                            TCT:

                                            <?= htmlspecialchars(
                                                $loan['tct_no']
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <div class="loan-table__actions">

                                        <form
                                            action="index.php?route=process_loan_approval"
                                            method="POST"
                                            onsubmit="return confirm('Approve this loan application?');">

                                            <input
                                                type="hidden"
                                                name="loan_id"
                                                value="<?= (int) $loan['id'] ?>">

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="Approve">

                                            <button
                                                type="submit"
                                                class="btn btn--success">

                                                <i class="fas fa-check"></i>

                                                Approve

                                            </button>

                                        </form>

                                        <form
                                            action="index.php?route=process_loan_approval"
                                            method="POST"
                                            onsubmit="return confirm('Reject this loan application?');">

                                            <input
                                                type="hidden"
                                                name="loan_id"
                                                value="<?= (int) $loan['id'] ?>">

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="Reject">

                                            <button
                                                type="submit"
                                                class="btn btn--danger">

                                                <i class="fas fa-times"></i>

                                                Reject

                                            </button>

                                        </form>

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