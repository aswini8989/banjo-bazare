
<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_name = $_POST['seller_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $company_name = $_POST['company_name'];
    $business_category = $_POST['business_category'];
    $supply_area = $_POST['supply_area'];

    try {
        $stmt = $conn->prepare("INSERT INTO sellers (seller_name, email, password, phone, company_name, business_category, supply_area) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$seller_name, $email, $password, $phone, $company_name, $business_category, $supply_area]);
        
        $_SESSION['user_type'] = 'seller';
        $_SESSION['seller_name'] = $seller_name;
        $_SESSION['user_id'] = $conn->lastInsertId();
        
        header("Location: dashboard_seller.php");
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
  <title>Register as Raw Material Supplier - StreetSupply</title>
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
      <div class="bg-gradient-to-r from-green-600 to-blue-600 px-8 py-6 text-white text-center">
        <div class="text-4xl mb-2">🚚</div>
        <h1 class="text-3xl font-bold mb-2">Register as Raw Material Supplier</h1>
        <p class="text-green-100">Supply fresh ingredients to street food vendors</p>
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
              <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
              <input type="text" name="seller_name" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
              <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
              <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
              <input type="tel" name="phone" required class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Company/Business Name</label>
            <input type="text" name="company_name" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Supply Category</label>
              <select name="business_category" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">Select Category</option>
                <option value="Vegetables & Fruits">Vegetables & Fruits</option>
                <option value="Grains & Pulses">Grains & Pulses</option>
                <option value="Spices & Seasonings">Spices & Seasonings</option>
                <option value="Dairy Products">Dairy Products</option>
                <option value="Meat & Poultry">Meat & Poultry</option>
                <option value="Cooking Oil & Ghee">Cooking Oil & Ghee</option>
                <option value="Packaging Materials">Packaging Materials</option>
                <option value="Kitchen Supplies">Kitchen Supplies</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Supply Area</label>
              <input type="text" name="supply_area" placeholder="City/Area you supply to" class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
          </div>

          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-semibold text-blue-900 mb-2">Supplier Benefits:</h3>
            <ul class="text-sm text-blue-800 space-y-1">
              <li>• Reach hundreds of street food vendors</li>
              <li>• List unlimited products for free</li>
              <li>• Manage orders from dashboard</li>
              <li>• Build reputation through ratings</li>
              <li>• Direct communication with vendors</li>
            </ul>
          </div>

          <div class="flex items-center">
            <input type="checkbox" required class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
            <label class="ml-2 text-sm text-gray-600">
              I agree to the <a href="#" class="text-green-600 hover:underline">Terms of Service</a> and <a href="#" class="text-green-600 hover:underline">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold text-lg transition duration-200">
            Register as Supplier
          </button>
        </form>

        <div class="mt-8 text-center border-t pt-6">
          <p class="text-gray-600">Already have a supplier account?</p>
          <a href="login.php" class="text-green-600 hover:underline font-medium">Login to your account</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
