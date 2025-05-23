<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = trim($_POST['user_name']);
$email = trim($_POST['email']);
$vehicle_number = trim($_POST['vehicle_number']);

$message = "";
$type = "";

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "❌ Invalid email format.";
    $type = "danger";
} else {
    $stmt = $conn->prepare("UPDATE users SET user_name = ?, email = ?, vehicle_number = ? WHERE id = ?");
    $stmt->bind_param("sssi", $user_name, $email, $vehicle_number, $user_id);

    if ($stmt->execute()) {
        $message = "✅ Profile updated successfully.";
        $_SESSION['user_name'] = $user_name;
        $type = "success";
    } else {
        $message = "❌ Error updating profile.";
        $type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-card {
            max-width: 500px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            padding: 30px;
            text-align: center;
        }

        .message-card h2 {
            color: #0d6efd;
            font-weight: bold;
        }

        .btn-custom {
            border-radius: 12px;
            margin-top: 20px;
            padding: 10px 20px;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .success .icon {
            color: #28a745;
        }

        .danger .icon {
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="message-card <?= $type ?>">
    <div class="icon">
        <i class="fas <?= $type === 'success' ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
    </div>
    <h2><?= $type === 'success' ? 'Success!' : 'Oops!' ?></h2>
    <p class="lead"><?= htmlspecialchars($message) ?></p>

    <a href="profile.php" class="btn btn-outline-primary btn-custom">
        <i class="fas fa-user-edit"></i> Back to Profile
    </a>

    <a href="dashboard.php" class="btn btn-primary btn-custom">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

</body>
</html>
