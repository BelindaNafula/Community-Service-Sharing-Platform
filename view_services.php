<?php
session_start();
require_once "db connection.php"; // ✅ FIXED this line

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php");
    exit;
}

// Fetch available services
$stmt = $conn->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_registration'])) {
    $provider_id = $_SESSION['user_id'];
    $service_id = intval($_POST['service_id']);
    $first_name = trim($_POST['first_name']);
    $second_name = trim($_POST['second_name']);
    $location = trim($_POST['location']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $experience = trim($_POST['experience']);

    // Check if already registered
    $check = $conn->prepare("SELECT * FROM service_registrations WHERE provider_id = ? AND service_id = ?");
    $check->execute([$provider_id, $service_id]);

    if ($check->rowCount() === 0) {
        $register = $conn->prepare("
            INSERT INTO service_registrations 
            (provider_id, service_id, first_name, second_name, location, contact, email, experience) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $success = $register->execute([
            $provider_id, $service_id, $first_name, $second_name,
            $location, $contact, $email, $experience
        ]);

        if ($success) {
            $message = "✅ Registered successfully!";
        } else {
            $message = "❌ Registration failed. Please try again.";
        }
    } else {
        $message = "⚠️ You are already registered under this service.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Available Services - Provider</title>
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

        .service-card form {
            margin-top: 10px;
        }

        .service-card input,
        .service-card textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
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
        <a href="provider profile.php">👤 Profile</a>
        <a href="registered_services.php">✅ Registered Services</a>
        <a href="inbox.php">📬 Inbox</a>
        <a href="../logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <h2>Available Services</h2>

        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php foreach ($services as $service): ?>
            <div class="service-card">
                <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                <button onclick="toggleForm(<?php echo $service['id']; ?>)">Register</button>

                <div id="form-<?php echo $service['id']; ?>" style="display:none;">
                    <form method="POST">
                        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">

                        <label>First Name:</label>
                        <input type="text" name="first_name" required>

                        <label>Second Name:</label>
                        <input type="text" name="second_name" required>

                        <label>Location:</label>
                        <input type="text" name="location" required>

                        <label>Contact Number:</label>
                        <input type="text" name="contact" required>

                        <label>Email:</label>
                        <input type="email" name="email" required>

                        <label>Experience:</label>
                        <textarea name="experience" required></textarea>

                        <input type="submit" name="submit_registration" value="Submit Registration">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function toggleForm(serviceId) {
        const form = document.getElementById('form-' + serviceId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>

</body>
</html>



