<?php
include 'db connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider_id = $_SESSION['provider_id'];  // assuming you're storing this in session
    $service_id = $_POST['service_id'];
    $first_name = $_POST['first_name'];
    $second_name = $_POST['second_name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $experience = $_POST['experience'];

    $sql = "INSERT INTO service_registrations (provider_id, service_id, first_name, second_name, location, contact, email, experience)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssss", $provider_id, $service_id, $first_name, $second_name, $location, $contact, $email, $experience);

    if ($stmt->execute()) {
        header("Location: services.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
