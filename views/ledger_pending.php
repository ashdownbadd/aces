<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access prohibited.');
}


// Assume $admin = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
$isAdmin = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);

/** * @var array $member  The array containing core profile data for the targeted cooperative member

 * @var array $pending_vouchers The array containing all pending journal voucher entries awaiting approval
 */
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
                <th>Status</th> <?php if ($isAdmin): ?>
                    <th>Action</th>
                <?php endif; ?>
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
                        <span style="background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold;">PENDING</span>
                    </td>

                    <?php if ($isAdmin): ?>
                        <td>
                            <form action="index.php?route=approve_ledger_entry" method="POST">
                                <input type="hidden" name="voucher_id" value="<?php echo htmlspecialchars($v['id']); ?>">
                                <button type="submit" style="background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;">Approve</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>