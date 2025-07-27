<?php
// Start session
session_start();

// DB config for XAMPP
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP default
$dbname = "elixirhub"; // make sure this DB exists in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>

