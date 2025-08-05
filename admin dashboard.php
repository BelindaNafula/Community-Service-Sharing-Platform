<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>ADMIN Dashboard - CommShare</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3edf0ff;
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

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
        }

        .nav-links li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links li a:hover {
            text-decoration: underline;
        }

        .dashboard-container {
            display: flex;
            height: calc(100vh - 60px); /* minus navbar height */
        }

        .sidebar {
            width: 220px;
            background-color: #1b0210ff;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #4c042b;
        }

        .logout {
            margin-top: 40px;
            font-size: 14px;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .main-content h1 {
            color: #333;
        }

        .main-content p {
            color: #555;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <!-- Top Nav Bar -->
    <nav>
        <h1>CommShare Admin Dashboard</h1>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Sidebar + Content -->
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="manage_services.php">📋 Manage Services</a>
            <a href="manage_users.php">👥 Manage Users</a>
            <a href="manage_providers.php">🛠️ Manage Providers</a>
            <div class="logout">
                <a href="../logout.php">🚪 Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h1>Welcome,  <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Select a section from the left menu to manage the platform.</p>
        </div>
    </div>

</body>
</html>


