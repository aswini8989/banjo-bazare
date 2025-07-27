<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: login.php");
    exit;
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $wholesale_price = $_POST['wholesale_price'];
    $min_order_qty = $_POST['min_order_qty'];
    $stock_quantity = $_POST['stock_quantity'];

    try {
        $stmt = $conn->prepare("INSERT INTO products (seller_id, product_name, description, category, price, wholesale_price, min_order_qty, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product_name, $description, $category, $price, $wholesale_price, $min_order_qty, $stock_quantity]);
        $success = "Product added successfully!";
    } catch(PDOException $e) {
        $error = "Error adding product: " . $e->getMessage();
    }
}

// Get seller info
$stmt = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$seller_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Get seller's products
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent orders
$stmt = $conn->prepare("SELECT o.*, p.product_name, s.shop_name FROM orders o JOIN products p ON o.product_id = p.id JOIN shops s ON o.shop_id = s.id WHERE o.seller_id = ? ORDER BY o.order_date DESC LIMIT 10");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Dashboard - ElixirHub</title>
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
          <span class="text-2xl">🧪</span>
          <span class="ml-2 text-xl font-bold text-indigo-600">ElixirHub</span>
          <span class="ml-4 text-sm text-gray-500">Seller Dashboard</span>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-gray-700">Welcome, <?= htmlspecialchars($seller_info['seller_name']) ?></span>
          <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="text-3xl text-blue-500">📦</div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900"><?= count($products) ?></div>
            <div class="text-sm text-gray-600">Products Listed</div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="text-3xl text-green-500">🛒</div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900"><?= count($orders) ?></div>
            <div class="text-sm text-gray-600">Total Orders</div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="text-3xl text-yellow-500">💰</div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">₹<?= number_format(array_sum(array_column($orders, 'total_amount')), 0) ?></div>
            <div class="text-sm text-gray-600">Total Revenue</div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="text-3xl text-purple-500">⭐</div>
          <div class="ml-4">
            <div class="text-2xl font-bold text-gray-900">4.8</div>
            <div class="text-sm text-gray-600">Seller Rating</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Product Form -->
    <div class="bg-white rounded-lg shadow mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Add New Product</h3>
      </div>
      <div class="p-6">
        <?php if (isset($success)): ?>
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $success ?>
          </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
            <input type="text" name="product_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select name="category" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <option value="">Select Category</option>
              <option value="Fashion">Fashion & Apparel</option>
              <option value="Electronics">Electronics</option>
              <option value="Home & Garden">Home & Garden</option>
              <option value="Sports">Sports & Fitness</option>
              <option value="Beauty">Beauty & Personal Care</option>
              <option value="Industrial">Industrial & Scientific</option>
            </select>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price (₹)</label>
            <input type="number" name="price" step="0.01" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Wholesale Price (₹)</label>
            <input type="number" name="wholesale_price" step="0.01" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Quantity</label>
            <input type="number" name="min_order_qty" value="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
            <input type="number" name="stock_quantity" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div class="md:col-span-2">
            <button type="submit" name="add_product" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
              Add Product
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Products List -->
    <div class="bg-white rounded-lg shadow mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Your Products</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($products as $product): ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div>
                  <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['product_name']) ?></div>
                  <div class="text-sm text-gray-500"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($product['category']) ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">₹<?= number_format($product['wholesale_price'], 2) ?></div>
                <div class="text-xs text-gray-500">Min: <?= $product['min_order_qty'] ?> units</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $product['stock_quantity'] ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $order['id'] ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($order['product_name']) ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($order['shop_name']) ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹<?= number_format($order['total_amount'], 2) ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                  <?= $order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                      ($order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') ?>">
                  <?= ucfirst($order['status']) ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>
</html>