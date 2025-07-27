
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shop_name = $_POST['shop_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (empty($shop_name) || empty($email) || empty($password)) {
        die("❌ Please fill in all required fields.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO shops (shop_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$shop_name, $email, $hashed_password, $phone, $address]);
        
        header("Location: ../login.html");
        exit;
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "❌ Email already exists!";
        } else {
            echo "❌ Error: " . $e->getMessage();
        }
    }
} else {
    echo "❌ Invalid request method.";
}
?>
