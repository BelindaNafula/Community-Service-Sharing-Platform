<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - CommShare</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
        }

        nav {
            background-color: #68063cff;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            margin: 0;
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

        .container {
            text-align: center;
            padding: 80px 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
        }

        .buttons a {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            background-color: #68063cff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .buttons a:hover {
            background-color: #cc03a0ff;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <h1>CommShare</h1>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<!-- Registration Choice -->
<div class="container">
    <h2>Register As:</h2>
    <div class="buttons">
        <a href="user signup.php">User</a>
        <a href="provider signup.php">Service Provider</a>
    </div>
</div>

</body>
</html>
