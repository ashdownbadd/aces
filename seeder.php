<?php
// seeder.php
session_start();
require_once __DIR__ . '/config/db.php';

// Security constraint: Check that an administrative session exists before seeding
if (!isset($_SESSION['user_id'])) {
    die("Access Denied: Please log into the system dashboard panel before running the data seeder script.");
}

try {
    // Start a clean, secure database transaction
    $pdo->beginTransaction();

    // Data Pools for realistic Filipino / general member profiles
    $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Maria', 'James', 'Princess', 'Mark', 'Jessica', 'William', 'Karen', 'Joseph', 'Amanda', 'Andrew', 'Stephanie', 'Ryan', 'Dorothy', 'Jun', 'Rey', 'Maria Clara', 'Danilo'];
    $middleNames = ['Santos', 'Reyes', 'Cruz', 'Dela Cruz', 'Garcia', 'Mendoza', 'Bautista', 'Torres', 'Aquino', 'Ramos'];
    $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Perez', 'Castro'];
    
    $prefixes = ['Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Engr.'];
    $suffixes = ['Jr.', 'III', 'IV', ''];
    $types = ['Regular', 'Associate'];
    
    // Geopolitical address maps
    $places = [
        ['barangay' => 'Barangay 78', 'city' => 'Caloocan', 'province' => 'Metro Manila', 'region' => 'NCR'],
        ['barangay' => 'Barangay 12', 'city' => 'Manila', 'province' => 'Metro Manila', 'region' => 'NCR'],
        ['barangay' => 'San Jose', 'city' => 'Antipolo', 'province' => 'Rizal', 'region' => 'Region IV-A'],
        ['barangay' => 'Malagasang', 'city' => 'Imus', 'province' => 'Cavite', 'region' => 'Region IV-A'],
        ['barangay' => 'Balibago', 'city' => 'Angeles City', 'province' => 'Pampanga', 'region' => 'Region III']
    ];

    $programs = ['BS in Computer Science', 'BS in Business Administration', 'BS in Civil Engineering', 'AB Communication', 'Associate in Hotel Management'];
    $schools = ['University of the Philippines', 'De La Salle University', 'Ateneo de Manila University', 'University of Santo Tomas', 'Polytechnic University of the Philippines'];
    
    $jobs = ['Software Engineer', 'Accountant', 'Project Manager', 'Customer Support Specialist', 'Administrative Assistant', 'Operations Supervisor'];
    $companies = ['TechSolutions Inc.', 'Global Finance Corp', 'Nexus Development', 'Apex Outsourcing', 'Pioneer Enterprises'];

    // 1. Prepare statements for all tables ahead of time
    $stmtMember = $pdo->prepare("INSERT INTO members (member_number, first_name, middle_name, last_name, prefix, suffix, nickname, membership_type, subscription, status, date_of_membership, date_of_birth, remarks) VALUES (:member_number, :first_name, :middle_name, :last_name, :prefix, :suffix, :nickname, :membership_type, :subscription, :status, :date_of_membership, :date_of_birth, :remarks)");
    
    $stmtProfile = $pdo->prepare("INSERT INTO member_profiles (member_id, title_rank, position, tin_no, marital_status, sex, height, weight, complexion, birthplace) VALUES (:member_id, :title_rank, :position, :tin_no, :marital_status, :sex, :height, :weight, :complexion, :birthplace)");
    
    $stmtContact = $pdo->prepare("INSERT INTO member_contact (member_id, phone_no_1, phone_no_2, telephone_no_1, email) VALUES (:member_id, :phone_no_1, :phone_no_2, :telephone_no_1, :email)");
    
    $stmtAddress = $pdo->prepare("INSERT INTO member_addresses (member_id, address_type, house_number, street, barangay, zone, district, town_city, province, region) VALUES (:member_id, 'Permanent', :house_number, :street, :barangay, :zone, :district, :town_city, :province, :region)");
    
    $stmtBeneficiary = $pdo->prepare("INSERT INTO member_beneficiaries (member_id, relation, first_name, last_name, date_of_birth, status) VALUES (:member_id, :relation, :first_name, :last_name, :date_of_birth, 'Active')");
    
    $stmtEducation = $pdo->prepare("INSERT INTO member_education (member_id, program, school_university, location, date_started, date_ended) VALUES (:member_id, :program, :school_university, :location, '2018-06-05', '2022-04-15')");
    
    $stmtExperience = $pdo->prepare("INSERT INTO member_experience (member_id, job_title, organization, date_started, date_ended) VALUES (:member_id, :job_title, :organization, '2022-05-01', '2025-12-30')");

    // Loop to build 50 relational member trees
    for ($i = 1; $i <= 50; $i++) {
        $fName = $firstNames[array_rand($firstNames)];
        $mName = $middleNames[array_rand($middleNames)];
        $lName = $lastNames[array_rand($lastNames)];
        
        // --- 1. INSERT CORE MEMBER ---
        $memberNo = "COOP-2026-" . str_pad($i, 4, "0", STR_PAD_LEFT);
        $birthYear = rand(1080, 2002);
        $dob = "$birthYear-" . str_pad(rand(1, 12), 2, "0", STR_PAD_LEFT) . "-" . str_pad(rand(1, 28), 2, "0", STR_PAD_LEFT);
        
        $stmtMember->execute([
            ':member_number'       => $memberNo,
            ':first_name'          => $fName,
            ':middle_name'         => $mName,
            ':last_name'           => $lName,
            ':prefix'              => $prefixes[array_rand($prefixes)],
            ':suffix'              => $suffixes[array_rand($suffixes)],
            ':nickname'            => $fName . 'ie',
            ':membership_type'     => $types[array_rand($types)],
            ':subscription'        => 'Standard Capital Contribution Option',
            ':status'              => 'active',
            ':date_of_membership'  => '2026-01-15',
            ':date_of_birth'       => $dob,
            ':remarks'             => 'System auto-seeded cooperative member asset profile.'
        ]);
        
        // GET THE AUTO-INCREMENTED ID FOR THIS SPECIFIC MEMBER
        $memberId = $pdo->lastInsertId();

        // --- 2. INSERT MATCHING PROFILE ---
        $stmtProfile->execute([
            ':member_id'      => $memberId,
            ':title_rank'     => 'Associate',
            ':position'       => 'Member Developer',
            ':tin_no'         => rand(100, 999) . '-' . rand(100, 999) . '-' . rand(100, 999) . '-000',
            ':marital_status' => ['Single', 'Married'][rand(0, 1)],
            ':sex'            => ['Male', 'Female'][rand(0, 1)],
            ':height'         => rand(150, 185) . ' cm',
            ':weight'         => rand(50, 90) . ' kg',
            ':complexion'     => ['Fair', 'Tan', 'Light'][rand(0, 2)],
            ':birthplace'     => 'City Public Hospital'
        ]);

        // --- 3. INSERT CONTACT LINK ---
        $stmtContact->execute([
            ':member_id'        => $memberId,
            ':phone_no_1'       => '0917' . rand(1000000, 9999999),
            ':phone_no_2'       => '0922' . rand(1000000, 9999999),
            ':telephone_no_1'   => '(02) 8' . rand(1000000, 9999999),
            ':email'            => strtolower($fName . '.' . $lName . $i . '@example.coop')
        ]);

        // --- 4. INSERT GEOPOLITICAL ADDRESS ---
        $geo = $places[array_rand($places)];
        $stmtAddress->execute([
            ':member_id'    => $memberId,
            ':house_number' => '#' . rand(1, 250),
            ':street'       => 'Rizal Avenue Ext.',
            ':barangay'     => $geo['barangay'],
            ':zone'         => 'Zone ' . rand(1, 10),
            ':district'     => 'District ' . rand(1, 4),
            ':town_city'    => $geo['city'],
            ':province'     => $geo['province'],
            ':region'       => $geo['region']
        ]);

        // --- 5. INSERT BENEFICIARY ---
        $stmtBeneficiary->execute([
            ':member_id'    => $memberId,
            ':relation'     => ['Spouse', 'Child', 'Sibling'][rand(0, 2)],
            ':first_name'   => $firstNames[array_rand($firstNames)],
            ':last_name'    => $lName,
            ':date_of_birth'=> '2015-08-20'
        ]);

        // --- 6. INSERT ACADEMIC HISTORY ---
        $stmtEducation->execute([
            ':member_id'         => $memberId,
            ':program'           => $programs[array_rand($programs)],
            ':school_university' => $schools[array_rand($schools)],
            ':location'          => $geo['city']
        ]);

        // --- 7. INSERT CAREER EXPERIENCE ---
        $stmtExperience->execute([
            ':member_id'    => $memberId,
            ':job_title'    => $jobs[array_rand($jobs)],
            ':organization' => $companies[array_rand($companies)]
        ]);
    }

    // Commit all operations safely together
    $pdo->commit();
    echo "<h2 style='color: green; font-family: Arial;'>Success! 50 Fully Organized Relational Members have been cleanly split and injected into your 7 structural tables!</h2>";
    echo "<p style='font-family: Arial;'><a href='index.php?route=members'>← Return to Cooperative Directory Registry</a></p>";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Relational Seeding aborted due to engineering structure data mismatch error: " . $e->getMessage());
}