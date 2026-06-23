<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p><a href="index.php?route=amortization_dashboard">← Back to Amortization Dashboard</a></p>
    </div>

    <h2>Loan Applications Verification Queue</h2>
    <p>Review newly encoded loan profiles. Approving a profile activates the line item and commits initialization routines.</p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #f9f2f2; padding: 8px; border: 1px solid red; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Member Information</th>
                <th>Loan Type</th>
                <th>Principal Amount</th>
                <th>Terms & Interest</th>
                <th>Collateral Details</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pending_loans)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #777; padding: 20px;">
                        🎉 Excellent! The loan validation queue is completely empty.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pending_loans as $loan): ?>
                    <tr>
                        <td><code>#<?php echo $loan['id']; ?></code></td>
                        <td>
                            <strong><?php echo htmlspecialchars($loan['last_name'] . ', ' . $loan['first_name']); ?></strong><br>
                            <small style="color: #666;">Member No: <?php echo htmlspecialchars($loan['member_number']); ?></small>
                        </td>
                        <td><span style="background: #eef; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 0.9em;"><?php echo htmlspecialchars($loan['loan_type']); ?></span></td>
                        <td style="color: green; font-weight: bold;">$<?php echo number_format($loan['principal'], 2); ?></td>
                        <td>
                            <?php echo htmlspecialchars($loan['terms']); ?> Months @ <?php echo htmlspecialchars($loan['interest_rate']); ?>%<br>
                            <small style="color: #666;"><?php echo htmlspecialchars($loan['payment_frequency'] . ' / ' . $loan['amortization_type']); ?></small>
                        </td>
                        <td>
                            <strong>Type:</strong> <?php echo htmlspecialchars($loan['collateral']); ?><br>
                            <?php if (!empty($loan['tct_no'])): ?>
                                <small style="color: #555;">TCT: <?php echo htmlspecialchars($loan['tct_no']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <form action="index.php?route=process_loan_approval" method="POST" onsubmit="return confirm('Are you sure you want to approve and activate this loan?');">
                                    <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
                                    <input type="hidden" name="action" value="Approve">
                                    <button type="submit" style="background: #5cb85c; color: white; border: none; padding: 6px 12px; font-weight: bold; border-radius: 4px; cursor: pointer;">
                                        Approve
                                    </button>
                                </form>

                                <form action="index.php?route=process_loan_approval" method="POST" onsubmit="return confirm('Are you sure you want to reject this loan application?');">
                                    <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
                                    <input type="hidden" name="action" value="Reject">
                                    <button type="submit" style="background: #d9534f; color: white; border: none; padding: 6px 12px; font-weight: bold; border-radius: 4px; cursor: pointer;">
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