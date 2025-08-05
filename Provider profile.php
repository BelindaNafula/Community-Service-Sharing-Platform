<?php
session_start();
include 'db connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'provider') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Fetch provider details
$stmt = $conn->prepare("SELECT full_name, email, phone, location, service_type FROM providers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Profile - CommShare</title>
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

        .logout {
            margin-top: 40px;
            font-size: 14px;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .profile-card {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        .profile-card h2 {
            margin-top: 0;
            color: #68063cff;
        }

        .profile-detail {
            margin-bottom: 15px;
        }

        .profile-detail strong {
            display: inline-block;
            width: 120px;
            color: #333;
        }

        .edit-btn {
            margin-top: 20px;
            background-color: #68063cff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .edit-btn:hover {
            background-color: #4c042b;
        }
    </style>
</head>
<body>

    <!-- Nav Bar -->
    <nav>
        <h1>CommShare Service Provider Dashboard</h1>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Main Container -->
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Provider Panel</h2>
            <a href="view_services.php">📋 View Services</a>
            <a href="provider_profile.php">👤 Profile</a>
            <a href="registered_services.php">✅ Registered Services</a>
            <a href="inbox.php">📬 Inbox</a>
            <div class="logout">
                <a href="../logout.php">🚪 Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="profile-card">
                <h2>My Profile</h2>
                <div class="profile-detail"><strong>Full Name:</strong> <?php echo htmlspecialchars($provider['full_name']); ?></div>
                <div class="profile-detail"><strong>Email:</strong> <?php echo htmlspecialchars($provider['email']); ?></div>
                <div class="profile-detail"><strong>Phone:</strong> <?php echo htmlspecialchars($provider['phone']); ?></div>
                <div class="profile-detail"><strong>Location:</strong> <?php echo htmlspecialchars($provider['location']); ?></div>
                <div class="profile-detail"><strong>Service Type:</strong> <?php echo htmlspecialchars($provider['service_type']); ?></div>

                <form action="edit_profile.php" method="get">
                    <button type="submit" class="edit-btn">Edit Profile</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>


