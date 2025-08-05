<?php
session_start();
require_once "db connection.php";

// Restrict access to admin only
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit;
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
    header("Location: manage_users.php");
    exit;
}

// Fetch all users
$stmt = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Users - Admin</title>
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

        table {
            width: 100%;
            margin-top: 20px;
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
        <h2>All Registered Users</h2>
        <h3>Manage Users</h3>

        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th># User ID</th>
                        <th>Username</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            
                            
                            <td>
                                <a class="action-btn delete-btn" href="?delete=<?php echo $user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
