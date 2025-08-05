<?php
session_start();
require_once "db connection.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Fetch all available services
$stmt = $conn->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Available Services - User</title>
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
            height: calc(100vh - 60px);
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

        .main-content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .service-card {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .service-card h3 {
            margin: 0 0 10px;
        }

        .service-card a.button {
            display: inline-block;
            background-color: #68063cff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }

        .service-card a.button:hover {
            background-color: #50062e;
        }
    </style>
</head>
<body>

    <!-- Top Nav -->
    <nav>
        <h1>CommShare User Dashboard</h1>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Sidebar + Content -->
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>User Panel</h2>
            <a href="dashboard user.php">🏠 Dashboard</a>
            <a href="user_view_services.php">📋 View Services</a>
            <a href="user_profile.php">👤 Profile</a>
            <div class="logout">
                <a href="../logout.php">🚪 Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h2>Available Services</h2>

            <?php if (empty($services)): ?>
                <p>No services available at the moment.</p>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($service['description'] ?? '')); ?></p>
                        <a class="button" href="view_providers.php?service_id=<?php echo $service['id']; ?>">
                            🔍 View Service Providers
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>








