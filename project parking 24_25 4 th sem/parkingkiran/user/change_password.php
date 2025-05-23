<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$message_class = '';

if (isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($current_password, $user['password'])) {
        $message = "❌ Current password is incorrect.";
        $message_class = "danger";
    } elseif ($new_password !== $confirm_password) {
        $message = "❌ New passwords do not match.";
        $message_class = "warning";
    } elseif (strlen($new_password) < 6) {
        $message = "⚠️ New password must be at least 6 characters.";
        $message_class = "warning";
    } else {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password_hash, $user_id);

        if ($stmt->execute()) {
            $message = "✅ Password changed successfully.";
            $message_class = "success";
        } else {
            $message = "❌ Error changing password.";
            $message_class = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            background: #f2f6fc;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 60px;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 {
            color: #0d6efd;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            border-radius: 12px;
        }
        .btn-back {
            margin-top: 15px;
            display: inline-block;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-lock"></i> Change Password</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_class ?> mt-3"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <button type="submit" name="change_password" class="btn btn-primary w-100"><i class="fas fa-key"></i> Change Password</button>
    </form>

    <a href="dashboard.php" class="btn btn-outline-secondary btn-back w-100 mt-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
