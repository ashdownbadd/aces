<?php
// views/loan_view.php

/**
 * @var array $loanData  Injected context parameter from AmortizationController
 * @var array $rows      Injected context parameter from AmortizationController
 * @var array $ledger    Injected context parameter from AmortizationController
 */

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

// Recalculate immediate cumulative running dashboard sums
$totalRemPrincipal = 0;
$totalRemInterest  = 0;
$totalRemPenalty   = 0;

foreach ($rows as $r) {
    if ($r['status'] !== 'paid') {
        $totalRemPrincipal += floatval($r['rem_principal']);
        $totalRemInterest  += floatval($r['rem_interest']);
        $totalRemPenalty   += floatval($r['rem_penalty']);
    }
}
$grandTotalDue = $totalRemPrincipal + $totalRemInterest + $totalRemPenalty;
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <p><a href="index.php?route=amortization_dashboard">← Back to Loans Dashboard</a></p>

    <h2>Statement of Loan Account (SOA Ledger)</h2>
    <p>
        <a href="index.php?route=print_soa&id=<?php echo $loanData['id']; ?>" target="_blank" style="background: #337ab7; color: white; padding: 6px 12px; text-decoration: none; font-weight: bold; border-radius: 4px; font-size:0.9em;">
            🖨️ Open Print-Ready Layout / Export PDF
        </a>
    </p>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; background: #f9f9f9; padding: 15px; border: 1px solid #ccc; border-radius: 6px;">
            <h3 style="margin-top: 0; color: #2c3e50;">Borrower Core Profile</h3>
            <p style="margin: 5px 0;"><strong>Account Name:</strong> <?php echo htmlspecialchars($loanData['last_name'] . ', ' . $loanData['first_name']); ?></p>
            <p style="margin: 5px 0;"><strong>Member No:</strong> <code><?php echo htmlspecialchars($loanData['member_number']); ?></code></p>
            <p style="margin: 5px 0;"><strong>Loan Class:</strong> <?php echo htmlspecialchars($loanData['loan_type']); ?></p>
            <p style="margin: 5px 0;"><strong>Collateral Asset Type:</strong> <?php echo htmlspecialchars($loanData['collateral']); ?></p>
        </div>

        <div style="flex: 1; background: #fff8f8; padding: 15px; border: 1px solid #ebccd1; border-radius: 6px;">
            <h3 style="margin-top: 0; color: #a94442;">Real-Time Account Balance</h3>
            <p style="margin: 5px 0;">Total Unpaid Principal: <strong>₱<?php echo number_format($totalRemPrincipal, 2); ?></strong></p>
            <p style="margin: 5px 0;">Total Unpaid Interest: <strong>₱<?php echo number_format($totalRemInterest, 2); ?></strong></p>
            <p style="margin: 5px 0;">Accumulated Late Penalties: <strong>₱<?php echo number_format($totalRemPenalty, 2); ?></strong></p>
            <hr>
            <h2 style="margin: 5px 0; color: #c9302c;">Grand Aggregate Total: ₱<?php echo number_format($grandTotalDue, 2); ?></h2>

            <form action="index.php?route=apply_loan_payment" method="POST" style="margin-top: 15px;">
                <input type="hidden" name="loan_id" value="<?php echo $loanData['id']; ?>">
                <input type="number" name="amount_paid" step="0.01" required placeholder="Enter Payment Amount" style="width:100%; padding:8px; box-sizing:border-box;">
                <input type="text" name="remarks" placeholder="Reference Remarks" style="width:100%; padding:8px; margin-top:5px; box-sizing:border-box;">
                <button type="submit" style="width:100%; margin-top:10px; background:#c9302c; color:white; border:none; padding:8px; font-weight:bold; cursor:pointer;">
                    Execute Global Waterfall Distribution Pass
                </button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green;"><?php echo $_SESSION['success_message'];
                                                                                                                unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <h3>Chronological Period-by-Period Amortization Tracking Matrix</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Period</th>
                <th>Due Date</th>
                <th>Principal (Orig / Rem)</th>
                <th>Interest (Orig / Rem)</th>
                <th>Penalty</th>
                <th>Status</th>
                <th>Adjustment Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr style="<?php echo ($r['status'] === 'paid') ? 'background-color: #f2f9f2;' : (($r['status'] === 'overdue') ? 'background-color: #fff2f2;' : ''); ?>">
                    <td>P-<?php echo $r['period']; ?></td>
                    <td><code><?php echo $r['due_date']; ?></code></td>
                    <td>₱<?php echo number_format($r['principal'], 2); ?> / ₱<?php echo number_format($r['rem_principal'], 2); ?></td>
                    <td>₱<?php echo number_format($r['interest'], 2); ?> / ₱<?php echo number_format($r['rem_interest'], 2); ?></td>
                    <td>₱<?php echo number_format($r['rem_penalty'], 2); ?></td>
                    <td><?php echo strtoupper($r['status']); ?></td>
                    <td style="background:#fffdf0;">
                        <form action="index.php?route=edit_schedule_period" method="POST">
                            <input type="hidden" name="schedule_id" value="<?php echo $r['id']; ?>">
                            <input type="hidden" name="loan_id" value="<?php echo $loanData['id']; ?>">
                            <input type="date" name="due_date" value="<?php echo $r['due_date']; ?>" style="width:100px;">
                            <input type="number" step="0.01" name="penalty" value="<?php echo $r['rem_penalty']; ?>" style="width:60px;">
                            <input type="text" name="remarks" placeholder="Notes..." style="width:80px;">
                            <button type="submit" style="background:#f39c12; color:white; border:none; padding:2px 5px;">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Collection Payment Log Registry</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Timestamp</th>
                <th>Collected</th>
                <th>Penalty</th>
                <th>Interest</th>
                <th>Principal</th>
                <th>Excess</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ledger as $log): ?>
                <tr>
                    <td><code><?php echo $log['datetime']; ?></code></td>
                    <td>₱<?php echo number_format($log['amount_paid'], 2); ?></td>
                    <td>₱<?php echo number_format($log['penalty_applied'], 2); ?></td>
                    <td>₱<?php echo number_format($log['interest_applied'], 2); ?></td>
                    <td>₱<?php echo number_format($log['principal_applied'], 2); ?></td>
                    <td>₱<?php echo number_format($log['excess'], 2); ?></td>
                    <td><?php echo htmlspecialchars($log['remarks']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>