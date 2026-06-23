<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p><a href="index.php?route=dashboard">← Back to Dashboard</a></p>
    </div>

    <h2>General Ledger Module</h2>
    <p>Track journal vouchers, share capital collections, dividends, and real-time member equity balances.</p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php" method="GET" style="margin-bottom: 20px; background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 4px; display: flex; gap: 10px; align-items: center;">
        <input type="hidden" name="route" value="ledger">
        <label for="search" style="font-weight: bold;">Search Member:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Enter name or member number..." style="padding: 6px 12px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" style="background: #337ab7; color: white; border: none; padding: 6px 15px; font-weight: bold; border-radius: 4px; cursor: pointer;">Filter</button>
        <?php if (!empty($_GET['search'])): ?>
            <a href="index.php?route=ledger" style="color: #666; font-size: 0.9em; text-decoration: none; margin-left: 5px;">Clear</a>
        <?php endif; ?>
    </form>

    <p style="display: flex; gap: 10px; align-items: center; margin-bottom: 25px;">
        <a href="index.php?route=add_ledger_entry" style="background: #337ab7; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px;">
            + Post New Journal Voucher Entry
        </a>
        <a href="index.php?route=pending_approvals" style="background: #f0ad4e; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px;">
            ⚠️ View Pending Approvals Verification Queue
        </a>
    </p>

    <h3>Member Share Capital Balances</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 40px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Member No.</th>
                <th>Full Name</th>
                <th>Total Credits (+)</th>
                <th>Total Debits (-)</th>
                <th>Net Share Capital Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($member_summaries)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">No members found matching your search.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($member_summaries as $ms): ?>
                    <tr>
                        <td><code><?php echo htmlspecialchars($ms['member_number']); ?></code></td>
                        <td>
                            <a href="index.php?route=ledger_statement&id=<?php echo $ms['member_id']; ?>" style="color: #337ab7; font-weight: bold; text-decoration: none;">
                                <?php echo htmlspecialchars($ms['last_name'] . ', ' . $ms['first_name']); ?>
                            </a>
                        </td>
                        <td style="color: green;">$<?php echo number_format($ms['total_credits'], 2); ?></td>
                        <td style="color: #c9302c;">$<?php echo number_format($ms['total_debits'], 2); ?></td>
                        <td>
                            <strong style="color: <?php echo ($ms['current_balance'] >= 0) ? 'blue' : 'red'; ?>;">
                                $<?php echo number_format($ms['current_balance'], 2); ?>
                            </strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Recent Journal Vouchers Log</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Date</th>
                <th>Reference No.</th>
                <th>Particulars / Description</th>
                <th>Posted By</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($vouchers)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #777;">No transaction vouchers recorded in the ledger yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($vouchers as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['transaction_date']); ?></td>
                        <td><code><?php echo htmlspecialchars($v['reference_number']); ?></code></td>
                        <td><?php echo htmlspecialchars($v['particulars']); ?></td>
                        <td><span style="background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;"><?php echo htmlspecialchars($v['operator_name'] ?? 'System'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>