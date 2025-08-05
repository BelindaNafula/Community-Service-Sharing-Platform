<?php
session_start();
require_once "db connection.php";

// Restrict access to admin only
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit;
}

// Handle provider deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM service_registrations WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_providers.php");
    exit;
}

// Fetch all services with providers
$stmt = $conn->query("
    SELECT s.id AS service_id, s.name AS service_name, sr.*
    FROM services s
    LEFT JOIN service_registrations sr ON s.id = sr.service_id
    ORDER BY s.name ASC, sr.first_name ASC
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group providers by service
$groupedProviders = [];
foreach ($rows as $row) {
    $serviceName = $row['service_name'] ?? 'Unknown Service';
    if (!isset($groupedProviders[$serviceName])) {
        $groupedProviders[$serviceName] = [];
    }
    if (!empty($row['id'])) {
        $groupedProviders[$serviceName][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Providers - Admin</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4ff;
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
            background-color: #68063cff;
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
            background-color: #07b1bdff;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .main-content h2 {
            color: #333;
        }

        .service-block {
            margin-bottom: 40px;
        }

        .service-block h3 {
            color: #444;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            background: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav>
    <h1>CommShare Admin</h1>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<!-- Dashboard Layout -->
<div class="dashboard-container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin dashboard.php">🏠 Dashboard</a>
        <a href="manage_services.php">📋 Manage Services</a>
        <a href="manage_users.php">👥 Manage Users</a>
        <a href="manage_providers.php">🛠️ Manage Providers</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <h2>All Registered Providers</h2>
        <h3>Grouped by Service</h3>

        <?php if (empty($groupedProviders)): ?>
            <p>No providers registered yet.</p>
        <?php else: ?>
            <?php foreach ($groupedProviders as $service => $providers): ?>
                <div class="service-block">
                    <h3>🔧 <?php echo htmlspecialchars($service); ?></h3>

                    <table>
                        <thead>
                            <tr>
                                <th># ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Experience</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($providers as $p): ?>
                                <tr>
                                    <td><?php echo $p['id']; ?></td>
                                    <td><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['second_name']); ?></td>
                                    <td><?php echo htmlspecialchars($p['location']); ?></td>
                                    <td><?php echo htmlspecialchars($p['contact']); ?></td>
                                    <td><?php echo htmlspecialchars($p['email']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($p['experience'])); ?></td>
                                    <td>
                                        <a class="action-btn delete-btn" href="?delete=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this provider?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
