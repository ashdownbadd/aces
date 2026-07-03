<?php
// views/amortization_dashboard.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p><a href="index.php?route=dashboard">← Back to Main Control Room</a></p>
    </div>

    <h2>Amortization Calculators Portfolio Suite</h2>
    <p>Formulate multi-frequency repayment matrices, trigger late penalty calculations, and monitor executive credit summaries.</p>
    <hr>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #f9f2f2; padding: 8px; border: 1px solid red; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <div style="display: flex; gap: 15px; margin-bottom: 25px; margin-top:15px;">
        <div style="flex: 1; background: #2c3e50; color: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <small style="text-transform: uppercase; font-size: 0.75em; opacity: 0.8;">Total Capital Disbursed</small>
            <h2 style="margin: 5px 0 0 0;">₱<?php echo number_format($totalDisbursed, 2); ?></h2>
        </div>
        <div style="flex: 1; background: #18bc9c; color: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <small style="text-transform: uppercase; font-size: 0.75em; opacity: 0.8;">Projected Interest Income</small>
            <h2 style="margin: 5px 0 0 0;">₱<?php echo number_format($projectedRevenue, 2); ?></h2>
        </div>
        <div style="flex: 1; background: #3498db; color: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <small style="text-transform: uppercase; font-size: 0.75em; opacity: 0.8;">Collected Cash to Date</small>
            <h2 style="margin: 5px 0 0 0;">₱<?php echo number_format($collectedToDate, 2); ?></h2>
        </div>
        <div style="flex: 1; background: #e74c3c; color: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <small style="text-transform: uppercase; font-size: 0.75em; opacity: 0.8;">Portfolio at Risk (PAR)</small>
            <h2 style="margin: 5px 0 0 0;">₱<?php echo number_format($portfolioAtRisk, 2); ?></h2>
        </div>
    </div>

    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 25px;">
        <a href="index.php?route=create_loan" style="background: #337ab7; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px; display:inline-block;">
            + Generate New Amortization Loan Account
        </a>

        <?php if (isset($_SESSION['role_id']) && intval($_SESSION['role_id']) === 1): ?>
            <a href="index.php?route=pending_loans_queue" style="background: #f0ad4e; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px; display:inline-block;">
                ⚠️ View Pending Loans Verification Queue
            </a>
        <?php endif; ?>
    </div>
    <br>

    <h3>Active Credit Accounts Portfolio Directory</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background: #f2f2f2; border-bottom: 2px solid #ddd;">
                <th style="padding: 10px; text-align: left;">Borrower Account</th>
                <th style="padding: 10px; text-align: left;">Loan Class Type</th>
                <th style="padding: 10px; text-align: left;">Amortization Setup Matrix</th>
                <th style="padding: 10px; text-align: left;">Principal</th>
                <th style="padding: 10px; text-align: left;">Life Timeline</th>
                <th style="padding: 10px; text-align: left;">Account Status</th>
                <th style="padding: 10px; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($loans)): ?>
                <tr>
                    <td colspan="7" style="padding: 15px; text-align: center; color: #777;">No active loan ledger registrations found inside database rows.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($loans as $l): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">
                            <strong><?php echo htmlspecialchars($l['last_name'] . ', ' . $l['first_name']); ?></strong><br>
                            <small style="color:#666;">(<?php echo htmlspecialchars($l['member_number']); ?>)</small>
                        </td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($l['loan_type']); ?></td>
                        <td style="padding: 10px;">
                            <span style="background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;">
                                <?php echo htmlspecialchars($l['amortization_type']); ?>
                                <?php if ($l['loan_type'] === 'Micro-Finance Loan') echo ' (' . $l['payment_frequency'] . ')'; ?>
                            </span>
                        </td>
                        <td style="padding: 10px;"><strong>₱<?php echo number_format($l['principal'], 2); ?></strong></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($l['terms']); ?> Months</td>
                        <td style="padding: 10px;">
                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 0.85em; font-weight: bold; background: <?php echo $l['soa_status'] === 'Fully Paid' ? '#d4edda; color:#155724;' : '#fff3cd; color:#856404;'; ?>">
                                <?php echo htmlspecialchars($l['soa_status']); ?>
                            </span>
                        </td>
                        <td style="padding: 10px;">
                            <a href="index.php?route=view_loan&id=<?php echo $l['id']; ?>" style="background: #5cb85c; color: white; padding: 4px 8px; text-decoration: none; font-weight: bold; border-radius: 4px; font-size: 0.9em;">
                                View Account Statement 📊
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>