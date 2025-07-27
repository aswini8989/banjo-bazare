
<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        if ($user_type === "shop") {
            $stmt = $conn->prepare("SELECT * FROM shops WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_type'] = 'shop';
                $_SESSION['shop_name'] = $user['shop_name'];
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard_shop.php");
                exit;
            }
        } else {
            $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_type'] = 'seller';
                $_SESSION['seller_name'] = $user['seller_name'];
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard_seller.php");
                exit;
            }
        }
        
        $error = "❌ Invalid credentials!";
    } catch(PDOException $e) {
        $error = "❌ Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - StreetSupply</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-xl rounded-2xl p-10 w-full max-w-md">
    
    <div class="text-center mb-8">
      <div class="text-4xl mb-2">🍜</div>
      <h2 class="text-2xl font-bold text-orange-600 mb-2">Login to StreetSupply</h2>
      <p class="text-gray-600 text-sm">Raw Materials for Street Food</p>
    </div>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4" onsubmit="return validateForm()">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">I am a:</label>
        <select name="user_type" id="user_type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
          <option value="">Select User Type</option>
          <option value="seller">🚚 Raw Material Supplier</option>
          <option value="shop">🧑‍🍳 Street Food Vendor</option>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>
      
      <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg font-semibold transition">
        Login
      </button>
    </form>

    <script>
      function validateForm() {
        const userType = document.getElementById('user_type').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!userType) {
          alert('Please select user type');
          return false;
        }

        if (!email) {
          alert('Please enter email');
          return false;
        }

        if (!password) {
          alert('Please enter password');
          return false;
        }

        return true;
      }
    </script>

    <div class="mt-6 text-center text-sm">
      <p class="text-gray-600 mb-2">Don't have an account?</p>
      <div class="space-x-2">
        <a href="register_seller.php" class="text-green-600 hover:underline">Register as Supplier</a>
        <span class="text-gray-400">|</span>
        <a href="register_shop.php" class="text-orange-600 hover:underline">Register as Vendor</a>
      </div>
    </div>
  </div>
</body>
</html>
