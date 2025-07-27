
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bhojan Bazaar - Traditional meets Modern</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    .handwritten { font-family: 'Kalam', cursive; }
    .modern { font-family: 'Inter', sans-serif; }
    body { 
      background: linear-gradient(135deg, #8B5A3C 0%, #D2691E 25%, #CD853F 50%, #DEB887 75%, #F5DEB3 100%);
      animation: gradientShift 8s ease-in-out infinite;
    }
    @keyframes gradientShift {
      0%, 100% { background: linear-gradient(135deg, #8B5A3C 0%, #D2691E 25%, #CD853F 50%, #DEB887 75%, #F5DEB3 100%); }
      50% { background: linear-gradient(135deg, #F5DEB3 0%, #DEB887 25%, #CD853F 50%, #D2691E 75%, #8B5A3C 100%); }
    }
    .card-hover { transition: all 0.3s ease; }
    .card-hover:hover { transform: translateY(-10px) scale(1.02); }
  </style>
</head>
<body class="min-h-screen">

  <!-- Navigation -->
  <nav class="bg-white/90 backdrop-blur-sm shadow-lg sticky top-0 z-50 border-b-4 border-orange-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <div class="flex items-center">
          <div class="flex-shrink-0 flex items-center">
            <span class="text-4xl animate-bounce">🍛</span>
            <span class="ml-3 handwritten text-3xl font-bold text-orange-800">Bhojan Bazaar</span>
          </div>
          <div class="hidden md:block ml-8">
            <span class="modern text-sm text-gray-600 bg-orange-100 px-4 py-2 rounded-full border-2 border-orange-200">
              Traditional meets Modern • Raw Materials for Street Food
            </span>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <a href="login.html" class="modern text-gray-700 hover:text-orange-600 px-4 py-2 text-sm font-medium transition border-2 border-transparent hover:border-orange-300 rounded-lg">Login</a>
          <a href="register_seller.html" class="modern bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105">Join as Supplier</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-20 text-center">
      <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-12 border-4 border-white/30">
        <h1 class="handwritten text-6xl font-bold mb-6 text-white drop-shadow-lg">
          Welcome to Bhojan Bazaar
        </h1>
        <p class="modern text-xl mb-8 text-orange-100 drop-shadow">
          Where Traditional Street Food Culture Meets Modern Technology
        </p>
        <p class="modern text-lg mb-12 text-white/90 max-w-3xl mx-auto">
          Connecting authentic street food vendors with trusted raw material suppliers across India. 
          Fresh ingredients, fair prices, and the taste of tradition.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
          <!-- Vendor Card -->
          <div class="card-hover bg-white/95 backdrop-blur-sm rounded-2xl p-8 text-center border-4 border-orange-200 shadow-2xl">
            <div class="text-7xl mb-6 animate-pulse">🧑‍🍳</div>
            <h3 class="handwritten text-2xl font-bold mb-4 text-orange-800">Street Food Vendors</h3>
            <p class="modern text-orange-700 mb-6 text-sm leading-relaxed">
              Find fresh, authentic ingredients for your delicious street food. 
              Connect with verified suppliers in your area.
            </p>
            <div class="space-y-2 text-xs modern text-gray-600 mb-6">
              <div>✅ Fresh vegetables & spices daily</div>
              <div>✅ Wholesale prices guaranteed</div>
              <div>✅ Direct supplier contact</div>
            </div>
            <a href="register_shop.html" class="modern bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 block">
              🧑‍🍳 Register as Vendor
            </a>
          </div>

          <!-- Supplier Card -->
          <div class="card-hover bg-white/95 backdrop-blur-sm rounded-2xl p-8 text-center border-4 border-green-200 shadow-2xl">
            <div class="text-7xl mb-6 animate-pulse">🚚</div>
            <h3 class="handwritten text-2xl font-bold mb-4 text-green-800">Raw Material Suppliers</h3>
            <p class="modern text-green-700 mb-6 text-sm leading-relaxed">
              Expand your business by supplying fresh ingredients to 
              hundreds of street food vendors.
            </p>
            <div class="space-y-2 text-xs modern text-gray-600 mb-6">
              <div>✅ Reach 500+ vendors</div>
              <div>✅ List products for free</div>
              <div>✅ Build your reputation</div>
            </div>
            <a href="register_seller.html" class="modern bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 block">
              🚚 Register as Supplier
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- How It Works -->
  <div class="py-20 bg-white/90">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="handwritten text-4xl font-bold text-center text-orange-800 mb-4">How Bhojan Bazaar Works</h2>
      <p class="modern text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        A simple, traditional approach to connecting food vendors with quality suppliers
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center card-hover bg-orange-50 p-8 rounded-2xl border-2 border-orange-200">
          <div class="bg-orange-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-orange-300">
            <span class="text-3xl">📝</span>
          </div>
          <h3 class="handwritten text-xl font-semibold mb-4 text-orange-800">Register Your Business</h3>
          <p class="modern text-gray-700 text-sm">Vendors and suppliers register with authentic business details and location</p>
        </div>

        <div class="text-center card-hover bg-green-50 p-8 rounded-2xl border-2 border-green-200">
          <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-green-300">
            <span class="text-3xl">🛒</span>
          </div>
          <h3 class="handwritten text-xl font-semibold mb-4 text-green-800">Browse & Connect</h3>
          <p class="modern text-gray-700 text-sm">Vendors browse available materials and connect directly with nearby suppliers</p>
        </div>

        <div class="text-center card-hover bg-blue-50 p-8 rounded-2xl border-2 border-blue-200">
          <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-blue-300">
            <span class="text-3xl">⭐</span>
          </div>
          <h3 class="handwritten text-xl font-semibold mb-4 text-blue-800">Rate & Build Trust</h3>
          <p class="modern text-gray-700 text-sm">Rate suppliers after transactions to build a trusted community network</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics -->
  <div class="py-16 bg-gradient-to-r from-orange-600 to-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
      <h2 class="handwritten text-3xl font-bold mb-12">Our Growing Community</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/30">
          <div class="handwritten text-4xl font-bold">500+</div>
          <div class="modern text-sm text-orange-100">Street Food Vendors</div>
        </div>
        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/30">
          <div class="handwritten text-4xl font-bold">100+</div>
          <div class="modern text-sm text-orange-100">Raw Material Suppliers</div>
        </div>
        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/30">
          <div class="handwritten text-4xl font-bold">1000+</div>
          <div class="modern text-sm text-orange-100">Products Available</div>
        </div>
        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border-2 border-white/30">
          <div class="handwritten text-4xl font-bold">4.8★</div>
          <div class="modern text-sm text-orange-100">Average Rating</div>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA Section -->
  <div class="py-20 bg-white">
    <div class="max-w-4xl mx-auto text-center px-4">
      <h2 class="handwritten text-4xl font-bold mb-6 text-orange-800">Ready to Join Bhojan Bazaar?</h2>
      <p class="modern text-xl mb-8 text-gray-600">Start your journey in the world of authentic street food business</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="register_shop.html" class="modern bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105">
          🧑‍🍳 I'm a Vendor
        </a>
        <a href="register_seller.html" class="modern bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105">
          🚚 I'm a Supplier
        </a>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-12 border-t-4 border-orange-300">
    <div class="max-w-7xl mx-auto px-4 text-center">
      <div class="flex items-center justify-center mb-6">
        <span class="text-3xl">🍛</span>
        <span class="ml-3 handwritten text-2xl font-bold text-orange-300">Bhojan Bazaar</span>
      </div>
      <p class="modern text-gray-300 mb-4">Preserving traditional street food culture through modern technology</p>
      <p class="modern text-sm text-gray-500">© 2025 Bhojan Bazaar. Connecting hearts through authentic flavors.</p>
    </div>
  </footer>

</body>
</html>
