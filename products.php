<?php
session_start();
require_once 'connection.php';

// Fetch products using MySQLi (not PDO)
$stmt = $conn->prepare("SELECT * FROM Products");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Store - Essentials</title>
    <link rel="stylesheet" href="main.css">
    <style>
        /* Simple Grid for Products */
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 20px; 
            padding: 20px; 
        }
        .product-card { 
            border: 1px solid #ddd; 
            padding: 10px; 
            border-radius: 8px; 
            text-align: center; 
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .product-card img { 
            width: 100%; 
            height: 150px; 
            object-fit: cover; 
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .filter-bar { 
            padding: 20px; 
            display: flex; 
            gap: 10px; 
            justify-content: center; 
            flex-wrap: wrap;
        }
        .filter-btn { 
            padding: 8px 16px; 
            cursor: pointer; 
            background: #ddd; 
            border: none; 
            border-radius: 4px; 
            transition: all 0.3s;
        }
        .filter-btn.active { 
            background: #1e90ff; 
            color: white; 
        }
        .filter-btn:hover {
            background: #1873cc;
            color: white;
        }
        .btn {
            padding: 8px 16px;
            background: #1e90ff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #1873cc;
        }
        .header {
            background: #1e90ff;
            color: white;
            padding: 15px 0;
        }
        .navbar {
            max-width: 1200px;
            margin: 0 auto;
        }
        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .nav-links a:hover {
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <ul class="nav-links">
                <li><a href="homepage.html">Home</a></li>
                <li><a href="checkoutCart.php">Cart (<span id="cart-count">0</span>)</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="filter-bar">
        <button class="filter-btn active" onclick="filterProducts('all')">All</button>
        <button class="filter-btn" onclick="filterProducts('Food')">Food</button>
        <button class="filter-btn" onclick="filterProducts('Beverages')">Beverages</button>
        <button class="filter-btn" onclick="filterProducts('Snacks')">Snacks</button>
        <button class="filter-btn" onclick="filterProducts('Toiletries')">Toiletries</button>
    </div>

    <div class="product-grid">
        <?php if (empty($products)): ?>
            <p style="grid-column: 1/-1; text-align: center; padding: 40px;">
                No products found. Please add products to your database.
            </p>
        <?php else: ?>
            <?php foreach($products as $p): ?>
                <div class="product-card" data-category="<?php echo htmlspecialchars($p['category']); ?>">
                    <img src="<?php echo htmlspecialchars($p['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($p['productName']); ?>"
                         onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                    <h3><?php echo htmlspecialchars($p['productName']); ?></h3>
                    <p>GHS <?php echo number_format($p['price'], 2); ?></p>
                    <button class="btn" onclick="addToCart(<?php echo $p['productID']; ?>, '<?php echo htmlspecialchars($p['productName'], ENT_QUOTES); ?>', <?php echo $p['price']; ?>)">
                        Add to Cart
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // 1. Filtering Logic (No Page Reload)
        function filterProducts(category) {
            const items = document.querySelectorAll('.product-card');
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // 2. Add To Cart Logic (Using JSON in LocalStorage)
        function addToCart(id, name, price) {
            // Get existing cart or create new one
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Check if item already exists
            let existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                // Increase quantity if item exists
                existingItem.quantity++;
            } else {
                // Add new item
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1
                });
            }
            
            // Save cart
            localStorage.setItem('cart', JSON.stringify(cart));
            
            updateCartCount();
            alert(name + " added to cart!");
        }

        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').textContent = totalItems;
        }

        window.onload = updateCartCount;
    </script>
</body>
</html>