<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "integrated_system";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$firstNames = [
    "John",
    "Michael",
    "James",
    "Robert",
    "David",
    "Daniel",
    "Joseph",
    "Mark",
    "Anthony",
    "Joshua",
    "Kevin",
    "Brian",
    "Paul",
    "Ryan",
    "Nathan",
    "Angela",
    "Maria",
    "Christine",
    "Rose",
    "Grace",
    "Nicole",
    "Patricia",
    "Jessica",
    "Karen",
    "Michelle"
];

$middleNames = [
    "Santos",
    "Reyes",
    "Garcia",
    "Torres",
    "Lopez",
    "Cruz",
    "Dela Cruz",
    "Bautista",
    "Mendoza",
    "Flores"
];

$lastNames = [
    "Santos",
    "Reyes",
    "Garcia",
    "Torres",
    "Lopez",
    "Cruz",
    "Mendoza",
    "Flores",
    "Aquino",
    "Ramos",
    "Diaz",
    "Castro",
    "Navarro",
    "Villanueva",
    "Fernandez"
];

$barangays = [
    "San Jose",
    "Poblacion",
    "Sta. Cruz",
    "San Roque",
    "Bagumbayan",
    "Mabini",
    "Balibago",
    "Maligaya"
];

$cities = [
    "Manila",
    "Quezon City",
    "Pasig City",
    "Makati City",
    "Taguig City",
    "Caloocan City",
    "Parañaque City",
    "Las Piñas City",
];

$schools = [
    "University of the Philippines",
    "De La Salle University",
    "Ateneo de Manila University",
    "Polytechnic University of the Philippines",
    "Far Eastern University",
    "University of Santo Tomas"
];

$programs = [
    "BS Computer Science",
    "BS Information Technology",
    "BS Information Systems",
    "BS Civil Engineering",
    "BS Accountancy",
    "BS Nursing"
];

$jobs = [
    "Software Developer",
    "Web Developer",
    "Teacher",
    "Project Manager",
    "Network Engineer",
    "Business Analyst",
    "Accountant",
    "HR Officer"
];

$organizations = [
    "ABC Corporation",
    "XYZ Technologies",
    "Metro Solutions",
    "GlobalTech",
    "Innovate Inc.",
    "Prime Builders",
    "Future Systems"
];

$complexions = [
    "Fair",
    "Light",
    "Medium",
    "Morena"
];

$relations = [
    "Father",
    "Mother",
    "Brother",
    "Sister",
    "Spouse"
];

