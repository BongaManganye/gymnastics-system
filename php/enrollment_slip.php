<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM gymnasts WHERE membership_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$gymnast = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$gymnast) {
    die("Gymnast record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollment Slip - <?= htmlspecialchars($gymnast['membership_id']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .slip-container {
            border: 2px dashed #999;
            padding: 32px;
            max-width: 650px;
            margin: 20px auto;
            background: #fff;
        }
        .slip-header {
            text-align: center;
            border-bottom: 2px solid #1976d2;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .slip-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: none; }
            .slip-container { border: 1px solid #000; box-shadow: none; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="slip-header">
            <h2>Gymnastics Academy</h2>
            <h3>Official Enrollment Slip</h3>
        </div>

        <div class="slip-row">
            <span><strong>Membership ID:</strong></span>
            <span><?= htmlspecialchars($gymnast['membership_id']) ?></span>
        </div>
        <div class="slip-row">
            <span><strong>Gymnast Name:</strong></span>
            <span><?= htmlspecialchars($gymnast['full_name']) ?></span>
        </div>
        <div class="slip-row">
            <span><strong>Enrolled Program:</strong></span>
            <span><?= htmlspecialchars($gymnast['training_program']) ?></span>
        </div>
        <div class="slip-row">
            <span><strong>Enrollment Date:</strong></span>
            <span><?= htmlspecialchars($gymnast['enrollment_date']) ?></span>
        </div>
        <div class="slip-row">
            <span><strong>System Timestamp (created_at):</strong></span>
            <span><?= htmlspecialchars($gymnast['created_at']) ?></span>
        </div>
        <div class="slip-row">
            <span><strong>Slip Printed On:</strong></span>
            <span><?= date('Y-m-d H:i:s') ?></span>
        </div>
        <div class="slip-row">
            <span><strong>Membership Status:</strong></span>
            <span class="badge badge-<?= strtolower($gymnast['status']) ?>">
                <?= htmlspecialchars($gymnast['status']) ?>
            </span>
        </div>

        <div style="margin-top: 28px;" class="no-print">
            <button class="btn btn-primary" onclick="window.print()">Print Slip</button>
            <a href="profile.php?id=<?= urlencode($gymnast['membership_id']) ?>" class="btn btn-secondary">Back to Profile</a>
        </div>
    </div>
</body>
</html>