<?php
require_once "db connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get and sanitize inputs
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact_info = !empty($_POST['contact_info']) ? $_POST['contact_info'] : null;
    $bio = !empty($_POST['bio']) ? $_POST['bio'] : null;
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $services = $_POST['services'] ?? [];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert into service_providers table
        $stmt = $conn->prepare("INSERT INTO service_providers (name, password, location, contact_info, bio) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $hashed_password, $location, $contact_info, $bio]);

        // Get the inserted provider's ID
        $provider_id = $conn->lastInsertId();

        // Insert selected services into provider_services table
        if (!empty($services)) {
            $stmt = $conn->prepare("INSERT INTO service_providers (provider_id, service_id) VALUES (?, ?)");
            foreach ($services as $service_id) {
                $stmt->execute([$provider_id, $service_id]);
            }
        }

        echo "Service provider registered successfully!";
        // Optional: redirect to login or provider dashboard
        // header("Location: login_provider.php");
        // exit;

    } catch (PDOException $e) {
        echo "Registration failed: " . $e->getMessage();
    }
}
?>
