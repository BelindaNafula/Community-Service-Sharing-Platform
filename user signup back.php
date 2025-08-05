<?php
require_once "db connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $location = $_POST['location'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, password, location) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $hashed_password, $location])) {
        echo "User registered successfully!";
        // redirect to login or dashboard if needed
        // header("Location: login.php");
    } else {
        echo "Error: could not register user.";
    }
}
?>
