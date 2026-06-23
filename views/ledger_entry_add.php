<?php
// views/ledger_entry_add.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: 0 auto;">
    <p><a href="index.php?route=ledger">← Back to Ledger Dashboard</a></p>

    <h2>Post Journal Voucher Entry</h2>
    <p>Record a new asset transaction flow or manual deduction allocation line into the core ledger balances.</p>
    <hr>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #fee; padding: 8px; border: 1px solid red; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php?route=add_ledger_entry" method="POST" style="background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 6px;">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Target Cooperative Member:</label>
            <select name="member_id" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;">
                <option value="">-- Choose a member profile --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?php echo $m['id']; ?>">
                        <?php echo htmlspecialchars($m['last_name'] . ', ' . $m['first_name'] . ' (' . $m['member_number'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Reference Number (Voucher/OR No.):</label>
            <input type="text" name="reference_number" placeholder="e.g., o 79386, v 21109" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Transaction Date:</label>
            <input type="date" name="transaction_date" value="<?php echo date('Y-m-d'); ?>" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Particulars / Description Details:</label>
            <input type="text" name="particulars" placeholder="e.g., Share Capital Deposit, MRS Contribution" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Ledger Entry Category:</label>
            <select name="entry_type" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;">
                <option value="">-- Select Accounting Allocation Type --</option>
                <option value="deposit">Share Capital Deposit (+ Credit Increase)</option>
                <option value="dividend">Dividend Credited (+ Credit Increase)</option>
                <option value="withdrawal">Share Capital Withdrawal (- Debit Decrease)</option>
                <option value="mrs_deduction">MRS (Aboluyan Contribution) (- Debit Decrease)</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Transaction Amount ($):</label>
            <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; background: #5cb85c; color: white; padding: 10px; font-weight: bold; border: none; border-radius: 4px; cursor: pointer; font-size: 1em;">
            Post Entry to Ledger Registry
        </button>
    </form>
</div>