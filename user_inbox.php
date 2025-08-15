<?php
session_start();
include 'db connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->prepare("DELETE FROM service_requests WHERE request_id = ? AND user_id = ? AND status = 'pending'")
         ->execute([$delete_id, $user_id]);
    header("Location: user_inbox.php");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_request'])) {
    $request_id = intval($_POST['request_id']);
    $new_message = trim($_POST['message']);
    if (!empty($new_message)) {
        $update = $conn->prepare("UPDATE service_requests SET message = ? WHERE request_id = ? AND user_id = ? AND status = 'pending'");
        $update->execute([$new_message, $request_id, $user_id]);
    }
    header("Location: user_inbox.php");
    exit;
}

// Fetch requests
$sql = "SELECT sr.request_id, 
               CONCAT(sr2.first_name, ' ', sr2.second_name) AS provider_name, 
               s.name AS service_name, 
               sr.message, 
               sr.status, 
               sr.created_at
        FROM service_requests sr
        JOIN service_registrations sr2 ON sr.provider_id = sr2.id
        JOIN services s ON sr.service_id = s.id
        WHERE sr.user_id = ?
        ORDER BY sr.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Inbox - CommShare</title>
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

        /* Card layout */
        .card { background: white; border-radius: 6px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h3 { margin: 0 0 5px; font-size: 16px; }
        .card p { margin: 5px 0; font-size: 14px; }
        .status { font-weight: bold; }
        .status-pending { color: orange; }
        .status-accepted { color: green; }
        .status-declined { color: red; }
        .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 5px; }
        .btn-edit { background-color: #68063cff; color: white; }
        .btn-delete { background-color: red; color: white; }
        .btn-edit:hover { background-color: #4c042b; }
        .btn-delete:hover { background-color: darkred; }
        .edit-form textarea { width: 100%; padding: 5px; font-size: 14px; }
        .edit-actions { margin-top: 5px; }
    </style>
    <script>
        function toggleEditForm(id) {
            const form = document.getElementById('edit-form-' + id);
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }
    </script>
</head>
<body>

<!-- Top Nav Bar -->
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
        <a href="user_view_services.php">📋 View Services</a>
        <a href="user_profile.php">👤 Profile</a>
        <a href="user_inbox.php">📬 Inbox</a>
        <div class="logout">
            <a href="../logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2>📬 My Sent Requests</h2>
        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $req): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($req['provider_name']); ?> — <small><?php echo htmlspecialchars($req['service_name']); ?></small></h3>
                    <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($req['message'])); ?></p>
                    <p class="status status-<?php echo strtolower($req['status']); ?>">
                        Status: <?php echo ucfirst($req['status']); ?>
                    </p>
                    <p><small>Sent On: <?php echo $req['created_at']; ?></small></p>

                    <?php if (strtolower($req['status']) === 'pending'): ?>
                        <button class="btn btn-edit" onclick="toggleEditForm(<?php echo $req['request_id']; ?>)">Edit</button>
                        <a class="btn btn-delete" href="user_inbox.php?delete_id=<?php echo $req['request_id']; ?>" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>

                        <form id="edit-form-<?php echo $req['request_id']; ?>" class="edit-form" method="POST" style="display:none; margin-top:10px;">
                            <textarea name="message" required><?php echo htmlspecialchars($req['message']); ?></textarea>
                            <input type="hidden" name="request_id" value="<?php echo $req['request_id']; ?>">
                            <div class="edit-actions">
                                <button type="submit" name="update_request" class="btn btn-edit">Save</button>
                                <button type="button" onclick="toggleEditForm(<?php echo $req['request_id']; ?>)" class="btn">Cancel</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have not sent any requests yet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>









