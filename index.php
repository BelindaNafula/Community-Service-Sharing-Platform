<?php
// index.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CommShare | Home</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }

        nav {
            background-color: #68063cff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        nav h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            background: #ffffff;
        }

        .hero h2 {
            font-size: 36px;
            color: #333;
        }

        .hero p {
            font-size: 18px;
            color: #666;
            max-width: 600px;
            margin: 20px auto;
        }

        .buttons {
            margin-top: 30px;
        }

        .buttons a {
            display: inline-block;
            margin: 10px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .buttons a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <h1>Community Service Sharing Platform</h1>
    <ul>
        <li><a href="about.php">About</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="signup.php">Sign Up</a></li>
        <li><a href="contact.php">Contact</a></li>


    </ul>
</nav>

<!-- Hero Section -->
<div class="hero">
    <h2>Welcome to CommShare</h2>
    <p>
        A platform that connects users with trusted service providers in their community.
        Whether you're looking for help or offering your skills, CommShare makes it easy to connect and exchange services.
    </p>

</div>

</body>
</html>
