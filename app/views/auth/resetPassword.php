<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
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
<h2>Reset Password</h2>
<form id="resetPasswordForm" action="?url=auth/resetPassword" method="POST" onsubmit="return validateResetPasswordForm()">
<input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['reset_email'] ?? ''); ?>">
<input type="password" name="password" id="password" placeholder="New Password" required>
<div class="toggle-password">
<input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()">
<label for="showPassword">Show Password</label>
</div>
<button type="submit">Reset Password</button>
</form>
<?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
<?php if (isset($message)) { 
    echo "<p style='color:green'>Password reset successfully. <a href='?url=auth/login'>Go to Login</a></p>"; 
} ?>
<p><a href="?url=auth/forgotPassword">Back to Forgot Password</a></p>
</div>
<script>
    function validateResetPasswordForm() {
        const password = document.getElementById("password").value;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
 
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