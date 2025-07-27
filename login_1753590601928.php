<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user_type === "shop") {
        $stmt = $conn->prepare("SELECT * FROM shops WHERE email = ?");
    } elseif ($user_type === "seller") {
        $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
    } else {
        die("❌ Invalid user type selected.");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            if ($user_type === "shop") {
                $_SESSION['user_type'] = "shop";
                $_SESSION['shop_id'] = $user['id'];
                $_SESSION['shop_name'] = $user['shop_name'];
                header("Location: ./dashboard_seller_view.php");
            } else {
                $_SESSION['user_type'] = "seller";
                $_SESSION['seller_id'] = $user['id'];
                $_SESSION['seller_name'] = $user['seller_name'];
                header("Location: ./dashboard_shop_view.php");
            }
        } else {
            echo "❌ Incorrect password!";
        }
    } else {
        echo "❌ User not found!";
    }
}
?>