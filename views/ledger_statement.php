<?php
// views/ledger_statement.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/** * @var array $member  The array containing core profile data for the targeted cooperative member
 * @var array $member
 * @var array $history The historical array holding all associated journal voucher transactions
 */
?>

<style>
    /* Professional Document Styling */
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            font-family: 'Times New Roman', serif;
            color: #000;
        }

        .container {
            border: none;
        }

        h2 {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
    }
</style>

<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div class="no-print">
        <p><a href="index.php?route=ledger">← Back to Dashboard</a></p>
    </div>


    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #337ab7; color: white; border: none; border-radius: 4px;">
            Print Official Statement
        </button>
    </div>


        <h2>Statement of Account Ledger</h2>
        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 30px;">
            <h3 style="margin: 0 0 10px 0; color: #333;">Member Profile Details</h3>
            <p style="margin: 4px 0;"><strong>Name:</strong> <?php echo htmlspecialchars(($member['last_name'] ?? '') . ', ' . ($member['first_name'] ?? '')); ?></p>
            <p style="margin: 4px 0;"><strong>Member Number:</strong> <code><?php echo htmlspecialchars($member['member_number'] ?? ''); ?></code></p>
            <p style="margin: 4px 0;"><strong>Status:</strong> <span style="font-weight:bold; color: green;"><?php echo strtoupper($member['status'] ?? 'ACTIVE'); ?></span></p>
        </div>

        <form action="index.php" method="GET" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 4px; border: 1px solid #eee; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="route" value="ledger_statement">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($member['id']); ?>">

            <label for="start_date" style="font-weight: bold;">From Date:</label>
            <input type="date" id="start_date" name="start_date"
                value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>"
                style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">

            <label for="end_date" style="font-weight: bold;">To Date:</label>
            <input type="date" id="end_date" name="end_date"
                value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>"
                style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">

            <button type="submit" style="padding: 5px 15px; background: #337ab7; color: white; border: none; border-radius: 4px; cursor: pointer;">Search / Filter</button>
            <a href="index.php?route=ledger_statement&id=<?php echo htmlspecialchars($member['id']); ?>"
                style="margin-left: 10px; color: #666; text-decoration: none; font-size: 0.9em;">Clear Filters</a>
        </form>

        <h3>Transaction Ledger Timeline History</h3>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Date</th>
                    <th>Reference No.</th>
                    <th>Particulars / Description</th>
                    <th>Type Allocation</th>
                    <th style="color: red; text-align: right;">Debit (-)</th>
                    <th style="color: green; text-align: right;">Credit (+)</th>
                    <th style="text-align: right; background: #eaeaea;">Running Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #777;">No financial accounting transaction sequences found matching your criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php
                    $running_balance = 0.00;
                    foreach ($history as $tx):
                        $running_balance += (float)($tx['credit'] ?? 0) - (float)($tx['debit'] ?? 0);
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tx['transaction_date'] ?? ''); ?></td>
                            <td><code><?php echo htmlspecialchars($tx['reference_number'] ?? ''); ?></code></td>
                            <td><?php echo htmlspecialchars($tx['particulars'] ?? ''); ?></td>
                            <td><span style="background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $tx['entry_type'] ?? ''))); ?></span></td>
                            <td style="text-align: right; color: red;"><?php echo (isset($tx['debit']) && $tx['debit'] > 0) ? '$' . number_format($tx['debit'], 2) : '-'; ?></td>
                            <td style="text-align: right; color: green;"><?php echo (isset($tx['credit']) && $tx['credit'] > 0) ? '$' . number_format($tx['credit'], 2) : '-'; ?></td>
                            <td style="text-align: right; font-weight: bold; background: #fdfdfd;"><?php echo '$' . number_format($running_balance, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>