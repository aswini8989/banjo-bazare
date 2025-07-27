
<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'shop') {
    header("Location: login.php");
    exit;
}

// Handle quantity update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    
    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND shop_id = ?");
        $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND shop_id = ?");
        $stmt->execute([$cart_id, $_SESSION['user_id']]);
    }
}

// Handle remove from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $cart_id = $_POST['cart_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND shop_id = ?");
    $stmt->execute([$cart_id, $_SESSION['user_id']]);
}

// Handle place order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    try {
        $conn->beginTransaction();
        
        // Get cart items
        $stmt = $conn->prepare("SELECT c.*, p.*, s.seller_name FROM cart c JOIN products p ON c.product_id = p.id JOIN sellers s ON p.seller_id = s.id WHERE c.shop_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($cart_items as $item) {
            $total_amount = $item['wholesale_price'] * $item['quantity'];
            
            // Create order
            $stmt = $conn->prepare("INSERT INTO orders (shop_id, seller_id, product_id, quantity, total_amount) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $item['seller_id'], $item['product_id'], $item['quantity'], $total_amount]);
        }
        
        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE shop_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        $conn->commit();
        $success = "Order placed successfully!";
        
    } catch(PDOException $e) {
        $conn->rollback();
        $error = "Error placing order: " . $e->getMessage();
    }
}

// Get cart items
$stmt = $conn->prepare("SELECT c.*, p.*, s.seller_name FROM cart c JOIN products p ON c.product_id = p.id JOIN sellers s ON p.seller_id = s.id WHERE c.shop_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['wholesale_price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart - ElixirHub</title>
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
          <span class="ml-4 text-sm text-gray-500">Shopping Cart</span>
        </div>
        <div class="flex items-center space-x-4">
          <a href="dashboard_shop.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Continue Shopping</a>
          <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Cart Items -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Shopping Cart (<?= count($cart_items) ?> items)</h2>
          </div>
          
          <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-6">
              <?= $success ?>
            </div>
          <?php endif; ?>

          <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-6">
              <?= $error ?>
            </div>
          <?php endif; ?>

          <div class="divide-y divide-gray-200">
            <?php foreach ($cart_items as $item): ?>
              <div class="p-6">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h3>
                    <p class="text-sm text-gray-600 mt-1">by <?= htmlspecialchars($item['seller_name']) ?></p>
                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($item['category']) ?></p>
                    <div class="mt-2">
                      <span class="text-lg font-bold text-gray-900">₹<?= number_format($item['wholesale_price'], 2) ?></span>
                      <span class="text-sm text-gray-500 ml-2">per unit</span>
                    </div>
                  </div>
                  
                  <div class="flex items-center space-x-4">
                    <form method="POST" class="flex items-center space-x-2">
                      <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                      <label class="text-sm text-gray-600">Qty:</label>
                      <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="<?= $item['min_order_qty'] ?>" max="<?= $item['stock_quantity'] ?>" class="w-20 border border-gray-300 rounded px-2 py-1 text-center">
                      <button type="submit" name="update_quantity" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm">Update</button>
                    </form>
                    
                    <div class="text-right">
                      <div class="text-lg font-bold text-gray-900">₹<?= number_format($item['wholesale_price'] * $item['quantity'], 2) ?></div>
                      <form method="POST" class="mt-1">
                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="remove_item" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if (empty($cart_items)): ?>
            <div class="text-center py-12">
              <div class="text-6xl mb-4">🛒</div>
              <h3 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
              <p class="text-gray-600 mb-4">Start shopping to add items to your cart</p>
              <a href="dashboard_shop.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                Continue Shopping
              </a>
            </div>
          <?php else: ?>
            <div class="p-6 bg-gray-50 border-t">
              <a href="dashboard_shop.php" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Continue Shopping
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Order Summary -->
      <?php if (!empty($cart_items)): ?>
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow sticky top-6">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
            </div>
            
            <div class="p-6 space-y-4">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">₹<?= number_format($total_amount, 2) ?></span>
              </div>
              
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Shipping</span>
                <span class="font-medium text-green-600">Free</span>
              </div>
              
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax (GST)</span>
                <span class="font-medium">₹<?= number_format($total_amount * 0.18, 2) ?></span>
              </div>
              
              <div class="border-t pt-4">
                <div class="flex justify-between text-lg font-bold">
                  <span>Total</span>
                  <span>₹<?= number_format($total_amount * 1.18, 2) ?></span>
                </div>
              </div>
              
              <form method="POST" class="mt-6">
                <button type="submit" name="place_order" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-semibold transition">
                  Place Order
                </button>
              </form>
              
              <div class="text-center text-sm text-gray-500 mt-4">
                <p>🔒 Secure checkout guaranteed</p>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
