<?php
session_start();
include 'db connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'provider') {
    header('Location: login.php');
    exit;
}

$provider_id = $_SESSION['user_id']; // ID from service_providers table

// Check if profile exists
$stmt = $conn->prepare("SELECT first_name, second_name, email, contact, location, experience 
                        FROM service_registrations 
                        WHERE provider_id = ?");
$stmt->execute([$provider_id]);
$provider = $stmt->fetch(PDO::FETCH_ASSOC);

// If no profile exists, create a blank one
if (!$provider) {
    $insert = $conn->prepare("INSERT INTO service_registrations 
                              (provider_id, service_id, first_name, second_name, email, contact, location, experience) 
                              VALUES (?, 0, '', '', '', '', '', '')");
    $insert->execute([$provider_id]);

    // Fetch again after inserting
    $stmt->execute([$provider_id]);
    $provider = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update profile if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name']);
    $second_name = trim($_POST['second_name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $location = trim($_POST['location']);
    $experience = trim($_POST['experience']);

    $update = $conn->prepare("UPDATE service_registrations 
                               SET first_name=?, second_name=?, email=?, contact=?, location=?, experience=? 
                               WHERE provider_id=?");
    $update->execute([$first_name, $second_name, $email, $contact, $location, $experience, $provider_id]);

    $success_message = "Profile updated successfully!";

    // Refresh provider data
    $stmt->execute([$provider_id]);
    $provider = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Profile - CommShare</title>
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
        .profile-card { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; }
        .profile-card h2 { margin-top: 0; color: #68063cff; }
        .profile-detail { margin-bottom: 15px; }
        .profile-detail strong { display: inline-block; width: 120px; color: #333; }
        .edit-btn { margin-top: 20px; background-color: #68063cff; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
        .edit-btn:hover { background-color: #4c042b; }
        .edit-form { display: none; margin-top: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 6px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
        .save-btn { background-color: #68063cff; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
        .save-btn:hover { background-color: #4c042b; }
        .success-message { color: green; font-weight: bold; margin-bottom: 15px; }
    </style>
    <script>
        function toggleEditForm() {
            document.getElementById('editForm').style.display = 
                document.getElementById('editForm').style.display === 'none' ? 'block' : 'none';
        }
    </script>
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
            <a href="provider inbox.php">📬 Inbox</a>
            <div class="logout">
                <a href="../logout.php">🚪 Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="profile-card">
                <h2>My Profile</h2>
                <?php if (!empty($success_message)) echo "<p class='success-message'>$success_message</p>"; ?>
                <div class="profile-detail"><strong>First Name:</strong> <?php echo htmlspecialchars($provider['first_name']); ?></div>
                <div class="profile-detail"><strong>Second Name:</strong> <?php echo htmlspecialchars($provider['second_name']); ?></div>
                <div class="profile-detail"><strong>Email:</strong> <?php echo htmlspecialchars($provider['email']); ?></div>
                <div class="profile-detail"><strong>Phone:</strong> <?php echo htmlspecialchars($provider['contact']); ?></div>
                <div class="profile-detail"><strong>Location:</strong> <?php echo htmlspecialchars($provider['location']); ?></div>
                <div class="profile-detail"><strong>Experience:</strong> <?php echo htmlspecialchars($provider['experience']); ?></div>

                <button class="edit-btn" onclick="toggleEditForm()">Edit Profile</button>

                <!-- Hidden Edit Form -->
                <form id="editForm" class="edit-form" method="POST">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($provider['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Second Name:</label>
                        <input type="text" name="second_name" value="<?php echo htmlspecialchars($provider['second_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($provider['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="contact" value="<?php echo htmlspecialchars($provider['contact']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Location:</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($provider['location']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Experience:</label>
                        <textarea name="experience" rows="4"><?php echo htmlspecialchars($provider['experience']); ?></textarea>
                    </div>
                    <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>










