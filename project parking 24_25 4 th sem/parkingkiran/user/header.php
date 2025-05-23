<?php
// Start session and connect to DB
session_start();

// Redirect if not logged in as user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// HTML head + Bootstrap CSS and FontAwesome CDN
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard - Parking Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

<style>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f9ff;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .dashboard-links {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            justify-content: center;
        }

        .dashboard-links a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .dashboard-links a:hover {
            background-color: #084298;
            text-decoration: none;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    
</style>

</head>
<body>
<div class="dashboard-links mb-4">
    <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
    <a href="booking_history.php"><i class="fas fa-clock-rotate-left"></i> Booking History</a>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Parking Booking - User</a>
        <div class="ms-auto">
            <a href="../logout.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>
<div class="container">
<?php
// header ends, dashboard content starts in dashboard.php
