<?php
// controllers/LedgerController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * ==========================================================
 * Ledger Dashboard
 * ==========================================================
 */
function handleLedgerDashboard(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $search = trim($_GET['search'] ?? '');

    try {

        /*
        |--------------------------------------------------------------------------
        | KPI Data
        |--------------------------------------------------------------------------
        */

        $totalCoopEquity = (float) (
            $pdo
            ->query("
                    SELECT SUM(credit - debit)
                    FROM ledger_entries le
                    INNER JOIN journal_vouchers jv
                        ON le.voucher_id = jv.id
                    WHERE jv.status = 'approved'
                ")
            ->fetchColumn()
            ?: 0
        );

        $pendingApprovals = (int) (
            $pdo
            ->query("
                    SELECT COUNT(*)
                    FROM journal_vouchers
                    WHERE status = 'pending'
                ")
            ->fetchColumn()
        );

        /*
        |--------------------------------------------------------------------------
        | Member Summary
        |--------------------------------------------------------------------------
        */

        $sql = "
            SELECT

                m.id,
                m.member_number,
                m.first_name,
                m.last_name,

                COALESCE(
                    SUM(
                        CASE
                            WHEN le.entry_type IN ('deposit','dividend')
                            THEN le.credit
                            ELSE 0
                        END
                    ),
                    0
                ) AS total_credits,

                COALESCE(
                    SUM(
                        CASE
                            WHEN le.entry_type IN ('withdrawal','mrs_deduction')
                            THEN le.debit
                            ELSE 0
                        END
                    ),
                    0
                ) AS total_debits,

                (
                    COALESCE(
                        SUM(
                            CASE
                                WHEN le.entry_type IN ('deposit','dividend')
                                THEN le.credit
                                ELSE 0
                            END
                        ),
                        0
                    )

                    -

                    COALESCE(
                        SUM(
                            CASE
                                WHEN le.entry_type IN ('withdrawal','mrs_deduction')
                                THEN le.debit
                                ELSE 0
                            END
                        ),
                        0
                    )

                ) AS current_balance

            FROM members m

            LEFT JOIN ledger_entries le
                ON le.member_id = m.id

            LEFT JOIN journal_vouchers jv
                ON jv.id = le.voucher_id

            WHERE
                (jv.status = 'approved' OR jv.status IS NULL)
        ";

        $params = [];

        if ($search !== '') {

            $sql .= "
                AND
                (
                    m.first_name LIKE ?
                    OR m.last_name LIKE ?
                    OR m.member_number LIKE ?
                )
            ";

            $like = "%{$search}%";

            $params = [
                $like,
                $like,
                $like
            ];
        }

        $sql .= "
            GROUP BY
                m.id,
                m.member_number,
                m.first_name,
                m.last_name

            ORDER BY
                m.last_name ASC,
                m.first_name ASC
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute($params);

        $memberSummaries = $stmt->fetchAll();

        /*
        |--------------------------------------------------------------------------
        | Recent Voucher Activity
        |--------------------------------------------------------------------------
        */

        $start_date = trim($_GET['start_date'] ?? '');
        $end_date   = trim($_GET['end_date'] ?? '');

        $sql = "
            SELECT
                jv.*,
                u.username AS operator_name

            FROM journal_vouchers jv

            LEFT JOIN users u
                ON u.id = jv.created_by

            WHERE 1 = 1
        ";

        $voucherParams = [];

        if ($start_date !== '') {

            $sql .= " AND DATE(jv.transaction_date) >= ?";

            $voucherParams[] = $start_date;
        }

        if ($end_date !== '') {

            $sql .= " AND DATE(jv.transaction_date) <= ?";

            $voucherParams[] = $end_date;
        }

        $sql .= "
            ORDER BY
                jv.transaction_date DESC,
                jv.id DESC

            LIMIT 50
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute($voucherParams);

        $vouchers = $stmt->fetchAll();

        /*
        |--------------------------------------------------------------------------
        | KPI View Model
        |--------------------------------------------------------------------------
        */

        $kpis = [

            [

                'title'       => 'Total Equity',

                'value'       => '₱' . number_format($totalCoopEquity, 2),

                'subtitle'    => 'Approved Share Capital',

                'description' => 'Current cooperative equity',

                'icon'        => 'fas fa-coins',

                'color'       => 'primary',

                'url'         => url('ledger')

            ],

            [

                'title'       => 'Pending Vouchers',

                'value'       => $pendingApprovals,

                'subtitle'    => 'Awaiting Approval',

                'description' => 'Pending journal vouchers',

                'icon'        => 'fas fa-clock',

                'color'       => 'warning',

                'url'         => url('pending_approvals')

            ]

        ];

        /*
        |--------------------------------------------------------------------------
        | Member Table View Model
        |--------------------------------------------------------------------------
        */

        $memberTable = [

            'headers' => [

                'Member',

                'Credits',

                'Debits',

                'Balance',

                'Actions'

            ],

            'rows' => []

        ];

        foreach ($memberSummaries as $member) {

            $memberTable['rows'][] = [

                htmlspecialchars(
                    $member['last_name']
                        . ', '
                        . $member['first_name']
                ),

                '₱' . number_format((float)$member['total_credits'], 2),

                '₱' . number_format((float)$member['total_debits'], 2),

                '<strong>₱'
                    . number_format((float)$member['current_balance'], 2)
                    . '</strong>',

                '<a class="btn btn--primary" href="index.php?route=ledger_statement&id='
                    . (int)$member['id']
                    . '">View</a>'

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Voucher Table View Model
        |--------------------------------------------------------------------------
        */

        $voucherTable = [

            'headers' => [

                'Reference',

                'Date',

                'Operator',

                'Status'

            ],

            'rows' => []

        ];

        foreach ($vouchers as $voucher) {

            $voucherTable['rows'][] = [

                htmlspecialchars($voucher['reference_number']),

                htmlspecialchars($voucher['transaction_date']),

                htmlspecialchars($voucher['operator_name'] ?? 'System'),

                htmlspecialchars(ucfirst($voucher['status']))

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Render View
        |--------------------------------------------------------------------------
        */

        return render(
            'ledger_dashboard',
            [

                'kpis' => $kpis,

                'memberTable' => $memberTable,

                'voucherTable' => $voucherTable,

                /*
                |--------------------------------------------------------------------------
                | Temporary Compatibility
                |--------------------------------------------------------------------------
                | Keep these while we rewrite ledger_dashboard.php.
                | They can be removed afterwards.
                */

                'total_coop_equity' => $totalCoopEquity,

                'pending_count' => $pendingApprovals,

                'member_summaries' => $memberSummaries,

                'vouchers' => $vouchers,

                'search' => $search,

                'start_date' => $start_date,

                'end_date' => $end_date

            ]
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        $_SESSION['error_message'] =
            'Unable to load the Ledger Dashboard.';

        return render(
            'ledger_dashboard',
            [

                'kpis' => [],

                'memberTable' => [

                    'headers' => [],

                    'rows' => []

                ],

                'voucherTable' => [

                    'headers' => [],

                    'rows' => []

                ],

                'total_coop_equity' => 0,

                'pending_count' => 0,

                'member_summaries' => [],

                'vouchers' => [],

                'search' => '',

                'start_date' => '',

                'end_date' => ''

            ]
        );
    }
}


/**
 * ==========================================================
 * Create Ledger Entry
 * ==========================================================
 */

function handleCreateLedgerEntry(PDO $pdo): string
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return render('ledger_entry_add', [
            'members' => $pdo
                ->query("
                    SELECT
                        id,
                        member_number,
                        first_name,
                        last_name
                    FROM members
                    ORDER BY last_name
                ")
                ->fetchAll(PDO::FETCH_ASSOC)
        ]);
    }

    $member_id   = (int) ($_POST['member_id'] ?? 0);
    $entry_type  = trim($_POST['entry_type'] ?? '');
    $amount      = (float) ($_POST['amount'] ?? 0);
    $particulars = trim($_POST['particulars'] ?? 'Manual ledger entry');
    $reference_number = trim($_POST['reference_number'] ?? '');

    if ($reference_number !== '') {

        $stmt = $pdo->prepare("
            SELECT id
            FROM journal_vouchers
            WHERE reference_number = ?
        ");

        $stmt->execute([$reference_number]);

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {

            flashError(
                'Reference number already exists.'
            );

            return render('ledger_entry_add', [
                'members' => $pdo
                    ->query("
                        SELECT
                            id,
                            member_number,
                            first_name,
                            last_name
                        FROM members
                        ORDER BY last_name
                    ")
                    ->fetchAll(PDO::FETCH_ASSOC)
            ]);
        }
    } else {

        $reference_number = 'JV-' . strtoupper(uniqid());
    }

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO journal_vouchers
            (
                reference_number,
                transaction_date,
                particulars,
                created_by,
                status
            )
            VALUES
            (
                ?,
                NOW(),
                ?,
                ?,
                'pending'
            )
        ");

        $stmt->execute([
            $reference_number,
            $particulars,
            $_SESSION['user_id']
        ]);

        $voucher_id = (int) $pdo->lastInsertId();

        $credit = in_array(
            $entry_type,
            ['deposit', 'dividend'],
            true
        ) ? $amount : 0;

        $debit = in_array(
            $entry_type,
            ['deposit', 'dividend'],
            true
        ) ? 0 : $amount;

        $stmt = $pdo->prepare("
            INSERT INTO ledger_entries
            (
                voucher_id,
                member_id,
                entry_type,
                debit,
                credit
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $voucher_id,
            $member_id,
            $entry_type,
            $debit,
            $credit
        ]);

        $pdo->commit();

        redirectSuccess(
            'ledger',
            "Voucher {$reference_number} submitted for approval."
        );
    } catch (PDOException $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log($e->getMessage());

        flashError(
            'Unable to create the ledger entry.'
        );

        return render('ledger_entry_add', [
            'members' => $pdo
                ->query("
                    SELECT
                        id,
                        member_number,
                        first_name,
                        last_name
                    FROM members
                    ORDER BY last_name
                ")
                ->fetchAll(PDO::FETCH_ASSOC)
        ]);
    }
}

/**
 * ==========================================================
 * Ledger Statement
 * ==========================================================
 */
function handleLedgerStatement(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $member_id  = (int) ($_GET['id'] ?? 0);
    $start_date = trim($_GET['start_date'] ?? '');
    $end_date   = trim($_GET['end_date'] ?? '');

    try {

        /*
        |--------------------------------------------------------------------------
        | Member Information
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT *
            FROM members
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$member_id]);

        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member) {

            redirectError(
                'ledger',
                'Member not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ledger Transactions
        |--------------------------------------------------------------------------
        */

        $sql = "
            SELECT
                le.entry_type,
                le.debit,
                le.credit,
                jv.transaction_date,
                jv.reference_number,
                jv.particulars

            FROM ledger_entries le

            INNER JOIN journal_vouchers jv
                ON le.voucher_id = jv.id

            WHERE
                le.member_id = ?
                AND jv.status = 'approved'
        ";

        $params = [$member_id];

        if ($start_date !== '') {

            $sql .= " AND DATE(jv.transaction_date) >= ?";

            $params[] = $start_date;
        }

        if ($end_date !== '') {

            $sql .= " AND DATE(jv.transaction_date) <= ?";

            $params[] = $end_date;
        }

        $sql .= "
            ORDER BY
                jv.transaction_date ASC,
                le.id ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return render('ledger_statement', [

            'member'     => $member,
            'history'    => $history,
            'start_date' => $start_date,
            'end_date'   => $end_date

        ]);
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'ledger',
            'Unable to load the ledger statement.'
        );
    }
}

/**
 * ==========================================================
 * Pending Voucher Approvals
 * ==========================================================
 */
function handlePendingApprovals(PDO $pdo): string
{
    checkAuthenticated($pdo);

    if (
        !isset($_SESSION['role_id']) ||
        !in_array((int) $_SESSION['role_id'], [1, 2], true)
    ) {

        redirectError(
            'ledger',
            'Access denied.'
        );
    }

    try {

        $stmt = $pdo->query("
            SELECT

                jv.*,

                le.entry_type,
                le.credit,
                le.debit,

                m.first_name,
                m.last_name

            FROM journal_vouchers jv

            INNER JOIN ledger_entries le
                ON jv.id = le.voucher_id

            INNER JOIN members m
                ON le.member_id = m.id

            WHERE
                jv.status = 'pending'

            ORDER BY
                jv.transaction_date ASC
        ");

        $pending_vouchers =
            $stmt->fetchAll(PDO::FETCH_ASSOC);

        return render('ledger_pending', [

            'pending_vouchers' => $pending_vouchers

        ]);
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'ledger',
            'Unable to load pending approvals.'
        );
    }
}


/**
 * ==========================================================
 * Approve Voucher
 * ==========================================================
 */
function handleApproveVoucher(PDO $pdo): void
{
    checkAuthenticated($pdo);

    if (
        !isset($_SESSION['role_id']) ||
        (int) $_SESSION['role_id'] !== 1
    ) {

        $_SESSION['error_message'] =
            "Access denied.";

        header("Location:index.php?route=ledger");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php?route=pending_approvals");
        exit;
    }

    $voucher_id = (int) ($_POST['voucher_id'] ?? 0);

    try {

        $stmt = $pdo->prepare("
            UPDATE journal_vouchers
            SET status='approved'
            WHERE id=?
        ");

        $stmt->execute([$voucher_id]);

        logSystemActivity(
            $pdo,
            'VOUCHER_APPROVAL',
            "Approved journal voucher #{$voucher_id}"
        );

        $_SESSION['success_message'] =
            "Voucher approved successfully.";

        header("Location:index.php?route=pending_approvals");
        exit;
    } catch (PDOException $e) {

        die("Database error: " . $e->getMessage());
    }
}
