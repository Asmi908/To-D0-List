<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="../public/css/authStyle.css">
<style>
    .toggle-password {
        position: relative;
        margin-top: 10px;
    }
    .toggle-password input[type="checkbox"] {
        margin-right: 5px;
    }
    .toggle-password label {
        font-size: 14px;
        color: #333;
    }
</style>
</head>
<body>
<div class="container">
    <img src="../public/css/userImage/logo.jpg" alt="Logo" class="logo">
    <h2>Login</h2>
    <form id="loginForm" action="?url=auth/login" method="POST" onsubmit="return validateLoginForm()">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <div class="toggle-password">
            <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()">
            <label for="showPassword">Show Password</label>
        </div>
        <button type="submit">Login</button>
    </form>
 
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
 
    if (isset($_SESSION['message'])) {
        echo "<p style='color: green'>" . htmlspecialchars($_SESSION['message']) . "</p>";
        unset($_SESSION['message']);
    }
 
    if (isset($error)) {
        echo "<p style='color:red'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
 
    <p>Don't have an account? <a href="?url=auth/register">Register here</a></p>
    <p><a href="?url=auth/forgotPassword">Forgot Password?</a></p>
</div>
 
<script>
    function validateLoginForm() {
        const email = document.forms["loginForm"]["email"].value;
        const password = document.forms["loginForm"]["password"].value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
 
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }
        if (password.length < 8) {
            alert("Password must be at least 8 characters long.");
            return false;
        }
        return true;
    }
 
    function togglePasswordVisibility() {
        const passwordField = document.getElementById("password");
        const showPassword = document.getElementById("showPassword");
        passwordField.type = showPassword.checked ? "text" : "password";
    }
</script>
</body>
</html>