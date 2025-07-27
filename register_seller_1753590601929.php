<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_name = $_POST['seller_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $company_name = $_POST['company_name'];

    if (empty($seller_name) || empty($email) || empty($password)) {
        die("❌ Please fill in all required fields.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO sellers (seller_name, email, password, phone, company_name) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("❌ SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssss", $seller_name, $email, $hashed_password, $phone, $company_name);

    if ($stmt->execute()) {
        header("Location: ../login.html");
        exit;
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Invalid request.";
}
?>