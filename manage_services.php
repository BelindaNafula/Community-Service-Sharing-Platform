<?php
session_start();
require_once "db connection.php";

// Only admin allowed
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php');
    exit;
}

$success = "";
$error = "";

// Handle adding new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $serviceName = trim($_POST['service_name']);
    if (!empty($serviceName)) {
        try {
            $stmt = $conn->prepare("INSERT INTO services (name) VALUES (?)");
            $stmt->execute([$serviceName]);
            $success = "Service added successfully.";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Service name cannot be empty.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_services.php");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $id = intval($_POST['service_id']);
    $name = trim($_POST['service_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE services SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
        header("Location: manage_services.php");
        exit;
    } else {
        $error = "Updated name cannot be empty.";
    }
}

// Fetch all services
$stmt = $conn->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if editing
$editing = false;
$editService = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$editId]);
    $editService = $stmt->fetch(PDO::FETCH_ASSOC);
    $editing = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Services - Admin</title>
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

        form input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            background-color: #68063cff;
            color: white;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #68063cff;
        }

        .success { color: green; margin-top: 10px; }
        .error { color: red; margin-top: 10px; }

        table {
            width: 100%;
            margin-top: 30px;
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

        a.action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
        }

        a.edit-btn {
            background-color: #2196F3;
        }

        a.delete-btn {
            background-color: #f44336;
        }

        a.edit-btn:hover {
            background-color: #0b7dda;
        }

        a.delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<nav>
    <h1>CommShare Admin</h1>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

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
        <h2><?php echo $editing ? "Edit Service" : "Add New Service"; ?></h2>

        <form method="POST">
            <?php if ($editing): ?>
                <input type="hidden" name="service_id" value="<?php echo $editService['id']; ?>">
                <input type="text" name="service_name" value="<?php echo htmlspecialchars($editService['name']); ?>" required>
                <input type="submit" name="edit_service" value="Update Service">
            <?php else: ?>
                <input type="text" name="service_name" placeholder="Enter new service" required>
                <input type="submit" name="add_service" value="Add Service">
            <?php endif; ?>
        </form>

        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <h3>All Services</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo $service['id']; ?></td>
                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                        <td>
                            <a class="action-btn edit-btn" href="?edit=<?php echo $service['id']; ?>">Edit</a>
                            <a class="action-btn delete-btn" href="?delete=<?php echo $service['id']; ?>" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

