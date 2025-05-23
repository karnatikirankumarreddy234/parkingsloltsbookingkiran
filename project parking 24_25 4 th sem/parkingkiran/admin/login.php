<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed);
    $stmt->fetch();

    if (password_verify($pass, $hashed)) {
        $_SESSION['admin_id'] = $id;
        header("Location: dashboard.php");
    } else {
        echo "Invalid credentials.";
    }
}
?>

<form method="post">
    Username: <input name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Admin Login</button>
</form>
