<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<link rel="stylesheet" href="../public/css/authStyle.css">
</head>
<body>
<div class="container">
<img src="../public/css/userImage/logo.jpg" alt="Logo" class="logo">
<h2>Forgot Password</h2>
<form id="forgotPasswordForm" action="?url=auth/forgotPassword" method="POST" onsubmit="return validateForgotPasswordForm()">
<input type="email" name="email" placeholder="Email" required>
<button type="submit">Next</button>
</form>
<?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>
<p><a href="?url=auth/login">Back to Login</a></p>
</div>
<script>
        function validateForgotPasswordForm() {
            const email = document.forms["forgotPasswordForm"]["email"].value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            return true;
        }
</script>
</body>
</html>