<?php
// cron_update_overdue.php
define('ALLOW_ACCESS', true);

// 1. Establish database context configuration
require_once __DIR__ . '/config/db.php';

try {
    echo "Starting automated loan status and penalty assessment matrix...\n";
    
    // 2. Begin a safe database transactional state
    $pdo->beginTransaction();

    // Query all pending rows where the due date is strictly older than today
    $checkSql = "SELECT id, loan_id, rem_principal FROM loan_schedules WHERE status = 'Pending' AND due_date < CURDATE()";
    $overdueItems = $pdo->query($checkSql)->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    if (!empty($overdueItems)) {
        // Prepare the update statement to apply late fee tracking values
        $updateSql = "UPDATE loan_schedules 
                      SET status = 'overdue', 
                          orig_penalty = rem_principal * 0.03, 
                          rem_penalty = rem_principal * 0.03 
                      WHERE id = ?";
        $stmt = $pdo->prepare($updateSql);

        foreach ($overdueItems as $item) {
            $stmt->execute([$item['id']]);
            $count++;
        }
    }

    $pdo->commit();
    echo "Success: Updated {$count} schedule lines to OVERDUE status with a 3% late penalty charge applied.\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Automated calculation job aborted due to exception context: " . $e->getMessage() . "\n");
}