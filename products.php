<?php
session_start();
if(!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'customer'){
    header("location:signup.php");
}
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
        /* Updated navigation bar matching homepage style */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: #0d6efd;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: black;
            font-weight: 500;
            transition: color 150ms ease-in-out;
            text-decoration: none;
        }

        .nav-links a:hover {
            color: #0d6efd;
            text-decoration: none;
        }

        /* Content margin for fixed nav */
        .content-wrapper {
            margin-top: 80px;
        }
    </style>
</head>

<body>
    <!-- Navigation bar matching homepage style -->
    <nav>
        <div class="logo">Essentials</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="checkoutCart.php">Cart (<span id="cart-count">0</span>)</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>

    <div class="content-wrapper">
        <div class="filter-bar">
            <button class="filter-btn active" onclick="filterProducts('all')">All</button>
            <button class="filter-btn" onclick="filterProducts('Food')">Food</button>
            <button class="filter-btn" onclick="filterProducts('Beverages')">Beverages</button>
            <button class="filter-btn" onclick="filterProducts('Snacks')">Snacks</button>
            <button class="filter-btn" onclick="filterProducts('Toiletries')">Toiletries</button>
        </div>

        <div class="container">
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
        </div>
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