for ($i = 1; $i <= 25; $i++) {

    $first = $firstNames[array_rand($firstNames)];
    $middle = $middleNames[array_rand($middleNames)];
    $last = $lastNames[array_rand($lastNames)];

    $sex = rand(0, 1) ? "Male" : "Female";

    if ($sex == "Female") {
        $first = ["Angela", "Maria", "Christine", "Rose", "Grace", "Nicole", "Patricia", "Jessica", "Karen", "Michelle"][array_rand(["Angela", "Maria", "Christine", "Rose", "Grace", "Nicole", "Patricia", "Jessica", "Karen", "Michelle"])];
    }

    $memberNo = str_pad($i, 4, "0", STR_PAD_LEFT);

    $membership = rand(0, 1) ? "Regular" : "Associate";

    $subscription = rand(0, 1) ? "Active" : "Inactive";

    $status = rand(0, 1) ? "Active" : "Inactive";

    $dob = date("Y-m-d", strtotime("-" . rand(22, 60) . " years"));

    $dom = date("Y-m-d", strtotime("-" . rand(1, 10) . " years"));

    $nickname = substr($first, 0, 3);

    $remarks = "Seed Data";

    $stmt = $conn->prepare("
        INSERT INTO members
        (
            member_number,
            first_name,
            middle_name,
            last_name,
            prefix,
            suffix,
            nickname,
            membership_type,
            subscription,
            status,
            date_of_membership,
            date_of_birth,
            date_of_death,
            remarks,
            created_at,
            updated_at
        )
        VALUES
        (?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())
    ");

    $death = NULL;
    $prefix = "";
    $suffix = "";

    $stmt->bind_param(
        "ssssssssssssss",
        $memberNo,
        $first,
        $middle,
        $last,
        $prefix,
        $suffix,
        $nickname,
        $membership,
        $subscription,
        $status,
        $dom,
        $dob,
        $death,
        $remarks
    );

    $stmt->execute();

    $member_id = $conn->insert_id;

    // ADDRESS

    $house = rand(1, 999);
    $street = "Sample Street";
    $barangay = $barangays[array_rand($barangays)];
    $zone = rand(1, 10);
    $district = "District " . rand(1, 6);
    $city = $cities[array_rand($cities)];

    $conn->query("
        INSERT INTO member_addresses
        (
            member_id,
            address_type,
            house_number,
            street,
            barangay,
            zone,
            district,
            town_city,
            province,
            region
        )
        VALUES
        (
            '$member_id',
            'Home',
            '$house',
            '$street',
            '$barangay',
            '$zone',
            '$district',
            '$city',
            'Metro Manila',
            'NCR'
        )
    ");

    // BENEFICIARY

    $bfirst = $firstNames[array_rand($firstNames)];
    $bmiddle = $middleNames[array_rand($middleNames)];
    $blast = $lastNames[array_rand($lastNames)];

    $bdob = date("Y-m-d", strtotime("-" . rand(15, 65) . " years"));

    $relation = $relations[array_rand($relations)];

    $conn->query("
        INSERT INTO member_beneficiaries
        (
            member_id,
            relation,
            first_name,
            middle_name,
            last_name,
            prefix,
            suffix,
            date_of_birth,
            place_of_birth,
            status
        )
        VALUES
        (
            '$member_id',
            '$relation',
            '$bfirst',
            '$bmiddle',
            '$blast',
            '',
            '',
            '$bdob',
            '$city',
            'Active'
        )
    ");

    // CONTACT

    $phone = "09" . rand(10, 99) . rand(1000000, 9999999);

    $email = strtolower($first) . "." . $last . $i . "@gmail.com";

    $conn->query("
        INSERT INTO member_contact
        (
            member_id,
            phone_no_1,
            phone_no_2,
            telephone_no_1,
            telephone_no_2,
            email
        )
        VALUES
        (
            '$member_id',
            '$phone',
            '',
            '',
            '',
            '$email'
        )
    ");

    // EDUCATION

    $school = $schools[array_rand($schools)];
    $program = $programs[array_rand($programs)];

    $start = rand(2000, 2015) . "-06-01";
    $end = rand(2016, 2023) . "-04-30";

    $conn->query("
        INSERT INTO member_education
        (
            member_id,
            program,
            school_university,
            location,
            date_started,
            date_ended
        )
        VALUES
        (
            '$member_id',
            '$program',
            '$school',
            '$city',
            '$start',
            '$end'
        )
    ");

    // EXPERIENCE

    $job = $jobs[array_rand($jobs)];
    $org = $organizations[array_rand($organizations)];

    $jobStart = rand(2015, 2020) . "-01-01";
    $jobEnd = date("Y-m-d");

    $conn->query("
        INSERT INTO member_experience
        (
            member_id,
            job_title,
            organization,
            date_started,
            date_ended
        )
        VALUES
        (
            '$member_id',
            '$job',
            '$org',
            '$jobStart',
            '$jobEnd'
        )
    ");

    // PROFILE

    $height = rand(150, 190);
    $weight = rand(50, 90);
    $complexion = $complexions[array_rand($complexions)];

    $marital = rand(0, 1) ? "Single" : "Married";

    $tin = rand(100, 999) . "-" . rand(100, 999) . "-" . rand(100, 999);

    $conn->query("
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
        VALUES
        (
            '$member_id',
            '',
            '$job',
            '$tin',
            '$marital',
            '$sex',
            '$height',
            '$weight',
            '$complexion',
            '$city'
        )
    ");
}

echo "25 members successfully seeded.";

$conn->close();
