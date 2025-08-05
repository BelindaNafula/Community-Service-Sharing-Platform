<?php
session_start();
require_once 'db connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_service'])) {
    $user_id = $_SESSION['user_id'];
    $provider_id = intval($_POST['provider_id']);
    $service_id = intval($_POST['service_id']);
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $insert = $conn->prepare("INSERT INTO service_requests (user_id, provider_id, service_id, message) VALUES (:user_id, :provider_id, :service_id, :message)");
        $insert->execute([
            ':user_id' => $user_id,
            ':provider_id' => $provider_id,
            ':service_id' => $service_id,
            ':message' => $message
        ]);
        $success = "Request sent successfully.";
    }
}

if (!isset($_GET['service_id'])) {
    echo "No service selected.";
    exit;
}

$service_id = intval($_GET['service_id']);

// Get service name
$serviceQuery = $conn->prepare("SELECT name FROM services WHERE id = :service_id");
$serviceQuery->bindParam(':service_id', $service_id, PDO::PARAM_INT);
$serviceQuery->execute();
$service = $serviceQuery->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "Invalid service selected.";
    exit;
}

// Fetch providers
$providerQuery = $conn->prepare("
    SELECT id AS provider_id, first_name, second_name, location, contact, email, experience 
    FROM service_registrations 
    WHERE service_id = :service_id
");
$providerQuery->bindParam(':service_id', $service_id, PDO::PARAM_INT);
$providerQuery->execute();
$providers = $providerQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Providers - <?php echo htmlspecialchars($service['name']); ?></title>
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

        .card {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .provider-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .info {
            margin-bottom: 5px;
        }

        .request-button {
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #68063c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .request-button:hover {
            background-color: #4c042b;
        }

        .request-form {
            margin-top: 10px;
            display: none;
        }

        .request-form textarea {
            width: 100%;
            height: 70px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #68063c;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function toggleForm(index) {
            const form = document.getElementById('form-' + index);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
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
            <h2>Providers for "<?php echo htmlspecialchars($service['name']); ?>"</h2>

            <?php if (!empty($success)) echo "<p class='success-message'>{$success}</p>"; ?>

            <?php if ($providers): ?>
                <?php foreach ($providers as $index => $row): ?>
                    <div class="card">
                        <div class="provider-name">
                            <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['second_name']); ?>
                        </div>
                        <div class="info">📍 Location: <?php echo htmlspecialchars($row['location']); ?></div>
                        <div class="info">📞 Contact: <?php echo htmlspecialchars($row['contact']); ?></div>
                        <div class="info">📧 Email: <?php echo htmlspecialchars($row['email']); ?></div>
                        <div class="info">💼 Experience:<br> <?php echo nl2br(htmlspecialchars($row['experience'])); ?></div>

                        <button class="request-button" onclick="toggleForm(<?php echo $index; ?>)">📩 Request Service</button>

                        <form class="request-form" id="form-<?php echo $index; ?>" method="POST">
                            <input type="hidden" name="provider_id" value="<?php echo $row['provider_id']; ?>">
                            <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                            <textarea name="message" required placeholder="Enter your request or message here..."></textarea>
                            <br><br>
                            <button type="submit" name="request_service" class="request-button">Send Request</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No providers have registered for this service yet.</p>
            <?php endif; ?>

            <a class="back-link" href="user_view_services.php">← Back to Services</a>
        </div>
    </div>

</body>
</html>












