<?php
// Status Constants as mandated by Phase 1/2 Requirements
define('STATUS_ACTIVE', 'ACTIVE');
define('STATUS_ON_HOLD', 'ON_HOLD');
define('STATUS_COMPLETED', 'COMPLETED');
define('STATUS_PENDING', 'PENDING');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'gymnastics_db';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die(json_encode(["error" => "Database connection failed. Please contact the administrator."]));
}
?>