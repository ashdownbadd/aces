<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

function handleCoopMemberList(PDO $pdo): string
{
    checkAuthenticated($pdo);

    try {

        $filters = getMemberFilters();

        $members = fetchMembers($pdo, $filters);

        $stats = calculateMemberStats($members);

        $rows = buildMemberRows($members);

        return render('member', [

            'headers' => getMemberHeaders(),
            'rows' => $rows,

            'searchTerm' => $filters['search'],
            'statusFilter' => $filters['status'],
            'membershipFilter' => $filters['membership'],

            'totalMembers' => $stats['totalMembers'],
            'activeMembers' => $stats['activeMembers'],
            'inactiveMembers' => $stats['inactiveMembers'],
            'totalShareCapital' => $stats['totalShareCapital']

        ]);
    } catch (PDOException $e) {

        error_log($e->getMessage());

        flashError(
            'Unable to retrieve cooperative members.'
        );

        return render('member', [

            'headers' => [],
            'rows' => [],

            'searchTerm' => '',
            'statusFilter' => '',
            'membershipFilter' => '',

            'totalMembers' => 0,
            'activeMembers' => 0,
            'inactiveMembers' => 0,
            'totalShareCapital' => 0

        ]);
    }
}

function getMemberFilters(): array
{
    return [

        'search' => trim($_GET['search'] ?? ''),

        'status' => trim($_GET['status'] ?? ''),

        'membership' => trim($_GET['membership'] ?? '')

    ];
}

function getMemberHeaders(): array
{
    return [
        'Member ID',
        'Member',
        'Email',
        'Phone',
        'Share Capital',
        'Status',
        'Actions'
    ];
}

function formatMemberName(array $member): string
{
    return htmlspecialchars(
        trim(
            $member['first_name'] . ' ' . $member['last_name']
        )
    );
}

function formatShareCapital(array $member): string
{
    $amount = (float) ($member['share_capital'] ?? 0);

    $formatted = floor($amount) == $amount
        ? number_format($amount, 0)
        : number_format($amount, 2);

    return '<strong>₱' . $formatted . '</strong>';
}

function buildStatusBadge(array $member): string
{
    $status = strtolower($member['status'] ?? '');

    ob_start();

    c('badge', [
        'type' => $status === 'active'
            ? 'success'
            : 'warning',
        'text' => ucfirst($status ?: 'Unknown')
    ]);

    return ob_get_clean();
}

function buildMemberActionButton(array $member): string
{
    $profileUrl = url('member_profile') . '&id=' . (int) $member['id'];

    ob_start();

    c('button', [
        'href' => $profileUrl,
        'text' => 'View',
        'icon' => 'fas fa-arrow-right',
        'type' => 'secondary',
        'size' => 'sm'
    ]);

    return ob_get_clean();
}
function formatMemberNumber(array $member): string
{
    return '<code>' . htmlspecialchars(
        preg_replace(
            '/^COOP-\d{4}-/',
            '#',
            $member['member_number']
        )
    ) . '</code>';
}

function fetchMembers(PDO $pdo, array $filters): array
{
    $sql = "
    SELECT
      m.id,
      m.member_number,
      m.first_name,
      m.last_name,
      m.status,
      m.membership_type,

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

    $where = [];
    $params = [];

    if ($filters['search'] !== '') {

        $where[] = "(
      m.member_number LIKE ?
      OR m.first_name LIKE ?
      OR m.last_name LIKE ?
      OR CONCAT(m.first_name, ' ', m.last_name) LIKE ?
    )";

        $like = '%' . $filters['search'] . '%';

        array_push(
            $params,
            $like,
            $like,
            $like,
            $like
        );
    }

    if ($filters['status'] !== '') {

        $where[] = "m.status = ?";

        $params[] = $filters['status'];
    }

    if ($filters['membership'] !== '') {

        $where[] = "m.membership_type = ?";

        $params[] = $filters['membership'];
    }

    if ($where) {

        $sql .= "\nWHERE\n  " . implode("\n  AND ", $where);
    }

    $sql .= "

    GROUP BY

      m.id,
      m.member_number,
      m.first_name,
      m.last_name,
      m.status,
      m.membership_type,

      c.email,
      c.phone_no_1

    ORDER BY

      m.last_name,
      m.first_name
  ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function calculateMemberStats(array $members): array
{
    $totalMembers = count($members);

    $activeMembers = count(array_filter(
        $members,
        fn($member) => strtolower($member['status']) === 'active'
    ));

    $inactiveMembers = $totalMembers - $activeMembers;

    $totalShareCapital = array_sum(array_map(
        fn($member) => (float) ($member['share_capital'] ?? 0),
        $members
    ));

    return [
        'totalMembers' => $totalMembers,
        'activeMembers' => $activeMembers,
        'inactiveMembers' => $inactiveMembers,
        'totalShareCapital' => $totalShareCapital
    ];
}

