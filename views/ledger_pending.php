<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access prohibited.');
}
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Pending Journal Voucher Approvals</h2>
    <p>Review and approve pending manual ledger entries before they affect member balances.</p>

    <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th>Date</th>
                <th>Ref No.</th>
                <th>Member</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pending_vouchers as $v): ?>
                <tr>
                    <td><?php echo htmlspecialchars($v['transaction_date']); ?></td>
                    <td><?php echo htmlspecialchars($v['reference_number']); ?></td>
                    <td><?php echo htmlspecialchars($v['last_name'] . ', ' . $v['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($v['entry_type']); ?></td>
                    <td>$<?php echo number_format($v['credit'] + $v['debit'], 2); ?></td>
                    <td>
                        <form action="index.php?route=approve_ledger_entry" method="POST">
                            <input type="hidden" name="voucher_id" value="<?php echo $v['id']; ?>">
                            <button type="submit" style="background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer;">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>