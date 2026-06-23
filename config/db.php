<?php
// Database configuration credentials
$host     = 'localhost';
$db_name  = 'integrated_system';
$username = 'root';
$password = ''; // Default XAMPP MySQL password is empty
$charset  = 'utf8mb4';

// Set up the Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

// Configuration options for secure and efficient database operations
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws exceptions on SQL errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetches data as clean associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Uses actual prepared statements for security
];

try {
    // Create the secure PDO instance
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // In production, never echo $e->getMessage() as it reveals system secrets. 
    // For local development, it helps us debug quickly.
    die("Database connection failed: " . $e->getMessage());
}
