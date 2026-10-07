<?php
require_once 'db.php';

$errors = [];
$success = '';
$membership_id = htmlspecialchars(stripslashes(trim($_GET['id'] ?? '')));

if (empty($membership_id)) {
    header('Location: dashboard.php');
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = htmlspecialchars(stripslashes(trim($_POST['full_name'] ?? '')));
    $email = htmlspecialchars(stripslashes(trim($_POST['email'] ?? '')));
    $contact_no = htmlspecialchars(stripslashes(trim($_POST['contact_no'] ?? '')));
    $dob = trim($_POST['dob'] ?? '');
    $training_program = trim($_POST['training_program'] ?? '');
    $status = trim($_POST['status'] ?? STATUS_ACTIVE);

    // Validation
    if (strlen($full_name) < 3) {
        $errors[] = 'Full Name must be at least 3 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address format.';
    }
    if (!preg_match('/^[0-9]{10}$/', $contact_no)) {
        $errors[] = 'Contact number must be exactly 10 digits.';
    }

    $valid_programs = ['Beginner', 'Intermediate', 'Advanced'];
    if (!in_array($training_program, $valid_programs, true)) {
        $errors[] = 'Invalid training program selected.';
    }

    $valid_statuses = [STATUS_ACTIVE, STATUS_ON_HOLD, STATUS_COMPLETED, STATUS_PENDING];
    if (!in_array($status, $valid_statuses, true)) {
        $errors[] = 'Invalid status selected.';
    }

    if (empty($errors)) {
        $update_stmt = $conn->prepare(
            "UPDATE gymnasts 
             SET full_name = ?, email = ?, contact_no = ?, dob = ?, training_program = ?, status = ? 
             WHERE membership_id = ?"
        );
        $update_stmt->bind_param("sssssss", $full_name, $email, $contact_no, $dob, $training_program, $status, $membership_id);

        if ($update_stmt->execute()) {
            $success = "Gymnast profile updated successfully!";
        } else {
            $errors[] = "Failed to update record.";
        }
        $update_stmt->close();
    }
}

// Fetch Current Record
$stmt = $conn->prepare("SELECT * FROM gymnasts WHERE membership_id = ?");
$stmt->bind_param("s", $membership_id);
$stmt->execute();
$gymnast = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$gymnast) {
    die("Gymnast not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Gymnast - <?= htmlspecialchars($gymnast['membership_id']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="material-card">
        <h2>Edit Gymnast: <?= htmlspecialchars($gymnast['membership_id']) ?></h2>

        <?php if (!empty($errors)): ?>
            <div class="snackbar error"><?= implode('<br>', $errors) ?></div>
        <?php elseif ($success): ?>
            <div class="snackbar success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="edit.php?id=<?= urlencode($membership_id) ?>">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($gymnast['full_name']) ?>" required minlength="3">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($gymnast['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Contact Number (10 digits)</label>
                <input type="tel" name="contact_no" value="<?= htmlspecialchars($gymnast['contact_no']) ?>" required pattern="[0-9]{10}">
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?= htmlspecialchars($gymnast['dob']) ?>" required>
            </div>
            <div class="form-group">
                <label>Training Program</label>
                <select name="training_program" required>
                    <option value="Beginner" <?= $gymnast['training_program'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="Intermediate" <?= $gymnast['training_program'] === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="Advanced" <?= $gymnast['training_program'] === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="<?= STATUS_ACTIVE ?>" <?= $gymnast['status'] === STATUS_ACTIVE ? 'selected' : '' ?>><?= STATUS_ACTIVE ?></option>
                    <option value="<?= STATUS_ON_HOLD ?>" <?= $gymnast['status'] === STATUS_ON_HOLD ? 'selected' : '' ?>><?= STATUS_ON_HOLD ?></option>
                    <option value="<?= STATUS_COMPLETED ?>" <?= $gymnast['status'] === STATUS_COMPLETED ? 'selected' : '' ?>><?= STATUS_COMPLETED ?></option>
                    <option value="<?= STATUS_PENDING ?>" <?= $gymnast['status'] === STATUS_PENDING ? 'selected' : '' ?>><?= STATUS_PENDING ?></option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>