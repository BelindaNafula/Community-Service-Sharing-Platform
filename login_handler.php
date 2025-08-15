<?php
session_start();
require_once "db connection.php";

$role = $_POST['role'];
$username = $_POST['username'];
$password = $_POST['password'];

if ($role === 'user') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
} elseif ($role === 'provider') {
    $stmt = $conn->prepare("SELECT * FROM service_providers WHERE name = ?");
} elseif ($role === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
} else {
    die("Invalid role selected.");
}

$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

    // Correct session mapping for each role
    if ($role === 'user') {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
    } elseif ($role === 'provider') {
        $_SESSION['user_id'] = $user['id']; // FIX: store provider's primary key
        $_SESSION['username'] = $user['name'];
    } elseif ($role === 'admin') {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    }

    $_SESSION['role'] = $role;

    // Redirect based on role
    if ($role === 'user') {
        header("Location: dashboard user.php");
    } elseif ($role === 'provider') {
        header("Location: provider dashboard.php");
    } elseif ($role === 'admin') {
        header("Location: admin dashboard.php");
    }
    exit;
} else {
    echo "Invalid credentials.";
}
?>


