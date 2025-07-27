<?php
session_start();
include "backend/config.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: login.html");
    exit;
}

$result = $conn->query("SELECT shop_name, email, phone, address FROM shops");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Seller Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h2 class="text-2xl font-bold text-indigo-600 mb-4">Welcome, Seller!</h2>
  <p class="mb-6">Here are all shops looking for sellers:</p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="bg-white p-4 shadow rounded">
        <h3 class="text-lg font-semibold"><?= $row['shop_name'] ?></h3>
        <p>Email: <?= $row['email'] ?></p>
        <p>Phone: <?= $row['phone'] ?></p>
        <p>Address: <?= $row['address'] ?></p>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>