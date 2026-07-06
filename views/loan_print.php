<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

/*
|--------------------------------------------------------------------------
| Remaining Totals
|--------------------------------------------------------------------------
*/

$totalRemainingPrincipal = 0;
$totalRemainingInterest  = 0;
$totalRemainingPenalty   = 0;

foreach ($rows as $row) {

    if ($row['status'] === 'paid') {
        continue;
    }

    $totalRemainingPrincipal += (float) $row['rem_principal'];
    $totalRemainingInterest  += (float) $row['rem_interest'];
    $totalRemainingPenalty   += (float) $row['rem_penalty'];
}

$grandTotal =
    $totalRemainingPrincipal +
    $totalRemainingInterest +
    $totalRemainingPenalty;

?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>

        SOA_<?= $loanData['id'] ?>_<?= htmlspecialchars($loanData['last_name']) ?>

    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {

            margin: 32px;

            font-family: Arial, sans-serif;

            color: #222;

            line-height: 1.45;

        }

        h1,
        h2,
        h3,
        h4 {

            margin: 0;

        }

        .header {

            margin-bottom: 28px;

            padding-bottom: 18px;

            border-bottom: 3px solid #1f2937;

            text-align: center;

        }

        .header h2 {

            font-size: 24px;

        }

        .header h3 {

            margin-top: 8px;

            font-size: 18px;

            font-weight: 600;

        }

        .header p {

            margin-top: 8px;

            color: #666;

        }

        .no-print {

            display: flex;

            align-items: center;

            gap: 16px;

            margin-bottom: 24px;

            padding: 14px;

            border: 1px solid #d7d7d7;

            background: #fffde8;

        }

        .no-print button {

            padding: 10px 18px;

            border: none;

            border-radius: 4px;

            background: #1f2937;

            color: #fff;

            cursor: pointer;

            font-weight: bold;

        }

        .no-print a {

            color: #1f2937;

            text-decoration: none;

            font-weight: 600;

        }

        .summary {

            display: flex;

            justify-content: space-between;

            gap: 40px;

            margin-bottom: 28px;

        }

        .summary>div {

            flex: 1;

        }

        .summary p {

            margin: .4rem 0;

        }

        .section-title {

            margin-top: 28px;

            padding: 10px 14px;

            border: 1px solid #ccc;

            background: #f5f5f5;

            font-weight: bold;

        }

        table {

            width: 100%;

            margin-top: 12px;

            border-collapse: collapse;

            font-size: 13px;

        }

        th {

            background: #ececec;

        }

        th,
        td {

            padding: 8px;

            border: 1px solid #bbb;

        }

        .text-right {

            text-align: right;

        }

        .status-paid {

            background: #edfdf0;

        }

        .status-overdue {

            background: #fff0f0;

        }

        @media print {

            .no-print {

                display: none;

            }

            body {

                margin: 10px;

            }

        }
    </style>

</head>

<body>

    <div class="no-print">

        <button onclick="window.print()">

            Print / Save PDF

        </button>

        <a href="index.php?route=view_loan&id=<?= (int)$loanData['id'] ?>">

            Return to Loan Account

        </a>

    </div>

    <div class="header">

        <h2>

            COOPERATIVE CREDIT MANAGEMENT SYSTEM

        </h2>

        <h3>

            Statement of Loan Account

        </h3>

        <p>

            Generated

            <?= date('Y-m-d H:i:s') ?>

        </p>

    </div>

    <div class="summary">

        <div>

            <p>

                <strong>Borrower</strong><br>

                <?= htmlspecialchars($loanData['last_name']) ?>,

                <?= htmlspecialchars($loanData['first_name']) ?>

            </p>

            <p>

                <strong>Member No.</strong><br>

                <?= htmlspecialchars($loanData['member_number']) ?>

            </p>

            <p>

                <strong>Status</strong><br>

                <?= strtoupper(htmlspecialchars($loanData['soa_status'])) ?>

            </p>

        </div>

        <div>

            <p>

                <strong>Loan Type</strong><br>

                <?= htmlspecialchars($loanData['loan_type']) ?>

            </p>

            <p>

                <strong>Principal</strong><br>

                ₱<?= number_format($loanData['principal'], 2) ?>

            </p>

            <p>

                <strong>Total Outstanding</strong><br>

                <strong>

                    ₱<?= number_format($grandTotal, 2) ?>

                </strong>

            </p>

        </div>

    </div>

    <div class="section-title">

        AMORTIZATION SCHEDULE

    </div>

    <table>

        <thead>

            <tr>

                <th>Period</th>

                <th>Due Date</th>

                <th>Principal</th>

                <th>Interest</th>

                <th>Penalty</th>

                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($rows as $row): ?>

                <tr class="<?=
                            $row['status'] === 'paid'
                                ? 'status-paid'
                                : (
                                    $row['status'] === 'overdue'
                                    ? 'status-overdue'
                                    : ''
                                )
                            ?>">

                    <td>

                        <?= (int) $row['period'] ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['due_date']) ?>

                    </td>

                    <td class="text-right">

                        ₱<?= number_format($row['principal'], 2) ?>

                        <br>

                        <small>

                            Remaining:
                            ₱<?= number_format($row['rem_principal'], 2) ?>

                        </small>

                    </td>

                    <td class="text-right">

                        ₱<?= number_format($row['interest'], 2) ?>

                        <br>

                        <small>

                            Remaining:
                            ₱<?= number_format($row['rem_interest'], 2) ?>

                        </small>

                    </td>

                    <td class="text-right">

                        ₱<?= number_format($row['orig_penalty'], 2) ?>

                        <br>

                        <small>

                            Remaining:
                            ₱<?= number_format($row['rem_penalty'], 2) ?>

                        </small>

                    </td>

                    <td>

                        <strong>

                            <?= strtoupper(htmlspecialchars($row['status'])) ?>

                        </strong>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <div class="section-title">

        PAYMENT HISTORY

    </div>

    <table>

        <thead>

            <tr>

                <th>Date / Time</th>

                <th>Amount</th>

                <th>Penalty</th>

                <th>Interest</th>

                <th>Principal</th>

                <th>Excess</th>

            </tr>

        </thead>

        <tbody>

            <?php if (empty($ledger)): ?>

                <tr>

                    <td colspan="6" style="text-align:center; color:#777;">

                        No payment records available.

                    </td>

                </tr>

            <?php else: ?>

                <?php foreach ($ledger as $log): ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($log['datetime']) ?>

                        </td>

                        <td class="text-right">

                            <strong>

                                ₱<?= number_format($log['amount_paid'], 2) ?>

                            </strong>

                        </td>

                        <td class="text-right">

                            ₱<?= number_format($log['penalty_applied'], 2) ?>

                        </td>

                        <td class="text-right">

                            ₱<?= number_format($log['interest_applied'], 2) ?>

                        </td>

                        <td class="text-right">

                            ₱<?= number_format($log['principal_applied'], 2) ?>

                        </td>

                        <td class="text-right">

                            ₱<?= number_format($log['excess'], 2) ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>

    </table>

</body>

</html>