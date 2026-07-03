<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

function handleCoopMemberList(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $searchTerm = trim($_GET['search'] ?? '');

    try {

        $sql = "
            SELECT
                m.id,
                m.member_number,
                m.first_name,
                m.last_name,
                m.status,
                c.email,
                c.phone_no_1 AS phone,
                (
                    COALESCE(SUM(
                        CASE
                            WHEN jv.status = 'approved'
                            THEN le.credit
                            ELSE 0
                        END
                    ), 0)
                    -
                    COALESCE(SUM(
                        CASE
                            WHEN jv.status = 'approved'
                            THEN le.debit
                            ELSE 0
                        END
                    ), 0)
                ) AS share_capital

            FROM members m

            LEFT JOIN member_contact c
                ON c.member_id = m.id

            LEFT JOIN ledger_entries le
                ON le.member_id = m.id

            LEFT JOIN journal_vouchers jv
                ON jv.id = le.voucher_id
        ";

        $params = [];

        if ($searchTerm !== '') {

            $sql .= "
                WHERE
                    m.first_name LIKE ?
                    OR m.last_name LIKE ?
                    OR m.member_number LIKE ?
            ";

            $like = "%{$searchTerm}%";

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
                m.last_name,
                m.status,
                c.email,
                c.phone_no_1

            ORDER BY
                m.last_name ASC,
                m.first_name ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $members = $stmt->fetchAll();

        /*
        |--------------------------------------------------------------------------
        | Table View Model
        |--------------------------------------------------------------------------
        */

        $headers = [

            'Member No.',

            'Full Name',

            'Email',

            'Phone',

            'Share Capital',

            'Status'

        ];

        $rows = [];

        foreach ($members as $member) {

            $profileUrl =
                'index.php?route=member_profile&id='
                . (int)$member['id'];

            $status = strtolower($member['status'] ?? '');

            if ($status === 'active') {

                $statusBadge =
                    '<span class="status status--active">ACTIVE</span>';
            } else {

                $statusBadge =
                    '<span class="status status--inactive">'
                    . htmlspecialchars(strtoupper($status ?: 'UNKNOWN'))
                    . '</span>';
            }

            $rows[] = [

                '<code class="code">'
                    . htmlspecialchars($member['member_number'])
                    . '</code>',

                '<a class="module-card__title" href="'
                    . $profileUrl
                    . '">'
                    . htmlspecialchars(
                        $member['last_name']
                            . ', '
                            . $member['first_name']
                    )
                    . '</a>',

                htmlspecialchars(
                    $member['email'] ?: 'N/A'
                ),

                htmlspecialchars(
                    $member['phone'] ?: 'N/A'
                ),

                '<strong>₱'
                    . number_format(
                        (float)$member['share_capital'],
                        2
                    )
                    . '</strong>',

                $statusBadge

            ];
        }

        return render('member', [

            'headers' => $headers,

            'rows' => $rows,

            'searchTerm' => $searchTerm

        ]);
    } catch (PDOException $e) {

        $_SESSION['error_message'] =
            'Unable to retrieve cooperative members.';

        error_log($e->getMessage());

        return render('member', [

            'headers' => [],

            'rows' => [],

            'searchTerm' => $searchTerm

        ]);
    }
}

function handleMemberProfile(PDO $pdo)
{
    checkAuthenticated($pdo);

    $member_id = intval($_GET['id'] ?? 0);

    try {

        $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
        $stmt->execute([$member_id]);

        $member = $stmt->fetch();

        if (!$member) {
            die("Member profile not found.");
        }

        $relations = [
            'profile' => 'member_profiles',
            'contact' => 'member_contact',
            'address' => 'member_addresses'
        ];

        foreach ($relations as $key => $table) {

            $s = $pdo->prepare("SELECT * FROM {$table} WHERE member_id = ?");
            $s->execute([$member_id]);

            $member[$key] = $s->fetch() ?: [];
        }

        $lists = [
            'education',
            'experience',
            'beneficiaries'
        ];

        foreach ($lists as $list) {

            $s = $pdo->prepare("SELECT * FROM member_{$list} WHERE member_id = ?");
            $s->execute([$member_id]);

            $member[$list] = $s->fetchAll() ?: [];
        }

        $stmtLedger = $pdo->prepare("
            SELECT
                COALESCE(SUM(le.credit) - SUM(le.debit),0)
            FROM ledger_entries le
            JOIN journal_vouchers jv
                ON le.voucher_id = jv.id
            WHERE
                le.member_id = ?
                AND jv.status='approved'
        ");

        $stmtLedger->execute([$member_id]);

        $member['ledger_balance'] = $stmtLedger->fetchColumn() ?: 0;

        $stmtLoan = $pdo->prepare("
            SELECT
                l.*,
                s.due_date AS next_due_date

            FROM loans l

            LEFT JOIN loan_schedules s
                ON l.id=s.loan_id
                AND s.due_date>=CURDATE()

            WHERE
                l.member_id=?
                AND l.loan_status='approved'

            ORDER BY s.due_date ASC

            LIMIT 1
        ");

        $stmtLoan->execute([$member_id]);

        $member['active_loan'] = $stmtLoan->fetch() ?: null;

        return render('member_profile', [
            'member' => $member
        ]);
    } catch (PDOException $e) {

        die("Database Error loading profile: " . $e->getMessage());
    }
}

function handleCreateCoopMember(PDO $pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        return render('member_add');
    }

    $first_name      = trim($_POST['first_name'] ?? '');
    $middle_name     = trim($_POST['middle_name'] ?? '');
    $last_name       = trim($_POST['last_name'] ?? '');
    $date_of_birth   = trim($_POST['date_of_birth'] ?? '');
    $membership_type = trim($_POST['membership_type'] ?? 'Regular');

    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($date_of_birth)
    ) {

        $_SESSION['error_message'] =
            "First name, last name, and date of birth are required.";

        return render('member_add');
    }

    try {

        $pdo->beginTransaction();

        $stmtM = $pdo->prepare("
            INSERT INTO members
            (
                member_number,
                first_name,
                middle_name,
                last_name,
                date_of_birth,
                membership_type,
                status,
                date_of_membership
            )
            VALUES
            (
                'TEMP',
                ?,
                ?,
                ?,
                ?,
                ?,
                'active',
                CURDATE()
            )
        ");

        $stmtM->execute([
            $first_name,
            $middle_name,
            $last_name,
            $date_of_birth,
            $membership_type
        ]);

        $member_id = $pdo->lastInsertId();

        $formatted_member_no =
            "COOP-" .
            date('Y') .
            "-" .
            str_pad($member_id, 4, "0", STR_PAD_LEFT);

        $pdo->prepare("
            UPDATE members
            SET member_number=?
            WHERE id=?
        ")->execute([
            $formatted_member_no,
            $member_id
        ]);

        $pdo->prepare("
            INSERT INTO member_profiles
            (member_id, sex, marital_status)
            VALUES (?, ?, ?)
        ")->execute([
            $member_id,
            trim($_POST['sex'] ?? ''),
            trim($_POST['marital_status'] ?? '')
        ]);

        $pdo->prepare("
            INSERT INTO member_contact
            (
                member_id,
                email,
                phone_no_1,
                phone_no_2
            )
            VALUES (?, ?, ?, ?)
        ")->execute([
            $member_id,
            trim($_POST['email'] ?? ''),
            trim($_POST['phone_no_1'] ?? ''),
            trim($_POST['phone_no_2'] ?? '')
        ]);

        $pdo->prepare("
            INSERT INTO member_addresses
            (
                member_id,
                address_type,
                house_number,
                street,
                barangay,
                town_city,
                province,
                region
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([
            $member_id,
            'Home',
            trim($_POST['house_number'] ?? ''),
            trim($_POST['street'] ?? ''),
            trim($_POST['barangay'] ?? ''),
            trim($_POST['town_city'] ?? ''),
            trim($_POST['province'] ?? ''),
            trim($_POST['region'] ?? '')
        ]);

        logSystemActivity(
            $pdo,
            'MEMBER_CREATED',
            "Created new member: {$first_name} {$last_name} (ID: {$formatted_member_no})"
        );

        $pdo->commit();

        $_SESSION['success_message'] =
            "Member successfully registered. Member ID: {$formatted_member_no}";

        header("Location: index.php?route=members");
        exit;
    } catch (PDOException $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $_SESSION['error_message'] =
            "Database error: " . $e->getMessage();

        return render('member_add');
    }
}
