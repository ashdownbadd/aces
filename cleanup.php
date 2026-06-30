<?php
// cleanup_all_tables.php
require_once __DIR__ . '/config/db.php';

try {
    // 1. Disable foreign key checks to bypass dependency blocks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 2. Truncate all specified tables
    $tables = [
        'activity_logs',
        'ledger_entries',
        'payment_ledger',
        'loan_schedules',
        'loans',
        'journal_vouchers',
        'member_addresses',
        'member_beneficiaries',
        'member_contact',
        'member_education',
        'member_experience',
        'member_profiles',
        'members'
    ];

    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE $table");
    }

    // 3. Reset the Auto-Increment for members
    $pdo->exec("ALTER TABLE members AUTO_INCREMENT = 1");

    // 4. Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "All specified tables have been cleared successfully.";

} catch (PDOException $e) {
    echo "Error during cleanup: " . $e->getMessage();
}
?>