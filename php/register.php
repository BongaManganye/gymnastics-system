<?php
require_once 'db.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Phase 2 Sanitization
    $fullName = htmlspecialchars(stripslashes(trim($_POST['full_name'] ?? '')));
    $membershipId = htmlspecialchars(stripslashes(trim($_POST['membership_id'] ?? '')));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $contactNo = htmlspecialchars(stripslashes(trim($_POST['contact_no'] ?? '')));
    $dob = trim($_POST['dob'] ?? '');
    $trainingProgram = htmlspecialchars(stripslashes(trim($_POST['training_program'] ?? '')));
    $enrollmentDate = trim($_POST['enrollment_date'] ?? date('Y-m-d'));

    // FR2 Validation Rules
    if (strlen($fullName) < 3) {
        $errors[] = "Full Name must be at least 3 characters.";
    }
    if (!preg_match('/^GYMB-\d{3,}$/', $membershipId)) {
        $errors[] = "Membership ID must match format GYMB-xxx (e.g., GYMB-101).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if (!preg_match('/^[0-9]{10}$/', $contactNo)) {
        $errors[] = "Contact number must be exactly 10 digits.";
    }
    if (!empty($dob)) {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 5) {
            $errors[] = "Gymnast must be at least 5 years old.";
        }
    } else {
        $errors[] = "Date of Birth is required.";
    }

    if (empty($errors)) {
        // FR3: Prepared statements
        $status = STATUS_ACTIVE;
        $stmt = $conn->prepare("INSERT INTO gymnasts (full_name, membership_id, email, contact_no, dob, training_program, enrollment_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $fullName, $membershipId, $email, $contactNo, $dob, $trainingProgram, $enrollmentDate, $status);

        try {
            $stmt->execute();
            $success = "Gymnast registered successfully with status: " . STATUS_ACTIVE;
        } catch (mysqli_sql_exception $e) {
            if ($conn->errno === 1062) {
                $errors[] = "Membership ID or Email already exists.";
            } else {
                $errors[] = "Failed to save record: " . $e->getMessage();
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gymnast Registration</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="card">
    <h2>Register Gymnast</h2>
    
    <?php if (!empty($errors)): ?>
      <div class="snackbar" style="background: #d32f2f;">
        <?php foreach ($errors as $err) echo "<p style='margin:0;'>$err</p>"; ?>
      </div>
    <?php elseif (!empty($success)): ?>
      <div class="snackbar" style="background: #388e3c;"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="full_name" required placeholder="John Doe">
      </div>
      <div class="form-group">
        <label>Membership ID</label>
        <input type="text" name="membership_id" required placeholder="GYMB-101">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="john@example.com">
      </div>
      <div class="form-group">
        <label>Contact Number (10 Digits)</label>
        <input type="tel" name="contact_no" required placeholder="0821234567">
      </div>
      <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" name="dob" required>
      </div>
      <div class="form-group">
        <label>Training Program</label>
        <select name="training_program" required>
          <option value="Beginner">Beginner</option>
          <option value="Intermediate">Intermediate</option>
          <option value="Advanced">Advanced</option>
        </select>
      </div>
      <div class="form-group">
        <label>Enrollment Date</label>
        <input type="date" name="enrollment_date" value="<?= date('Y-m-d') ?>" required>
      </div>
      <button type="submit" class="btn">Register</button>
    </form>
  </div>
</body>
</html>