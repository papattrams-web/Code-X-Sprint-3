<?php
session_start();
require_once 'confi.php';

$database = new Database();
$conn = $database->getConnection();
$stmt = $conn->prepare("SELECT * FROM Products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Store - Essentials</title>
    <link rel="stylesheet" href="main.css">
    <style>
        /* Simple Grid for Products */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px; }
        .product-card { border: 1px solid #ddd; padding: 10px; border-radius: 8px; text-align: center; }
        .product-card img { width: 100%; height: 150px; object-fit: cover; }
        .filter-bar { padding: 20px; display: flex; gap: 10px; justify-content: center; }
        .filter-btn { padding: 8px 16px; cursor: pointer; background: #ddd; border: none; border-radius: 4px; }
        .filter-btn.active { background: #333; color: white; }
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
        <?php foreach($products as $p): ?>
            <div class="product-card" data-category="<?php echo $p['category']; ?>">
                <img src="<?php echo $p['image_url']; ?>" alt="Product">
                <h3><?php echo $p['productName']; ?></h3>
                <p>GHS <?php echo $p['price']; ?></p>
                <button class="btn" onclick="addToCart(<?php echo $p['productID']; ?>, '<?php echo $p['productName']; ?>', <?php echo $p['price']; ?>)">Add to Cart</button>
            </div>
        <?php endforeach; ?>
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

        // 2. Add To Cart Logic (Using LocalStorage)
        function addToCart(id, name, price) {
            let itemCount = localStorage.getItem('cart-itemNo') ? parseInt(localStorage.getItem('cart-itemNo')) : 0;
            
            localStorage.setItem('cart-item_' + itemCount + '_id', id);

            // Add item details
            localStorage.setItem('cart-item_' + itemCount + '_name', name);
            localStorage.setItem('cart-item_' + itemCount + '_price', price);
            localStorage.setItem('cart-item_' + itemCount + '_quantity', 1);
            
            // Update total count
            itemCount++;
            localStorage.setItem('cart-itemNo', itemCount);
            
            updateCartCount();
            alert(name + " added to cart!");
        }

        function updateCartCount() {
            let count = localStorage.getItem('cart-itemNo') || 0;
            document.getElementById('cart-count').textContent = count;
        }

        window.onload = updateCartCount;
    </script>
</body>
</html>