 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CommShare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(239, 247, 248, 1);
            margin: 0;
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
            font-size: 22px;
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
            max-width: 400px;
            margin: 80px auto;
            background: beige;
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
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #68063c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #58052f;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Nav Bar -->
<nav>
    <h1>CommShare</h1>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="signup.php">Sign Up</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<!-- Login Form -->
<div class="container">
    <h2>Login</h2>

    <form action="login_handler.php" method="POST">
        <select name="role" required>
            <option value="">Login as...</option>
            <option value="user">User</option>
            <option value="provider">Service Provider</option>
            <option value="admin">Admin</option>
        </select>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>



