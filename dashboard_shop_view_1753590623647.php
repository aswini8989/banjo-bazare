<?php
session_start();
include "backend/config.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'shop') {
    header("Location: login.html");
    exit;
}

$result = $conn->query("SELECT seller_name, email, phone, company_name FROM sellers");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shop Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h2 class="text-2xl font-bold text-indigo-600 mb-4">Welcome, Shop Owner!</h2>
  <p class="mb-6">Here are available sellers for your shop:</p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="bg-white p-4 shadow rounded">
        <h3 class="text-lg font-semibold"><?= $row['seller_name'] ?></h3>
        <p>Email: <?= $row['email'] ?></p>
        <p>Phone: <?= $row['phone'] ?></p>
        <p>Company: <?= $row['company_name'] ?></p>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>