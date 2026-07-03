<?php
// controllers/DashboardController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/*
|--------------------------------------------------------------------------
| Dashboard Controller
|--------------------------------------------------------------------------
*/

function handleDashboard(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $stats = getDashboardStatistics($pdo);

    $alerts = getSystemAlerts($pdo);

    /*
    |--------------------------------------------------------------------------
    | Dashboard Cards
    |--------------------------------------------------------------------------
    */

    $cards = [

        [

            'title'       => 'Members',

            'value'       => $stats['total_members'],

            'subtitle'    => 'Registered Members',

            'description' => 'Total cooperative members',

            'icon'        => 'fas fa-users',

            'color'       => 'primary',

            'url'         => url('members')

        ],

        [

            'title'       => 'Regular Members',

            'value'       => $stats['types']['Regular'] ?? 0,

            'subtitle'    => 'Membership Type',

            'description' => 'Associate: ' . ($stats['types']['Associate'] ?? 0),

            'icon'        => 'fas fa-id-card',

            'color'       => 'gold',

            'url'         => url('members')

        ],

        [

            'title'       => 'Active Members',

            'value'       => $stats['status']['active'] ?? 0,

            'subtitle'    => 'Membership Status',

            'description' => 'Inactive: ' . ($stats['status']['inactive'] ?? 0),

            'icon'        => 'fas fa-user-check',

            'color'       => 'success',

            'url'         => url('members')

        ],

        [

            'title'       => 'Female Members',

            'value'       => $stats['gender']['Female'] ?? 0,

            'subtitle'    => 'Gender Distribution',

            'description' => 'Male: ' . ($stats['gender']['Male'] ?? 0),

            'icon'        => 'fas fa-venus',

            'color'       => 'warning',

            'url'         => url('members')

        ]

    ];

    /*
    |--------------------------------------------------------------------------
    | Quick Actions
    |--------------------------------------------------------------------------
    */

    $modules = [

        [

            'title'       => 'Members',

            'description' => 'Manage cooperative members.',

            'icon'        => 'fas fa-users',

            'url'         => url('members'),

            'color'       => 'primary'

        ],

        [

            'title'       => 'Ledger',

            'description' => 'Accounting and journal vouchers.',

            'icon'        => 'fas fa-book',

            'url'         => url('ledger'),

            'color'       => 'success'

        ],

        [

            'title'       => 'Loans',

            'description' => 'Loan and amortization management.',

            'icon'        => 'fas fa-money-bill-wave',

            'url'         => url('amortization_dashboard'),

            'color'       => 'warning'

        ],

        [

            'title'       => 'Activity Logs',

            'description' => 'View system audit trail.',

            'icon'        => 'fas fa-history',

            'url'         => url('activity_logs'),

            'color'       => 'secondary'

        ]

    ];

    return render(
        'dashboard',
        array_merge(
            $stats,
            [
                'alerts' => $alerts,
                'cards' => $cards,
                'modules' => $modules
            ]
        )
    );
}

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

function getDashboardStatistics(PDO $pdo): array
{
    return [

        'total_members' => getTotalMembers($pdo),

        'types' => getMembershipTypes($pdo),

        'status' => getMemberStatus($pdo),

        'gender' => getMemberGender($pdo)

    ];
}

function getTotalMembers(PDO $pdo): int
{
    return (int) $pdo
        ->query("SELECT COUNT(*) FROM members")
        ->fetchColumn();
}

function getMembershipTypes(PDO $pdo): array
{
    return $pdo
        ->query("
            SELECT
                membership_type,
                COUNT(*) AS count
            FROM members
            GROUP BY membership_type
        ")
        ->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getMemberStatus(PDO $pdo): array
{
    return $pdo
        ->query("
            SELECT
                status,
                COUNT(*) AS count
            FROM members
            GROUP BY status
        ")
        ->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getMemberGender(PDO $pdo): array
{
    return $pdo
        ->query("
            SELECT
                sex,
                COUNT(*) AS count
            FROM member_profiles
            GROUP BY sex
        ")
        ->fetchAll(PDO::FETCH_KEY_PAIR);
}

/*
|--------------------------------------------------------------------------
| Dashboard Alerts
|--------------------------------------------------------------------------
*/

function getSystemAlerts(PDO $pdo): array
{
    return [

        'negative_equity' => getNegativeEquityMembers($pdo),

        'past_due_loans' => getPastDueLoans($pdo)

    ];
}

function getNegativeEquityMembers(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            m.id,
            m.member_number,
            m.first_name,
            m.last_name,
            (
                COALESCE(SUM(le.credit),0)
                -
                COALESCE(SUM(le.debit),0)
            ) AS balance

        FROM members m

        LEFT JOIN ledger_entries le
            ON m.id = le.member_id

        LEFT JOIN journal_vouchers jv
            ON le.voucher_id = jv.id

        WHERE
            jv.status = 'approved'
            OR jv.status IS NULL

        GROUP BY
            m.id,
            m.member_number,
            m.first_name,
            m.last_name

        HAVING balance < 0
    ");

    return $stmt->fetchAll();
}

function getPastDueLoans(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            l.id,
            l.member_id,
            m.member_number,
            m.first_name,
            m.last_name,
            COUNT(ls.id) AS overdue_count

        FROM loans l

        INNER JOIN members m
            ON l.member_id = m.id

        INNER JOIN loan_schedules ls
            ON l.id = ls.loan_id

        WHERE
            l.loan_status = 'Approved'
            AND ls.status = 'overdue'

        GROUP BY
            l.id,
            l.member_id,
            m.member_number,
            m.first_name,
            m.last_name
    ");

    return $stmt->fetchAll();
}
