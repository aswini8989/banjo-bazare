
<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'shop') {
    header("Location: login.php");
    exit;
}

// Get shop's orders
$stmt = $conn->prepare("SELECT o.*, p.product_name, s.seller_name, s.company_name FROM orders o JOIN products p ON o.product_id = p.id JOIN sellers s ON o.seller_id = s.id WHERE o.shop_id = ? ORDER BY o.order_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get shop info
$stmt = $conn->prepare("SELECT * FROM shops WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$shop_info = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - ElixirHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50">

  <!-- Navigation -->
  <nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <a href="dashboard_shop.php" class="flex items-center">
            <span class="text-2xl">🧪</span>
            <span class="ml-2 text-xl font-bold text-indigo-600">ElixirHub</span>
          </a>
          <span class="ml-4 text-sm text-gray-500">My Orders</span>
        </div>
        <div class="flex items-center space-x-4">
          <a href="dashboard_shop.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Dashboard</a>
          <a href="cart.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Cart</a>
          <span class="text-sm text-gray-700">Welcome, <?= htmlspecialchars($shop_info['shop_name']) ?></span>
          <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">My Orders (<?= count($orders) ?> orders)</h2>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $order['id'] ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($order['product_name']) ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900"><?= htmlspecialchars($order['seller_name']) ?></div>
                <div class="text-xs text-gray-500"><?= htmlspecialchars($order['company_name']) ?></div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $order['quantity'] ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹<?= number_format($order['total_amount'], 2) ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                  <?= $order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                      ($order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') ?>">
                  <?= ucfirst($order['status']) ?>
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <?= date('M j, Y', strtotime($order['order_date'])) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if (empty($orders)): ?>
        <div class="text-center py-12">
          <div class="text-6xl mb-4">📦</div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders yet</h3>
          <p class="text-gray-600 mb-4">Start shopping to place your first order</p>
          <a href="dashboard_shop.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
            Start Shopping
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
