<?php
// views/member.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p><a href="index.php?route=dashboard">← Back to Dashboard</a></p>
    </div>

    <h2>Cooperative Official Members Registry</h2>
    <p>This workspace tracks actual cooperative shareholders, registration logs, and capital statements.</p>

    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
        <p><a href="index.php?route=add_member" style="background: #5cb85c; color: white; padding: 8px 12px; text-decoration: none; font-weight: bold; border-radius: 4px;">+ Register New Cooperative Member</a></p>
    <?php else: ?>
        <p style="color: #777; font-style: italic; background: #eee; padding: 8px; display: inline-block; border-radius: 4px;">🔒 Account Tier Mode: Staff View (Read-Only)</p>
    <?php endif; ?>
    <br><br>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php" method="GET" style="margin-bottom: 20px; background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 4px; display: flex; gap: 10px; align-items: center;">
        <input type="hidden" name="route" value="members">

        <label for="search" style="font-weight: bold;">Search Member:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Enter name or member number..." style="padding: 6px 12px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">

        <button type="submit" style="background: #337ab7; color: white; border: none; padding: 6px 15px; font-weight: bold; border-radius: 4px; cursor: pointer;">Filter</button>

        <?php if (!empty($_GET['search'])): ?>
            <a href="index.php?route=members" style="color: #666; font-size: 0.9em; text-decoration: none; margin-left: 5px;">Clear Filter</a>
        <?php endif; ?>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Member No.</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Share Capital</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($coop_members)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No members found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($coop_members as $m): ?>
                    <tr>
                        <td><code><?php echo htmlspecialchars($m['member_number']); ?></code></td>
                        <td>
                            <a href="index.php?route=member_profile&id=<?php echo $m['id']; ?>" style="text-decoration: none; color: #337ab7; font-weight: bold;">
                                <?php echo htmlspecialchars($m['last_name'] . ', ' . $m['first_name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($m['email'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($m['phone'] ?: 'N/A'); ?></td>
                        <td><strong>$<?php echo number_format($m['share_capital'] ?? 0, 2); ?></strong></td>
                        <td>
                            <span style="color: <?php echo ($m['status'] === 'active') ? 'green' : 'gray'; ?>; font-weight: bold;">
                                <?php echo htmlspecialchars(strtoupper($m['status'] ?? 'UNKNOWN')); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>