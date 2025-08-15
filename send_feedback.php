<?php
session_start();
require_once 'db connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$success = "";
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

// Handle request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_service'])) {
    $user_id = $_SESSION['user_id'];
    $provider_id = intval($_POST['provider_id']);
    $service_id = intval($_POST['service_id']); 
    $message = trim($_POST['message']);

    if (!empty($message) && $provider_id > 0 && $service_id > 0) {
        $check = $conn->prepare("SELECT id FROM service_registrations WHERE id = ?");
        $check->execute([$provider_id]);
        if ($check->rowCount() === 0) {
            die("Error: Selected provider does not exist.");
        }

        $insert = $conn->prepare("
            INSERT INTO service_requests (user_id, provider_id, service_id, message, status, created_at) 
            VALUES (:user_id, :provider_id, :service_id, :message, 'pending', NOW())
        ");
        $insert->execute([
            ':user_id' => $user_id,
            ':provider_id' => $provider_id,
            ':service_id' => $service_id,
            ':message' => $message
        ]);
        $success = "Request sent successfully.";
    }
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_feedback'])) {
    $user_id = $_SESSION['user_id'];
    $provider_id = intval($_POST['provider_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($provider_id > 0 && $rating >= 1 && $rating <= 5 && !empty($comment)) {
        $stmt = $conn->prepare("
            INSERT INTO feedback (user_id, provider_id, rating, comment, created_at)
            VALUES (:user_id, :provider_id, :rating, :comment, NOW())
        ");
        $stmt->execute([
            ':user_id' => $user_id,
            ':provider_id' => $provider_id,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
        $success = "Feedback submitted successfully.";
    }
}

// Validate service_id
if ($service_id <= 0) {
    echo "No service selected.";
    exit;
}

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
    SELECT id, first_name, second_name, location, contact, email, experience 
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
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #f3edf0ff; }
        nav { background-color: #68063cff; padding: 15px 20px; color: white; display: flex; justify-content: space-between; align-items: center; }
        nav h1 { margin: 0; font-size: 22px; }
        .nav-links { list-style: none; display: flex; gap: 20px; margin: 0; }
        .nav-links li a { color: white; text-decoration: none; font-weight: bold; }
        .nav-links li a:hover { text-decoration: underline; }
        .dashboard-container { display: flex; height: calc(100vh - 60px); }
        .sidebar { width: 220px; background-color: #1b0210ff; color: white; padding: 20px; }
        .sidebar h2 { font-size: 22px; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; margin-bottom: 15px; padding: 10px; border-radius: 4px; transition: background 0.3s ease; }
        .sidebar a:hover { background-color: #4c042b; }
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        .card { background: white; padding: 15px; margin-bottom: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .provider-name { font-size: 18px; font-weight: bold; margin-bottom: 8px; }
        .info { margin-bottom: 5px; }
        .request-button { margin-top: 10px; padding: 8px 12px; background-color: #68063c; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .request-button:hover { background-color: #4c042b; }
        .request-form { margin-top: 10px; display: none; }
        .request-form textarea { width: 100%; height: 70px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; resize: vertical; }
        .success-message { color: green; font-weight: bold; margin-bottom: 15px; }
        .back-link { display: inline-block; margin-top: 20px; color: #68063c; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        select { padding: 5px; border-radius: 4px; }
    </style>
    <script>
        function toggleForm(id) {
            const form = document.getElementById(id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <nav>
        <h1>CommShare User Dashboard</h1>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>User Panel</h2>
            <a href="dashboard user.php">🏠 Dashboard</a>
            <a href="user_view_services.php">📋 View Services</a>
            <a href="user_profile.php">👤 Profile</a>
            <a href="user_inbox.php">📬 Inbox</a>
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
                        
                        <!-- Request Service -->
                        <button class="request-button" onclick="toggleForm('request-<?php echo $index; ?>')">📩 Request Service</button>
                        <form class="request-form" id="request-<?php echo $index; ?>" method="POST">
                            <input type="hidden" name="provider_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                            <textarea name="message" required placeholder="Enter your request or message here..."></textarea>
                            <br><br>
                            <button type="submit" name="request_service" class="request-button">Send Request</button>
                        </form>

                        <!-- Leave Feedback -->
                        <button class="request-button" onclick="toggleForm('feedback-<?php echo $index; ?>')">⭐ Leave Feedback</button>
                        <form class="request-form" id="feedback-<?php echo $index; ?>" method="POST">
                            <input type="hidden" name="provider_id" value="<?php echo $row['id']; ?>">
                            <label for="rating-<?php echo $index; ?>">Rating:</label>
                            <select name="rating" id="rating-<?php echo $index; ?>" required>
                                <option value="">-- Select --</option>
                                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4">⭐⭐⭐⭐ (4)</option>
                                <option value="3">⭐⭐⭐ (3)</option>
                                <option value="2">⭐⭐ (2)</option>
                                <option value="1">⭐ (1)</option>
                            </select>
                            <br><br>
                            <textarea name="comment" required placeholder="Write your feedback..." style="width:100%;height:70px;"></textarea>
                            <br><br>
                            <button type="submit" name="leave_feedback" class="request-button">Submit Feedback</button>
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


