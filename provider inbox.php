<?php
session_start();
require_once 'db connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'provider') {
    header('Location: login.php');
    exit;
}

// Get provider's service registration ID
$stmt = $conn->prepare("
    SELECT sr.id 
    FROM service_registrations sr
    JOIN service_providers sp ON sp.id = sr.provider_id
    WHERE sp.name = ?
");
$stmt->execute([$_SESSION['username']]);
$providerReg = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$providerReg) {
    echo "Provider not found in service registrations.";
    exit;
}

$provider_registration_id = $providerReg['id'];

// Handle Accept / Decline actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $request_id = intval($_POST['request_id']);
    $new_status = $_POST['status']; // "accepted" or "declined"

    if (in_array($new_status, ['accepted', 'declined'])) {
        $update = $conn->prepare("
            UPDATE service_requests 
            SET status = ? 
            WHERE request_id = ? AND provider_id = ?
        ");
        $update->execute([$new_status, $request_id, $provider_registration_id]);
    }
    header("Location: provider inbox.php");
    exit;
}

// Fetch service requests for this provider
$sql = "
    SELECT srq.request_id, u.username AS user_name, srq.message, srq.status, srq.created_at
    FROM service_requests srq
    JOIN users u ON srq.user_id = u.user_id
    WHERE srq.provider_id = ?
    ORDER BY srq.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute([$provider_registration_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Inbox - CommShare</title>
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
        .logout { margin-top: 40px; font-size: 14px; }
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        .request-card { background: white; padding: 20px; border-radius: 6px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .request-card h3 { margin-top: 0; color: #68063cff; }
        .status { font-weight: bold; text-transform: capitalize; }
        .status.pending { color: orange; }
        .status.accepted { color: green; }
        .status.declined { color: red; }
        .no-requests { text-align: center; font-size: 18px; color: #555; margin-top: 50px; }
        .action-buttons { margin-top: 10px; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-accept { background-color: green; }
        .btn-decline { background-color: red; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body>

    <!-- Top Nav -->
    <nav>
        <h1>CommShare Service Provider Dashboard</h1>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Sidebar + Main Content -->
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Provider Panel</h2>
            <a href="view_services.php">📋 View Services</a>
            <a href="provider profile.php">👤 Profile</a>
            <a href="registered_services.php">✅ Registered Services</a>
            <a href="provider_inbox.php">📬 Inbox</a>
            <div class="logout">
                <a href="../logout.php">🚪 Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h2>Service Requests</h2>

            <?php if (empty($requests)): ?>
                <div class="no-requests">📭 No service requests yet.</div>
            <?php else: ?>
                <?php foreach ($requests as $req): ?>
                    <div class="request-card">
                        <h3>From: <?php echo htmlspecialchars($req['user_name']); ?></h3>
                        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($req['message'])); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="status <?php echo htmlspecialchars($req['status']); ?>">
                                <?php echo htmlspecialchars($req['status']); ?>
                            </span>
                        </p>
                        <p><small>Requested on: <?php echo htmlspecialchars($req['created_at']); ?></small></p>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $req['request_id']; ?>">
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" name="update_status" class="btn btn-accept" 
                                    <?php echo ($req['status'] !== 'pending') ? 'disabled' : ''; ?>>
                                    ✅ Accept
                                </button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $req['request_id']; ?>">
                                <input type="hidden" name="status" value="declined">
                                <button type="submit" name="update_status" class="btn btn-decline"
                                    <?php echo ($req['status'] !== 'pending') ? 'disabled' : ''; ?>>
                                    ❌ Decline
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>







