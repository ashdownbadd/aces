<?php
function handleDashboard($pdo) {
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