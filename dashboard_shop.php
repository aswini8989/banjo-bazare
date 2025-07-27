<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'shop') {
    header("Location: login.php");
    exit;
}

// Handle add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    try {
        // Check if product already in cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE shop_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);

        if ($stmt->fetch()) {
            // Update quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE shop_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $_SESSION['user_id'], $product_id]);
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO cart (shop_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
        }
        $success = "Product added to cart!";
    } catch(PDOException $e) {
        $error = "Error adding to cart: " . $e->getMessage();
    }
}

// Get shop info
$stmt = $conn->prepare("SELECT * FROM shops WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$shop_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all products with seller info
$stmt = $conn->query("SELECT p.*, s.seller_name, s.company_name FROM products p JOIN sellers s ON p.seller_id = s.id ORDER BY p.created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get cart count
$stmt = $conn->prepare("SELECT COUNT(*) as cart_count FROM cart WHERE shop_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_count = $stmt->fetch(PDO::FETCH_ASSOC)['cart_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop Dashboard - ElixirHub</title>
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
          <span class="ml-4 text-sm text-gray-500">Wholesale Marketplace</span>
        </div>
        <div class="flex items-center space-x-4">
          <a href="orders.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">My Orders</a>
          <div class="relative">
            <a href="cart.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
              🛒 Cart (<?= $cart_count ?>)
            </a>
          </div>
          <span class="text-sm text-gray-700">Welcome, <?= htmlspecialchars($shop_info['shop_name']) ?></span>
          <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-8 mb-8 text-white">
      <h1 class="text-3xl font-bold mb-2">Welcome to ElixirHub Wholesale</h1>
      <p class="text-indigo-100">Discover thousands of products at wholesale prices. Connect directly with verified sellers.</p>
      <div class="mt-4 grid grid-cols-3 gap-4 text-center">
        <div>
          <div class="text-2xl font-bold"><?= count($products) ?></div>
          <div class="text-sm text-indigo-200">Products Available</div>
        </div>
        <div>
          <div class="text-2xl font-bold">500+</div>
          <div class="text-sm text-indigo-200">Verified Sellers</div>
        </div>
        <div>
          <div class="text-2xl font-bold">₹100+</div>
          <div class="text-sm text-indigo-200">Min. Wholesale Order</div>
        </div>
      </div>
    </div>

    <?php if (isset($success)): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?= $success ?>
      </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
      <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="flex-1">
          <input type="text" id="searchInput" placeholder="Search products..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
          <select id="categoryFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Categories</option>
            <option value="Vegetables & Fruits">Vegetables & Fruits</option>
            <option value="Grains & Pulses">Grains & Pulses</option>
            <option value="Spices & Seasonings">Spices & Seasonings</option>
            <option value="Dairy Products">Dairy Products</option>
            <option value="Meat & Poultry">Meat & Poultry</option>
            <option value="Cooking Oil & Ghee">Cooking Oil & Ghee</option>
          </select>
        </div>
      </div>
      <div class="flex flex-wrap gap-2">
        <button onclick="filterByCategory('')" class="category-btn bg-indigo-100 text-indigo-800 px-4 py-2 rounded-full text-sm font-medium">All Categories</button>
        <button onclick="filterByCategory('Vegetables & Fruits')" class="category-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200">Vegetables & Fruits</button>
        <button onclick="filterByCategory('Grains & Pulses')" class="category-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200">Grains & Pulses</button>
        <button onclick="filterByCategory('Spices & Seasonings')" class="category-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200">Spices & Seasonings</button>
        <button onclick="filterByCategory('Dairy Products')" class="category-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200">Dairy Products</button>
        <button onclick="filterByCategory('Meat & Poultry')" class="category-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200">Meat & Poultry</button>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php foreach ($products as $product): ?>
        <div class="product-card bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
          <div class="p-6">
            <div class="flex justify-between items-start mb-4">
              <span class="product-category inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full"><?= htmlspecialchars($product['category']) ?></span>
              <div class="text-right">
                <div class="text-lg font-bold text-gray-900">₹<?= number_format($product['wholesale_price'], 2) ?></div>
                <div class="text-sm text-gray-500 line-through">₹<?= number_format($product['price'], 2) ?></div>
              </div>
            </div>

            <h3 class="product-name text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($product['product_name']) ?></h3>
            <p class="product-description text-gray-600 text-sm mb-4"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>

            <div class="space-y-2 text-sm text-gray-600 mb-4">
              <div class="flex justify-between">
                <span>Seller:</span>
                <span class="font-medium"><?= htmlspecialchars($product['seller_name']) ?></span>
              </div>
              <div class="flex justify-between">
                <span>Min. Order:</span>
                <span class="font-medium"><?= $product['min_order_qty'] ?> units</span>
              </div>
              <div class="flex justify-between">
                <span>Stock:</span>
                <span class="font-medium text-green-600"><?= $product['stock_quantity'] ?> available</span>
              </div>
            </div>

            <form method="POST" class="space-y-3">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                <input type="number" name="quantity" value="<?= $product['min_order_qty'] ?>" min="<?= $product['min_order_qty'] ?>" max="<?= $product['stock_quantity'] ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
              </div>
              <button type="submit" name="add_to_cart" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition">
                Add to Cart
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($products)): ?>
      <div class="text-center py-12">
        <div class="text-6xl mb-4">📦</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Available</h3>
        <p class="text-gray-600">Check back later for new wholesale products!</p>
      </div>
    <?php endif; ?>
  </div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const products = document.querySelectorAll('.product-card');
      
      products.forEach(product => {
        const productName = product.querySelector('.product-name').textContent.toLowerCase();
        const productDescription = product.querySelector('.product-description').textContent.toLowerCase();
        
        if (productName.includes(searchTerm) || productDescription.includes(searchTerm)) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    });

    // Category filter functionality
    function filterByCategory(category) {
      const products = document.querySelectorAll('.product-card');
      const buttons = document.querySelectorAll('.category-btn');
      
      // Update button styles
      buttons.forEach(btn => {
        btn.className = 'category-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200';
      });
      event.target.className = 'category-btn bg-indigo-100 text-indigo-800 px-4 py-2 rounded-full text-sm font-medium';
      
      // Filter products
      products.forEach(product => {
        const productCategory = product.querySelector('.product-category').textContent;
        
        if (category === '' || productCategory === category) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    }

    // Category dropdown change
    document.getElementById('categoryFilter').addEventListener('change', function() {
      filterByCategory(this.value);
    });
  </script>

</body>
</html>