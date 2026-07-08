<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$shareCapital = $member['ledger_balance'] ?? 0;

$hasLoan = !empty($member['active_loan']);

?>

<section class="member-summary">

    <h2 class="member-summary__title">

        Financial Summary

    </h2>

    <div class="member-summary__grid">

        <div class="member-summary__item">

            <span class="member-summary__label">

                Share Capital

            </span>

            <strong class="member-summary__value">

                ₱<?= number_format($shareCapital, 2); ?>

            </strong>

            <span class="member-summary__meta">

                Current Balance

            </span>

            <a
                class="member-summary__link"
                href="<?= htmlspecialchars(url('ledger_statement&id=' . ($member['id'] ?? ''))); ?>">
                View Ledger Statement →
            </a>

        </div>

        <div class="member-summary__item">

            <span class="member-summary__label">

                Active Loan

            </span>

            <?php if ($hasLoan): ?>

                <strong class="member-summary__value">

                    ₱<?= number_format(
                            $member['active_loan']['remaining_balance'] ?? 0,
                            2
                        ); ?>

                </strong>

                <span class="member-summary__meta">

                    Due
                    <?= display_value(
                        $member['active_loan']['next_due_date'] ?? null
                    ); ?>

                </span>

                <a
                    class="member-summary__link"
                    href="<?= htmlspecialchars(url('view_loan&id=' . ($member['active_loan']['id'] ?? ''))); ?>">
                    View Loan →
                </a>

            <?php else: ?>

                <strong class="member-summary__value">

                    None

                </strong>

                <span class="member-summary__meta">

                    No Active Loan

                </span>

            <?php endif; ?>

        </div>

    </div>

</section>