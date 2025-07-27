
<?php
// Start session
session_start();

// Database configuration for SQLite (works better in Replit)
$db_file = __DIR__ . '/streetsupply.db';

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables if they don't exist
    $conn->exec("
        CREATE TABLE IF NOT EXISTS shops (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shop_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            phone TEXT,
            address TEXT,
            business_type TEXT,
            food_category TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS sellers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seller_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            phone TEXT,
            company_name TEXT,
            business_category TEXT,
            supply_area TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seller_id INTEGER,
            product_name TEXT NOT NULL,
            description TEXT,
            category TEXT,
            price DECIMAL(10,2),
            wholesale_price DECIMAL(10,2),
            min_order_qty INTEGER DEFAULT 1,
            stock_quantity INTEGER DEFAULT 0,
            unit TEXT DEFAULT 'kg',
            product_image TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (seller_id) REFERENCES sellers(id)
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS requests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shop_id INTEGER,
            seller_id INTEGER,
            product_id INTEGER,
            quantity INTEGER,
            message TEXT,
            status TEXT DEFAULT 'pending',
            request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (shop_id) REFERENCES shops(id),
            FOREIGN KEY (seller_id) REFERENCES sellers(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS ratings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shop_id INTEGER,
            seller_id INTEGER,
            rating INTEGER CHECK(rating >= 1 AND rating <= 5),
            review TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (shop_id) REFERENCES shops(id),
            FOREIGN KEY (seller_id) REFERENCES sellers(id)
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS cart (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shop_id INTEGER,
            product_id INTEGER,
            quantity INTEGER,
            FOREIGN KEY (shop_id) REFERENCES shops(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shop_id INTEGER,
            seller_id INTEGER,
            product_id INTEGER,
            quantity INTEGER,
            total_amount DECIMAL(10,2),
            status TEXT DEFAULT 'pending',
            order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (shop_id) REFERENCES shops(id),
            FOREIGN KEY (seller_id) REFERENCES sellers(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ");
    
    // Insert sample suppliers if not exists
    $stmt = $conn->query("SELECT COUNT(*) as count FROM sellers");
    if ($stmt->fetch()['count'] == 0) {
        $conn->exec("
            INSERT INTO sellers (seller_name, email, password, phone, company_name, business_category, supply_area) VALUES 
            ('Rajesh Kumar', 'rajesh@freshveggies.com', '\$2y\$10\$demo.hash', '+91-9876543210', 'Fresh Veggies Supply', 'Vegetables & Fruits', 'Delhi NCR'),
            ('Priya Sharma', 'priya@spiceworld.com', '\$2y\$10\$demo.hash', '+91-9876543211', 'Spice World', 'Spices & Seasonings', 'Mumbai'),
            ('Ahmed Ali', 'ahmed@grainmart.com', '\$2y\$10\$demo.hash', '+91-9876543212', 'Grain Mart', 'Grains & Pulses', 'Hyderabad')
        ");
    }
    
    // Insert sample products if not exists
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
    if ($stmt->fetch()['count'] == 0) {
        $conn->exec("
            INSERT INTO products (seller_id, product_name, description, category, price, wholesale_price, min_order_qty, stock_quantity, unit) VALUES 
            (1, 'Fresh Tomatoes', 'Premium quality fresh tomatoes perfect for street food', 'Vegetables & Fruits', 60.00, 45.00, 5, 500, 'kg'),
            (1, 'Fresh Onions', 'High quality onions, essential for most street foods', 'Vegetables & Fruits', 40.00, 35.00, 10, 300, 'kg'),
            (1, 'Green Chilies', 'Fresh green chilies for authentic taste', 'Vegetables & Fruits', 80.00, 70.00, 2, 50, 'kg'),
            (2, 'Red Chili Powder', 'Premium red chili powder for spicy dishes', 'Spices & Seasonings', 200.00, 180.00, 1, 100, 'kg'),
            (2, 'Turmeric Powder', 'Pure turmeric powder for authentic flavor', 'Spices & Seasonings', 150.00, 130.00, 1, 80, 'kg'),
            (2, 'Garam Masala', 'Traditional blend of spices', 'Spices & Seasonings', 300.00, 250.00, 1, 50, 'kg'),
            (3, 'Basmati Rice', 'Premium quality basmati rice', 'Grains & Pulses', 120.00, 100.00, 25, 1000, 'kg'),
            (3, 'Wheat Flour', 'Fresh wheat flour for rotis and parathas', 'Grains & Pulses', 45.00, 40.00, 50, 2000, 'kg')
        ");
    }

} catch(PDOException $e) {
    die("❌ Database Connection failed: " . $e->getMessage());
}
?>
