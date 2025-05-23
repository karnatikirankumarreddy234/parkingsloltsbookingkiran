<?php
include 'includes/db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate strong password
    if (!preg_match("/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W]).{8,}$/", $password)) {
        $error = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed, $role);

            if ($stmt->execute()) {
                header("Location: index.php?registered=1");
                exit();
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Create an Account</h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="POST" onsubmit="return validateForm();">
        <input type="text" name="username" placeholder="Choose a username" required><br>
        <input type="email" name="email" placeholder="Enter your email" required><br>

        <input type="password" id="password" name="password" placeholder="Create a password" required>
        <input type="checkbox" onclick="togglePassword()"> Show Password<br>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit">Register</button>
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </form>
</div>

<script>
function togglePassword() {
    let pwd = document.getElementById("password");
    pwd.type = pwd.type === "password" ? "text" : "password";
}

function validateForm() {
    const pwd = document.getElementById("password").value;
    const strongRegex = /^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W]).{8,}$/;

    if (!strongRegex.test(pwd)) {
        alert("Password must be at least 8 characters, include uppercase, lowercase, number, and special character.");
        return false;
    }
    return true;
}
</script>
</body>
</html>
