<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
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
 
<h2>Register</h2>
<form id="registerForm" action="?url=auth/register" method="POST" onsubmit="return validateRegisterForm()">
<input type="text" name="username" placeholder="Username" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" id="password" placeholder="Password" required>
<div class="toggle-password">
<input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()">
<label for="showPassword">Show Password</label>
</div>
<button type="submit">Register</button>
</form>
<?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
  <p>Already have an account? <a href="?url=auth/login">Login</a></p>
</div>
 
    <script>
 
        function validateRegisterForm() {
 
            const username = document.forms["registerForm"]["username"].value;
 
            const email = document.forms["registerForm"]["email"].value;
 
            const password = document.forms["registerForm"]["password"].value;
 
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
 
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
 
            if (username.length < 3) {
 
                alert("Username must be at least 3 characters long.");
 
                return false;
 
            }
 
            if (!emailRegex.test(email)) {
 
                alert("Please enter a valid email address.");
 
                return false;
 
            }
 
            if (!passwordRegex.test(password)) {
 
                alert("Password must be at least 8 characters long and contain at least one letter and one number.");
 
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
 
 
 