<?php
function handleDashboard($pdo)
{
    checkAuthenticated($pdo);

    // 1. Fetch Membership Type Breakdown
    $stmt = $pdo->query("SELECT membership_type, COUNT(*) as count FROM members GROUP BY membership_type");
    $types = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 2. Fetch Status Breakdown
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM members GROUP BY status");
    $status = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 3. Fetch Gender Breakdown (via member_profiles)
    $stmt = $pdo->query("SELECT sex, COUNT(*) as count FROM member_profiles GROUP BY sex");
    $gender = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 4. Total Members
    $total_members = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();

    include dirname(__DIR__) . '/views/dashboard.php';
}

function getSystemAlerts($pdo)
{
    $alerts = [];

    // 1. Find members with Negative Share Capital
    $stmt = $pdo->query("SELECT m.id, m.first_name, m.last_name, 
                         (SUM(le.credit) - SUM(le.debit)) as balance 
                         FROM members m 
                         JOIN ledger_entries le ON m.id = le.member_id 
                         GROUP BY m.id HAVING balance < 0");
    $alerts['negative_equity'] = $stmt->fetchAll();

    // 2. Find Past Due Loans
    $stmtLoans = $pdo->query("SELECT l.id, m.first_name, m.last_name, s.due_date 
                              FROM loans l 
                              JOIN members m ON l.member_id = m.id
                              JOIN loan_schedules s ON l.id = s.loan_id
                              WHERE l.loan_status IN ('approved', 'ongoing') 
                              AND s.due_date < CURDATE() 
                              AND s.status != 'paid'
                              GROUP BY l.id");
    $alerts['past_due_loans'] = $stmtLoans->fetchAll();

    return $alerts;
}