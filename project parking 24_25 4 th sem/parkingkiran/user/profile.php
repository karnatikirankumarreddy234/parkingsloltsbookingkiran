<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT user_name, email, vehicle_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .profile-container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.1);
        }

        h2, h3 {
            color: #0d6efd;
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 10px 18px;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
        }

        label {
            margin-top: 15px;
            font-weight: 500;
        }

        hr {
            margin-top: 35px;
        }
    </style>
</head>
<body>

<div class="container profile-container">
    <h2><i class="fas fa-user-circle"></i> Your Profile</h2>

    <!-- Update Profile -->
    <form method="post" action="update_profile.php" class="mt-4">
        <label for="user_name">Name:</label>
        <input type="text" id="user_name" name="user_name" class="form-control" value="<?= htmlspecialchars($user['user_name']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="vehicle_number">Vehicle Number:</label>
        <input type="text" id="vehicle_number" name="vehicle_number" class="form-control" value="<?= htmlspecialchars($user['vehicle_number']) ?>" pattern="[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}" required title="Format: KA01AB1234">

        <button type="submit" name="update_profile" class="btn btn-primary btn-custom mt-3 w-100">
            <i class="fas fa-save"></i> Update Profile
        </button>
    </form>

    <hr>

    <!-- Change Password -->
    <h3><i class="fas fa-key"></i> Change Password</h3>
    <form method="post" action="change_password.php" class="mt-3">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" class="form-control" required>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" class="form-control" required>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>

        <button type="submit" name="change_password" class="btn btn-warning btn-custom mt-3 w-100">
            <i class="fas fa-lock"></i> Change Password
        </button>
    </form>

    <!-- Back to Dashboard -->
    <a href="dashboard.php" class="btn btn-outline-secondary back-btn w-100 mt-4">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