function buildMemberRows(array $members): array
{
    $rows = [];

    foreach ($members as $member) {

        $rows[] = [
            formatMemberNumber($member),
            formatMemberName($member),
            htmlspecialchars($member['email'] ?: 'N/A'),
            htmlspecialchars($member['phone'] ?: 'N/A'),
            formatShareCapital($member),
            buildStatusBadge($member),
            buildMemberActionButton($member)
        ];
    }

    return $rows;
}

function getMemberDetails(PDO $pdo, int $member_id): array
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM members
        WHERE id = ?
    ");

    $stmt->execute([$member_id]);

    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {

        redirectError(
            'members',
            'Member profile not found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | One-to-One Relations
    |--------------------------------------------------------------------------
    */

    $relations = [

        'profile'    => 'member_profiles',

        'contact'    => 'member_contact',

        'address'    => 'member_addresses',

        'employment' => 'member_employment'

    ];

    foreach ($relations as $key => $table) {

        $stmt = $pdo->prepare("
            SELECT *
            FROM {$table}
            WHERE member_id = ?
        ");

        $stmt->execute([$member_id]);

        $member[$key] =
            $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /*
    |--------------------------------------------------------------------------
    | One-to-Many Relations
    |--------------------------------------------------------------------------
    */

    $lists = [

        'education',

        'beneficiaries'

    ];

    foreach ($lists as $list) {

        $stmt = $pdo->prepare("
            SELECT *
            FROM member_{$list}
            WHERE member_id = ?
        ");

        $stmt->execute([$member_id]);

        $member[$list] =
            $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Share Capital
    |--------------------------------------------------------------------------
    */

    $stmtLedger = $pdo->prepare("
        SELECT
            COALESCE(
                SUM(le.credit)
                -
                SUM(le.debit),
                0
            )
        FROM ledger_entries le

        INNER JOIN journal_vouchers jv
            ON le.voucher_id = jv.id

        WHERE
            le.member_id = ?
            AND jv.status = 'approved'
    ");

    $stmtLedger->execute([$member_id]);

    $member['ledger_balance'] =
        (float) $stmtLedger->fetchColumn();

    /*
    |--------------------------------------------------------------------------
    | Active Loan
    |--------------------------------------------------------------------------
    */

    $stmtLoan = $pdo->prepare("
        SELECT
            l.*,
            s.due_date AS next_due_date

        FROM loans l

        LEFT JOIN loan_schedules s
            ON l.id = s.loan_id
            AND s.due_date >= CURDATE()

        WHERE
            l.member_id = ?
            AND l.loan_status = 'approved'

        ORDER BY
            s.due_date ASC

        LIMIT 1
    ");

    $stmtLoan->execute([$member_id]);

    $member['active_loan'] =
        $stmtLoan->fetch(PDO::FETCH_ASSOC) ?: null;

    return $member;
}

function handleMemberProfile(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $member_id = (int) ($_GET['id'] ?? 0);

    try {

        $member = getMemberDetails(
            $pdo,
            $member_id
        );

        return render(
            'member_profile',
            [
                'member' => $member
            ]
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'members',
            'Unable to load member profile.'
        );
    }
}


function handleEditMember(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $member_id = (int) ($_GET['id'] ?? 0);

    try {

        $member = getMemberDetails(
            $pdo,
            $member_id
        );

        return render(
            'member_add',
            [
                'member' => $member
            ]
        );
    } catch (PDOException $e) {

        error_log($e->getMessage());

        redirectError(
            'members',
            'Unable to load member.'
        );
    }
}



function handleUpdateMember(PDO $pdo): void
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        redirectError(
            'members',
            'Invalid request.'
        );
    }

    $member_id = (int) ($_GET['id'] ?? 0);

    $beneficiaries = json_decode(
        $_POST['beneficiaries'] ?? '[]',
        true
    );

    if (!is_array($beneficiaries)) {
        $beneficiaries = [];
    }

    try {

        $pdo->beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | Members
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            UPDATE members
SET
    first_name = ?,
    middle_name = ?,
    last_name = ?,
    suffix = ?,
    date_of_birth = ?,
    membership_type = ?
WHERE id = ?
        ")->execute([

            trim($_POST['first_name'] ?? ''),
            trim($_POST['middle_name'] ?? ''),
            trim($_POST['last_name'] ?? ''),
            trim($_POST['suffix'] ?? ''),
            trim($_POST['date_of_birth'] ?? ''),
            trim($_POST['membership_type'] ?? 'Regular'),
            $member_id

        ]);

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            UPDATE member_profiles
SET
    title_rank = ?,
    position = ?,
    tin_no = ?,
    marital_status = ?,
    sex = ?,
    height = ?,
    weight = ?,
    complexion = ?,
    birthplace = ?
WHERE member_id = ?
        ")->execute([
            trim($_POST['title_rank'] ?? ''),
            trim($_POST['position'] ?? ''),
            trim($_POST['tin_no'] ?? ''),
            trim($_POST['marital_status'] ?? ''),
            trim($_POST['sex'] ?? ''),
            trim($_POST['height'] ?? ''),
            trim($_POST['weight'] ?? ''),
            trim($_POST['complexion'] ?? ''),
            trim($_POST['birthplace'] ?? ''),
            $member_id
        ]);

        /*
        |--------------------------------------------------------------------------
        | Contact
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            UPDATE member_contact
            SET
                email = ?,
                phone_no_1 = ?,
                phone_no_2 = ?
            WHERE member_id = ?
        ")->execute([

            trim($_POST['email'] ?? ''),
            trim($_POST['phone_no_1'] ?? ''),
            trim($_POST['phone_no_2'] ?? ''),

            $member_id

        ]);

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            UPDATE member_addresses
            SET
                house_number = ?,
                street = ?,
                barangay = ?,
                town_city = ?,
                province = ?,
                region = ?
            WHERE member_id = ?
        ")->execute([

            trim($_POST['house_number'] ?? ''),
            trim($_POST['street'] ?? ''),
            trim($_POST['barangay'] ?? ''),
            trim($_POST['town_city'] ?? ''),
            trim($_POST['province'] ?? ''),
            trim($_POST['region'] ?? ''),

            $member_id

        ]);

        /*
        |--------------------------------------------------------------------------
        | Employment
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            UPDATE member_employment
            SET
    employment_status = ?,
    occupation = ?,
    employer_name = ?,
    employer_address = ?,
    monthly_income = ?,
    employment_remarks = ?
            WHERE member_id = ?
        ")->execute([

            trim($_POST['employment_status'] ?? ''),
            trim($_POST['occupation'] ?? ''),
            trim($_POST['employer_name'] ?? ''),
            trim($_POST['employer_address'] ?? ''),

            !empty($_POST['monthly_income'])
                ? (float) preg_replace(
                    '/[^\d.]/',
                    '',
                    trim($_POST['monthly_income'])
                )
                : null,

            trim($_POST['employment_remarks'] ?? ''),

            $member_id

        ]);

        /*
        |--------------------------------------------------------------------------
        | Education
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            UPDATE member_education
            SET
    education_level = ?,
    course = ?,
    school = ?,
    year_graduated = ?,
    honors = ?,
    education_remarks = ?
            WHERE member_id = ?
        ")->execute([

            trim($_POST['education_level'] ?? ''),
            trim($_POST['course'] ?? ''),
            trim($_POST['school'] ?? ''),

            !empty($_POST['year_graduated'])
                ? (int) $_POST['year_graduated']
                : null,

            trim($_POST['honors'] ?? ''),

            trim($_POST['education_remarks'] ?? ''),

            $member_id

        ]);

        /*
        |--------------------------------------------------------------------------
        | Beneficiaries
        |--------------------------------------------------------------------------
        */

        $pdo->prepare("
            DELETE FROM member_beneficiaries
            WHERE member_id = ?
        ")->execute([$member_id]);

        foreach ($beneficiaries as $beneficiary) {

            if (empty(trim($beneficiary['full_name'] ?? ''))) {
                continue;
            }

            $pdo->prepare("
                INSERT INTO member_beneficiaries
                (
                    member_id,
                    full_name,
                    relationship,
                    birth_date,
                    contact_number,
                    allocation
                )
                VALUES
                (?, ?, ?, ?, ?, ?)
            ")->execute([

                $member_id,

                trim($beneficiary['full_name'] ?? ''),

                trim($beneficiary['relationship'] ?? ''),

                !empty($beneficiary['birth_date'])
                    ? $beneficiary['birth_date']
                    : null,

                trim($beneficiary['contact_number'] ?? ''),

                (float) ($beneficiary['allocation'] ?? 0)

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        logSystemActivity(

            $pdo,

            'MEMBER_UPDATED',

            "Updated member ID: {$member_id}"

        );

        $pdo->commit();

        redirectSuccess(
            'member_profile',
            'Member successfully updated.',
            [
                'id' => $member_id
            ]
        );
    } catch (PDOException $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log($e->getMessage());

        redirectError(
            'member_profile',
            'Unable to update member.',
            [
                'id' => $member_id
            ]
        );
    }
}

function handleCreateCoopMember(PDO $pdo): string
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

    $beneficiaries = json_decode(
        $_POST['beneficiaries'] ?? '[]',
        true
    );

    if (!is_array($beneficiaries)) {
        $beneficiaries = [];
    }

    if (
        $first_name === '' ||
        $last_name === '' ||
        $date_of_birth === ''
    ) {

        flashError(
            'First name, last name, and date of birth are required.'
        );

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
    suffix,
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
    ?,
    'active',
    CURDATE()
)
        ");

        $stmtM->execute([
            $first_name,
            $middle_name,
            $last_name,
            trim($_POST['suffix'] ?? ''),
            $date_of_birth,
            $membership_type
        ]);

        $member_id = (int) $pdo->lastInsertId();

        $formatted_member_no =
            'COOP-'
            . date('Y')
            . '-'
            . str_pad(
                (string) $member_id,
                4,
                '0',
                STR_PAD_LEFT
            );

        $pdo->prepare("
            UPDATE members
            SET member_number = ?
            WHERE id = ?
        ")->execute([
            $formatted_member_no,
            $member_id
        ]);

        $pdo->prepare("
            INSERT INTO member_profiles
(
    member_id,
    title_rank,
    position,
    tin_no,
    marital_status,
    sex,
    height,
    weight,
    complexion,
    birthplace
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([
            $member_id,
            trim($_POST['title_rank'] ?? ''),
            trim($_POST['position'] ?? ''),
            trim($_POST['tin_no'] ?? ''),
            trim($_POST['marital_status'] ?? ''),
            trim($_POST['sex'] ?? ''),
            trim($_POST['height'] ?? ''),
            trim($_POST['weight'] ?? ''),
            trim($_POST['complexion'] ?? ''),
            trim($_POST['birthplace'] ?? '')
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

        $pdo->prepare("
    INSERT INTO member_employment
(
    member_id,
    employment_status,
    occupation,
    employer_name,
    employer_address,
    monthly_income,
    employment_remarks
)
    VALUES
(?, ?, ?, ?, ?, ?, ?)
")->execute([

            $member_id,

            trim($_POST['employment_status'] ?? ''),

            trim($_POST['occupation'] ?? ''),

            trim($_POST['employer_name'] ?? ''),

            trim($_POST['employer_address'] ?? ''),

            !empty($_POST['monthly_income'])
                ? (float) preg_replace(
                    '/[^\d.]/',
                    '',
                    trim($_POST['monthly_income'])
                )
                : null,

            trim($_POST['employment_remarks'] ?? ''),

        ]);

        $pdo->prepare("
    INSERT INTO member_education
(
    member_id,
    education_level,
    course,
    school,
    year_graduated,
    honors,
    education_remarks
)
    VALUES
(?, ?, ?, ?, ?, ?, ?)
")->execute([

            $member_id,

            trim($_POST['education_level'] ?? ''),

            trim($_POST['course'] ?? ''),

            trim($_POST['school'] ?? ''),

            !empty($_POST['year_graduated'])
                ? (int) $_POST['year_graduated']
                : null,

            trim($_POST['honors'] ?? ''),

            trim($_POST['education_remarks'] ?? '')

        ]);

        foreach ($beneficiaries as $beneficiary) {

            if (empty(trim($beneficiary['full_name'] ?? ''))) {
                continue;
            }

            $pdo->prepare("
        INSERT INTO member_beneficiaries
        (
            member_id,
            full_name,
            relationship,
            birth_date,
            contact_number,
            allocation
        )
        VALUES
        (?, ?, ?, ?, ?, ?)
    ")->execute([

                $member_id,

                trim($beneficiary['full_name'] ?? ''),

                trim($beneficiary['relationship'] ?? ''),

                !empty($beneficiary['birth_date'])
                    ? $beneficiary['birth_date']
                    : null,

                trim($beneficiary['contact_number'] ?? ''),

                (float) ($beneficiary['allocation'] ?? 0)

            ]);
        }

        logSystemActivity(
            $pdo,
            'MEMBER_CREATED',
            "Created new member: {$first_name} {$last_name} (ID: {$formatted_member_no})"
        );

        $pdo->commit();

        redirectSuccess(
            'members',
            "Member successfully registered. Member ID: {$formatted_member_no}"
        );
    } catch (PDOException $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log($e->getMessage());

        echo '<pre>';
        echo $e->getMessage();
        exit;
    }
}
