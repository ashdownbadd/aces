<?php
// controllers/MemberController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

function handleCoopMemberList($pdo)
{
    checkAuthenticated($pdo);

    $searchTerm = trim($_GET['search'] ?? '');

    try {
        // Base query now includes ledger_entries to calculate real-time share capital
        $sql = "SELECT m.id, m.member_number, m.first_name, m.last_name, m.status, 
                       c.email, c.phone_no_1 AS phone,
                       (COALESCE(SUM(le.credit), 0) - COALESCE(SUM(le.debit), 0)) AS share_capital
                FROM members m
                LEFT JOIN member_contact c ON m.id = c.member_id
                LEFT JOIN ledger_entries le ON m.id = le.member_id";

        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " WHERE m.first_name LIKE ? OR m.last_name LIKE ? OR m.member_number LIKE ?";
            $likeTerm = "%" . $searchTerm . "%";
            $params = [$likeTerm, $likeTerm, $likeTerm];
        }

        // Grouping is required when using SUM() to keep rows separate per member
        $sql .= " GROUP BY m.id, c.email, c.phone_no_1 ORDER BY m.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $coop_members = $stmt->fetchAll();

        include dirname(__DIR__) . '/views/member.php';
    } catch (PDOException $e) {
        die("Database error fetching members: " . $e->getMessage());
    }
}

function handleMemberProfile($pdo)
{
    checkAuthenticated($pdo);
    $member_id = intval($_GET['id'] ?? 0);

    try {
        // 1. Fetch Core Member Data
        $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
        $stmt->execute([$member_id]);
        $member = $stmt->fetch();

        if (!$member) die("Member profile not found.");

        // 2. Fetch all relational tables
        $relations = [
            'profile' => 'member_profiles',
            'contact' => 'member_contact',
            'address' => 'member_addresses'
        ];

        foreach ($relations as $key => $table) {
            $s = $pdo->prepare("SELECT * FROM $table WHERE member_id = ?");
            $s->execute([$member_id]);
            $member[$key] = $s->fetch();
        }

        // 3. Fetch multi-row lists
        $lists = ['education', 'experience', 'beneficiaries'];
        foreach ($lists as $list) {
            $s = $pdo->prepare("SELECT * FROM member_$list WHERE member_id = ?");
            $s->execute([$member_id]);
            $member[$list] = $s->fetchAll();
        }

        include dirname(__DIR__) . '/views/member_profile.php';
    } catch (PDOException $e) {
        die("Database Error loading profile: " . $e->getMessage());
    }
}

function handleCreateCoopMember($pdo)
{
    checkAuthenticated($pdo);

    // If the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = trim($_POST['first_name'] ?? '');
        $middle_name = trim($_POST['middle_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $date_of_birth = trim($_POST['date_of_birth'] ?? '');
        $membership_type = trim($_POST['membership_type'] ?? 'Regular');

        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($date_of_birth)) {
            $_SESSION['error_message'] = "First name, last name, and date of birth are required.";
            include dirname(__DIR__) . '/views/member_add.php';
            return;
        }

        try {
            // Start transaction to ensure all database actions succeed or fail together
            $pdo->beginTransaction();

            // 1. Insert core entity record into members table along with its date_of_birth column
            $sqlMember = "INSERT INTO members (member_number, first_name, middle_name, last_name, date_of_birth, membership_type, status, date_of_membership) 
                          VALUES ('TEMP', ?, ?, ?, ?, ?, 'active', CURDATE())";
            $stmtM = $pdo->prepare($sqlMember);
            $stmtM->execute([$first_name, $middle_name, $last_name, $date_of_birth, $membership_type]);

            // 2. Retrieve the auto-incremented ID assigned by MySQL
            $member_id = $pdo->lastInsertId();

            // 3. Generate the formatted member number (e.g., COOP-2026-0051)
            $year = date('Y');
            $formatted_member_no = "COOP-" . $year . "-" . str_pad($member_id, 4, "0", STR_PAD_LEFT);

            // 4. Update the newly created record with the correct, formatted number
            $updateStmt = $pdo->prepare("UPDATE members SET member_number = ? WHERE id = ?");
            $updateStmt->execute([$formatted_member_no, $member_id]);

            // 5. Save all comprehensive profile data from the expanded form

            // Extract data safely from POST
            $sex          = trim($_POST['sex'] ?? '');
            $civil_status = trim($_POST['marital_status'] ?? $_POST['marital_status'] ?? ''); // Fallback compatibility check
            $religion     = trim($_POST['religion'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $phone_no_1   = trim($_POST['phone_no_1'] ?? '');
            $phone_no_2   = trim($_POST['phone_no_2'] ?? '');

            // Extract modular address parts matching your exact database columns
            $house_number = trim($_POST['house_number'] ?? '');
            $street       = trim($_POST['street'] ?? '');
            $barangay     = trim($_POST['barangay'] ?? '');
            $town_city    = trim($_POST['town_city'] ?? '');
            $province     = trim($_POST['province'] ?? '');
            $region       = trim($_POST['region'] ?? '');
            $address_type = 'Home'; // Default fallback value for required schema categorization

            // Insert into member_profiles matching the profile layout keys (civil_status, religion)
            $stmtProfile = $pdo->prepare("INSERT INTO member_profiles (member_id, sex, civil_status, religion) VALUES (?, ?, ?, ?)");
            $stmtProfile->execute([$member_id, $sex, $civil_status, $religion]);

            // Insert into Contact
            $stmtContact = $pdo->prepare("INSERT INTO member_contact (member_id, email, phone_no_1, phone_no_2) VALUES (?, ?, ?, ?)");
            $stmtContact->execute([$member_id, $email, $phone_no_1, $phone_no_2]);

            // Insert into member_addresses with your exact updated schema structural names
            $stmtAddress = $pdo->prepare("INSERT INTO member_addresses (member_id, address_type, house_number, street, barangay, town_city, province, region) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtAddress->execute([$member_id, $address_type, $house_number, $street, $barangay, $town_city, $province, $region]);

            // Save all changes
            $pdo->commit();

            // Redirect back to members list with success alert
            $_SESSION['success_message'] = "Member successfully registered. Member ID: " . $formatted_member_no;
            header("Location: index.php?route=members");
            exit;
        } catch (PDOException $e) {
            // Cancel all changes if something went wrong
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
            include dirname(__DIR__) . '/views/member_add.php';
            return;
        }
    } else {
        // If it's a GET request, just show the form
        include dirname(__DIR__) . '/views/member_add.php';
    }
}
// FIXED: Removed the duplicate closing curly brace that was causing the parsing syntax crash here