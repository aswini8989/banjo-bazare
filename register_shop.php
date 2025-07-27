
<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shop_name = $_POST['shop_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $business_type = $_POST['business_type'];
    $food_category = $_POST['food_category'];

    try {
        $stmt = $conn->prepare("INSERT INTO shops (shop_name, email, password, phone, address, business_type, food_category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$shop_name, $email, $password, $phone, $address, $business_type, $food_category]);
        
        $_SESSION['user_type'] = 'shop';
        $_SESSION['shop_name'] = $shop_name;
        $_SESSION['user_id'] = $conn->lastInsertId();
        
        header("Location: dashboard_shop.php");
        exit;
    } catch(PDOException $e) {
        if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
            $error = "❌ Email already exists!";
        } else {
            $error = "❌ Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register as Street Food Vendor - StreetSupply</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 py-12">
  <div class="max-w-2xl mx-auto px-4">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
      
      <!-- Header -->
      <div class="bg-gradient-to-r from-orange-600 to-red-600 px-8 py-6 text-white text-center">
        <div class="text-4xl mb-2">🧑‍🍳</div>
        <h1 class="text-3xl font-bold mb-2">Register as Street Food Vendor</h1>
        <p class="text-orange-100">Get access to fresh raw materials and ingredients</p>
      </div>

      <div class="p-8">
        <?php if (isset($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Business/Stall Name *</label>
              <input type="text" name="shop_name" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
              <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
              <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
              <input type="tel" name="phone" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stall/Business Location *</label>
            <textarea name="address" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Street address, area, city"></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
              <select name="business_type" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">Select Business Type</option>
                <option value="Street Food Stall">Street Food Stall</option>
                <option value="Food Cart">Food Cart</option>
                <option value="Mobile Vendor">Mobile Vendor</option>
                <option value="Market Stall">Market Stall</option>
                <option value="Food Truck">Food Truck</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Food Category</label>
              <select name="food_category" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <option value="">Select Food Category</option>
                <option value="Chaat & Snacks">Chaat & Snacks</option>
                <option value="South Indian">South Indian</option>
                <option value="North Indian">North Indian</option>
                <option value="Chinese">Chinese</option>
                <option value="Beverages">Beverages</option>
                <option value="Sweets & Desserts">Sweets & Desserts</option>
                <option value="Fast Food">Fast Food</option>
                <option value="Regional Cuisine">Regional Cuisine</option>
              </select>
            </div>
          </div>

          <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <h3 class="font-semibold text-orange-900 mb-2">Vendor Benefits:</h3>
            <ul class="text-sm text-orange-800 space-y-1">
              <li>• Access to verified raw material suppliers</li>
              <li>• Fresh ingredients at competitive prices</li>
              <li>• Direct contact with suppliers</li>
              <li>• Rate and review suppliers</li>
              <li>• Build trusted supplier network</li>
            </ul>
          </div>

          <div class="flex items-center">
            <input type="checkbox" required class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
            <label class="ml-2 text-sm text-gray-600">
              I agree to the <a href="#" class="text-orange-600 hover:underline">Terms of Service</a> and <a href="#" class="text-orange-600 hover:underline">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-6 rounded-lg font-semibold text-lg transition duration-200">
            Register as Vendor
          </button>
        </form>

        <div class="mt-8 text-center border-t pt-6">
          <p class="text-gray-600">Already have an account?</p>
          <a href="login.php" class="text-orange-600 hover:underline font-medium">Login to your account</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
