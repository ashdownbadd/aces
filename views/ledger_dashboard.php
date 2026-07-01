<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/** * @var array $member  The array containing core profile data for the targeted cooperative member
 * @var int $total_coop_equity The total cooperative equity value
 * @var int $pending_count The number of pending approvals
 */

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

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; flex: 1; border: 1px solid #b3d7ff;">
            <h3 style="margin-top: 0; color: #0056b3;">Total Cooperative Equity</h3>
            <p style="font-size: 1.8em; font-weight: bold; margin: 5px 0;">$<?php echo number_format($total_coop_equity, 2); ?></p>
        </div>
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; flex: 1; border: 1px solid #ffeeba;">
            <h3 style="margin-top: 0; color: #856404;">Pending Approvals</h3>
            <p style="font-size: 1.8em; font-weight: bold; margin: 5px 0;"><?php echo $pending_count; ?></p>
            <a href="index.php?route=pending_approvals" style="color: #856404; font-weight: bold;">View Queue →</a>
        </div>
    </div>

    <form action="index.php" method="GET" style="margin-bottom: 20px; background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 4px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <input type="hidden" name="route" value="ledger">

        <label for="search" style="font-weight: bold;">Search:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Name or member no..." style="padding: 6px; width: 200px; border: 1px solid #ccc; border-radius: 4px;">

        <label for="start_date" style="font-weight: bold; margin-left: 10px;">From Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">

        <label for="end_date" style="font-weight: bold;">To Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px;">

        <button type="submit" style="background: #337ab7; color: white; border: none; padding: 6px 15px; font-weight: bold; border-radius: 4px; cursor: pointer;">Filter</button>
        <a href="index.php?route=ledger" style="color: #666; font-size: 0.9em; text-decoration: none; margin-left: 5px;">Clear All</a>
    </form>

    <p style="display: flex; gap: 10px; align-items: center; margin-bottom: 25px;">
        <a href="index.php?route=add_ledger_entry" style="background: #337ab7; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px;">+ Post New Journal Voucher Entry</a>
        <a href="index.php?route=pending_approvals" style="background: #f0ad4e; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px;">⚠️ View Pending Approvals Verification Queue</a>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($member_summaries)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No members found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($member_summaries as $ms): ?>
                    <tr>
                        <td><code><?php echo htmlspecialchars($ms['member_number']); ?></code></td>
                        <td><?php echo htmlspecialchars($ms['last_name'] . ', ' . $ms['first_name']); ?></td>
                        <td style="color: green;">$<?php echo number_format($ms['total_credits'], 2); ?></td>
                        <td style="color: #c9302c;">$<?php echo number_format($ms['total_debits'], 2); ?></td>
                        <td>
                            <strong style="color: <?php echo ($ms['current_balance'] >= 0) ? 'green' : 'red'; ?>;">
                                $<?php echo number_format($ms['current_balance'], 2); ?>
                            </strong>
                        </td>
                        <td>
                            <a href="index.php?route=ledger_statement&id=<?php echo $ms['member_id']; ?>" style="background: #17a2b8; color: white; padding: 4px 8px; text-decoration: none; font-size: 0.9em; border-radius: 3px;">View Statement</a>
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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($vouchers)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">No transaction vouchers recorded.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($vouchers as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['transaction_date']); ?></td>
                        <td><code><?php echo htmlspecialchars($v['reference_number']); ?></code></td>
                        <td><?php echo htmlspecialchars($v['particulars']); ?></td>
                        <td><span style="background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;"><?php echo htmlspecialchars($v['operator_name'] ?? 'System'); ?></span></td>
                        <td>
                            <?php if (($v['status'] ?? '') === 'approved'): ?>
                                <span style="background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;">APPROVED</span>
                            <?php elseif (($v['status'] ?? '') === 'pending'): ?>
                                <span style="background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;">PENDING</span>
                            <?php else: ?>
                                <span style="background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;"><?php echo strtoupper($v['status'] ?? 'UNKNOWN'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>