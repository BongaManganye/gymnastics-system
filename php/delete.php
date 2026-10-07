<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    // 1. Fetch record for audit logging
    $stmt = $conn->prepare("SELECT * FROM gymnasts WHERE membership_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        // 2. Perform deletion
        $del_stmt = $conn->prepare("DELETE FROM gymnasts WHERE membership_id = ?");
        $del_stmt->bind_param("s", $id);
        $del_stmt->execute();
        $del_stmt->close();

        // 3. Write to deleted_log.txt
        $log_entry = date('Y-m-d H:i:s') . " - " . json_encode($row) . PHP_EOL;
        $log_dir = __DIR__ . '/../logs';
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true);
        }
        file_put_contents($log_dir . '/deleted_log.txt', $log_entry, FILE_APPEND);
    }
}

header('Location: dashboard.php');
exit;
?>