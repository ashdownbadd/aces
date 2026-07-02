<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container">
    <div class="header-actions">
        <p><a href="index.php?route=dashboard">← Back to Dashboard</a></p>
    </div>

    <h2>General Ledger Module</h2>
    <p>Track journal vouchers, share capital collections, dividends, and real-time member equity balances.</p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <div class="card card-blue" style="flex: 1;">
            <h3 style="color: #0056b3;">Total Cooperative Equity</h3>
            <p style="font-size: 1.8em; font-weight: bold; margin: 5px 0;">$<?php echo number_format($total_coop_equity, 2); ?></p>
        </div>
        <div class="card card-yellow" style="flex: 1;">
            <h3 style="color: #856404;">Pending Approvals</h3>
            <p style="font-size: 1.8em; font-weight: bold; margin: 5px 0;"><?php echo $pending_count; ?></p>
            <a href="index.php?route=pending_approvals" style="color: #856404; font-weight: bold;">View Queue →</a>
        </div>
    </div>

    <form action="index.php" method="GET" class="form-inline">
        <input type="hidden" name="route" value="ledger">

        <label for="search" class="form-label" style="margin-bottom:0;">Search:</label>
        <input type="text" id="search" name="search" class="form-control" style="width: 200px;" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Name or member no...">

        <label for="start_date" class="form-label" style="margin-bottom:0; margin-left: 10px;">From:</label>
        <input type="date" id="start_date" name="start_date" class="form-control" style="width:auto;" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">

        <label for="end_date" class="form-label" style="margin-bottom:0;">To:</label>
        <input type="date" id="end_date" name="end_date" class="form-control" style="width:auto;" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="index.php?route=ledger" style="color: #666; text-decoration: none; margin-left: 5px;">Clear All</a>
    </form>

    <div style="margin-bottom: 25px;">
        <a href="index.php?route=add_ledger_entry" class="btn btn-primary">+ Post New Journal Voucher Entry</a>
        <a href="index.php?route=pending_approvals" class="btn btn-warning">⚠️ View Pending Approvals Queue</a>
    </div>

    <h3>Member Share Capital Balances</h3>
    <table class="table" style="margin-bottom: 40px;">
        <thead>
            <tr>
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
                    <td colspan="6" style="text-align: center;">No members found.</td>
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
                            <a href="index.php?route=ledger_statement&id=<?php echo $ms['member_id']; ?>" class="btn btn-info">View Statement</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Recent Journal Vouchers Log</h3>
    <table class="table">
        <thead>
            <tr>
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
                    <td colspan="5" style="text-align: center;">No transaction vouchers recorded.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($vouchers as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['transaction_date']); ?></td>
                        <td><code><?php echo htmlspecialchars($v['reference_number']); ?></code></td>
                        <td><?php echo htmlspecialchars($v['particulars']); ?></td>
                        <td><span class="badge badge-light"><?php echo htmlspecialchars($v['operator_name'] ?? 'System'); ?></span></td>
                        <td>
                            <?php if (($v['status'] ?? '') === 'approved'): ?>
                                <span class="badge badge-approved">APPROVED</span>
                            <?php elseif (($v['status'] ?? '') === 'pending'): ?>
                                <span class="badge badge-pending">PENDING</span>
                            <?php else: ?>
                                <span class="badge badge-light"><?php echo strtoupper($v['status'] ?? 'UNKNOWN'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>