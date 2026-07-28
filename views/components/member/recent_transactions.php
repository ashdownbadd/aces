<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$transactions = $member['recent_transactions'] ?? [];

ob_start();

?>

<?php if (empty($transactions)): ?>

    <div class="member-empty">

        No recent transactions available.

    </div>

<?php else: ?>

    <div class="transaction-feed">

        <?php foreach ($transactions as $transaction): ?>

            <?php

            $isCredit = ($transaction['credit'] ?? 0) > 0;

            $amount = $isCredit
                ? ($transaction['credit'] ?? 0)
                : ($transaction['debit'] ?? 0);

            ?>

            <article class="transaction-feed__item">

                <div class="transaction-feed__indicator">

                    <span class="transaction-feed__dot <?= $isCredit
                                                            ? 'transaction-feed__dot--credit'
                                                            : 'transaction-feed__dot--debit'; ?>">
                    </span>

                </div>

                <div class="transaction-feed__body">

                    <div class="transaction-feed__header">

                        <h4 class="transaction-feed__title">

                            <?= htmlspecialchars(
                                $transaction['particulars'] ?: 'Transaction'
                            ); ?>

                        </h4>

                        <span class="<?= $isCredit
                                            ? 'transaction-feed__value transaction-feed__value--credit'
                                            : 'transaction-feed__value transaction-feed__value--debit'; ?>">

                            <?= $isCredit ? '+' : '-'; ?>

                            ₱<?= number_format($amount, 2); ?>

                        </span>

                    </div>

                    <div class="transaction-feed__meta">

                        <?= ucwords(str_replace('_', ' ', $transaction['entry_type'])); ?>

                        •

                        <?= htmlspecialchars($transaction['reference_number']); ?>

                    </div>

                    <div class="transaction-feed__date">

                        <?= display_value($transaction['transaction_date']); ?>

                    </div>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif;

$body = ob_get_clean();

c('card', [

    'title' => 'Recent Activity',

    'subtitle' => 'Latest approved financial activity',

    'body' => $body

]);
