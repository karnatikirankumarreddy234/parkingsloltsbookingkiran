<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo "<p style='color: green;'>Successfully logged out.</p>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            redirectDashboard($role);
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="form-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <input type="checkbox" onclick="togglePassword()"> Show Password<br>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register Now</a></p>
    </form>
</div>
<script>
function togglePassword() {
    let pwd = document.getElementById("password");
    pwd.type = pwd.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
