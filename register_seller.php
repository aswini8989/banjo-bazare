
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

    try {
        $stmt = $conn->prepare("INSERT INTO sellers (seller_name, email, password, phone, company_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$seller_name, $email, $hashed_password, $phone, $company_name]);
        
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
    echo "❌ Invalid request.";
}
?>
