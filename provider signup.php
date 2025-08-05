<?php
// Provider registration form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Provider Registration - CommShare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        .container {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #45a049;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Service Provider Registration</h2>
    <form action="provider signup back.php" method="POST">
        <input type="hidden" name="role" value="provider">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="submit" value="Register">
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html>
