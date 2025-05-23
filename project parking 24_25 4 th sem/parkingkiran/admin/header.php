<?php
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="style.css" />
<style>
  /* Gradient animated navbar background */
  .navbar {
    background: linear-gradient(270deg,rgb(75, 158, 194),rgb(211, 148, 12),rgb(44, 143, 193));
    background-size: 600% 600%;
    animation: gradientAnimation 20s ease infinite;
  }

  @keyframes gradientAnimation {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
  }

  /* Moving credit text */
  .credit-text {
    overflow: hidden;
    white-space: nowrap;
    box-sizing: border-box;
  }
  .credit-text span {
    display: inline-block;
    padding-left: 100%;
    animation: moveText 10s linear infinite;
    font-weight: 500;
    color:rgb(21, 137, 32);
  }
  @keyframes moveText {
    0% {
      transform: translateX(0%);
    }
    100% {
      transform: translateX(-100%);
    }
  }

  /* Navbar brand style */
  .navbar-brand {
    font-weight: 700;
    font-size: 1.6rem;
    letter-spacing: 1px;
  }

  /* Logout button style */
  .btn-logout {
    border: 2px solid #f8f9fa;
    font-weight: 600;
    transition: background-color 0.3s, color 0.3s;
  }
  .btn-logout:hover {
    background-color:rgb(39, 114, 189);
    color:rgb(43, 230, 18);
  }
</style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4">
  <a class="navbar-brand" href="dashboard.php">
    <i class="fas fa-tachometer-alt me-2"></i> Admin Panel
  </a>
  
  <div class="credit-text mx-auto d-none d-md-block" style="max-width: 300px;">
    <span>Made by Kiran Kumar Reddy Karnati &nbsp; • &nbsp; Welcome to the Admin Dashboard &nbsp; • &nbsp; Enjoy managing your system!</span>
  </div>
  
  <div>
    <a href="logout.php" class="btn btn-sm btn-outline-light btn-logout">
      <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
  </div>
</nav>

<div class="container mt-4">
