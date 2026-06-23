<?php
// views/loan_print.php
if (!defined('ALLOW_ACCESS')) { die('Direct access prohibited.'); }

$tPr = 0; $tInt = 0; $tPen = 0;
foreach ($rows as $r) {
    if ($r['status'] !== 'paid') {
        $tPr += floatval($r['rem_principal']);
        $tInt += floatval($r['rem_interest']);
        $tPen += floatval($r['rem_penalty']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOA_<?php echo $loanData['id'] . '_' . $loanData['last_name']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 30px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; }
        .section-title { background: #f2f2f2; padding: 6px; font-weight: bold; margin-top: 20px; border: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9em; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background: #eaedd1; }
        .text-right { text-align: right; }
        @media print {
            .no-print { display: none; }
            body { margin: 10px; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; background: #fffcc4; padding: 10px; border: 1px solid #d6cc11; border-radius: 4px;">
        <button onclick="window.print();" style="background:#2c3e50; color:white; padding:8px 16px; border:none; font-weight:bold; cursor:pointer; border-radius:4px;">Print / Save to PDF Document 📄</button>
        <a href="index.php?route=view_loan&id=<?php echo $loanData['id']; ?>" style="margin-left:15px; color:#333;">Return to Account Statement</a>
    </div>

    <div class="header">
        <h2>COOPERATIVE CREDIT MANAGEMENT SYSTEM</h2>
        <h3>Official Statement of Loan Account Ledger</h3>
        <p>Generated Timestamp: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <strong>Borrower Name:</strong> <?php echo htmlspecialchars($loanData['last_name'] . ', ' . $loanData['first_name']); ?><br>
            <strong>Member Number:</strong> <?php echo htmlspecialchars($loanData['member_number']); ?><br>
            <strong>Account Status:</strong> <code><?php echo strtoupper($loanData['soa_status']); ?></code>
        </div>
        <div>
            <strong>Loan Option:</strong> <?php echo htmlspecialchars($loanData['loan_type']); ?><br>
            <strong>Principal Disbursed:</strong> ₱<?php echo number_format($loanData['principal'], 2); ?><br>
            <strong>Total Outstanding Due:</strong> <strong>₱<?php echo number_format($tPr + $tInt + $tPen, 2); ?></strong>
        </div>
    </div>

    <div class="section-title">CHRONOLOGICAL AMORTIZATION MATRICES SCHEDULE</div>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Due Date</th>
                <th>Principal (Orig/Rem)</th>
                <th>Interest (Orig/Rem)</th>
                <th>Late Penalty (Orig/Rem)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr style="<?php echo $r['status'] === 'paid' ? 'background-color:#e6ffe6;' : ($r['status'] === 'overdue' ? 'background-color:#ffe6e6;' : ''); ?>">
                    <td><?php echo $r['period']; ?></td>
                    <td><?php echo $r['due_date']; ?></td>
                    <td>₱<?php echo number_format($r['principal'], 2); ?> / ₱<?php echo number_format($r['rem_principal'], 2); ?></td>
                    <td>₱<?php echo number_format($r['interest'], 2); ?> / ₱<?php echo number_format($r['rem_interest'], 2); ?></td>
                    <td>₱<?php echo number_format($r['orig_penalty'], 2); ?> / ₱<?php echo number_format($r['rem_penalty'], 2); ?></td>
                    <td><strong><?php echo strtoupper($r['status']); ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="section-title">HISTORICAL COLLECTION RECORDS LOG REGISTRY</div>
    <table>
        <thead>
            <tr>
                <th>Datetime Tag</th>
                <th>Amount Collected</th>
                <th>Penalty Cleared</th>
                <th>Interest Cleared</th>
                <th>Principal Cleared</th>
                <th>Excess</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ledger)): ?>
                <tr><td colspan="6" style="text-align:center; color:#777;">No financial cash logs processed against this profile yet.</td></tr>
            <?php else: ?>
                <?php foreach ($ledger as $log): ?>
                    <tr>
                        <td><code><?php echo $log['datetime']; ?></code></td>
                        <td><strong>₱<?php echo number_format($log['amount_paid'], 2); ?></strong></td>
                        <td>₱<?php echo number_format($log['penalty_applied'], 2); ?></td>
                        <td>₱<?php echo number_format($log['interest_applied'], 2); ?></td>
                        <td>₱<?php echo number_format($log['principal_applied'], 2); ?></td>
                        <td>₱<?php echo number_format($log['excess'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>