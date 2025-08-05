<?php
session_start();
require_once "db connection.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php");
    exit;
}

$provider_id = $_SESSION['user_id'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_registration'])) {
    $service_id = intval($_POST['service_id']);
    $delete = $conn->prepare("DELETE FROM service_registrations WHERE provider_id = ? AND service_id = ?");
    $delete->execute([$provider_id, $service_id]);
    $message = "🗑️ Service unregistered successfully.";
}

// Fetch registered services
$stmt = $conn->prepare("
    SELECT sr.service_id, s.name 
    FROM service_registrations sr
    JOIN services s ON sr.service_id = s.id
    WHERE sr.provider_id = ?
    ORDER BY sr.created_at DESC
");
$stmt->execute([$provider_id]);
$registered_services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registered Services - Provider</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
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
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .dashboard-container {
            display: flex;
            height: calc(100vh - 60px);
        }

        .sidebar {
            width: 220px;
            background-color: #1b0210;
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
            padding: 30px;
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

        .service-card form {
            display: inline;
        }

        .service-card button {
            background-color: #68063cff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 3px;
            cursor: pointer;
        }

        .service-card button:hover {
            background-color: #50062e;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<nav>
    <h1>CommShare Service Provider</h1>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<div class="dashboard-container">
    <div class="sidebar">
        <h2>Provider Panel</h2>
        <a href="provider dashboard.php">🏠 Dashboard</a>
        <a href="view_services.php">📋 View Services</a>
        <a href="provider profile.php">📝 Edit Profile</a>
        <a href="registered_services.php">✅ Registered Services</a>
        <a href="inbox.php">📬 Inbox</a>
        <a href="../logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <h2>Registered Services</h2>

        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (count($registered_services) === 0): ?>
            <p>No services registered yet.</p>
        <?php else: ?>
            <?php foreach ($registered_services as $service): ?>
                <div class="service-card">
                    <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                        <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
                        <button type="submit" name="delete_registration">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>


