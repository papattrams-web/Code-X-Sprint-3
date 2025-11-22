<?php
session_start();
require_once __DIR__ . '/confi.php';

// Security: Ensure only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();
$stmt = $conn->prepare("SELECT * FROM Products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Store - Essentials (Staff)</title>
    <link rel="stylesheet" href="main.css">
    <style>
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; padding: 20px; }
        .product-card { background: white; border: 1px solid #eee; padding: 15px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .product-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;}
        .filter-bar { padding: 20px; display: flex; gap: 10px; justify-content: center; }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <h3>Staff View</h3>
            <ul class="nav-links">
                <li><a href="staff_dashboard.php">List Product</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="filter-bar">
        <button class="btn btn-outline active" onclick="filterProducts('all')">All</button>
        <button class="btn btn-outline" onclick="filterProducts('Beverages')">Beverages</button>
        <button class="btn btn-outline" onclick="filterProducts('Snacks')">Snacks</button>
        <button class="btn btn-outline" onclick="filterProducts('Toiletries')">Toiletries</button>
    </div>

    <div class="product-grid">
        <?php foreach($products as $p): ?>
            <div class="product-card" data-category="<?php echo $p['category']; ?>">
                <img src="<?php echo $p['image_url']; ?>" alt="Product">
                <h3><?php echo $p['productName']; ?></h3>
                <p>GHS <?php echo $p['price']; ?></p>
                
                <p id="display_qty_<?php echo $p['productID']; ?>" style="font-weight:bold; color:#0d6efd;">
                    Available: <?php echo $p['quantity']; ?>
                </p>
                
                <div style="margin-top:10px; border-top:1px solid #eee; padding-top:10px;">
                    <label style="font-size:0.9rem;">Set New Quantity:</label>
                    <input type="number" id="input_qty_<?php echo $p['productID']; ?>" value="<?php echo $p['quantity']; ?>" style="padding: 5px; text-align:center;">
                    
                    <button class="btn" style="padding: 5px 15px; font-size: 0.9rem;" onclick="updateProduct(<?php echo $p['productID']; ?>)">Update</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function filterProducts(category) {
            const items = document.querySelectorAll('.product-card');
            // Reset buttons visual state
            const buttons = document.querySelectorAll('.filter-bar button');
            buttons.forEach(btn => btn.classList.remove('active')); // You might need CSS for .active on .btn-outline
            
            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        async function updateProduct(productId) {
            // Select the specific input for THIS product
            const input = document.getElementById('input_qty_' + productId);
            const newQty = parseInt(input.value, 10);

            if (isNaN(newQty) || newQty < 0) {
                alert('Please enter a valid quantity.');
                return;
            }

            try {
                const form = new FormData();
                form.append('productID', productId);
                form.append('quantity', newQty);

                // Call the PHP backend
                const res = await fetch('update_quantity.php', {
                    method: 'POST',
                    body: form
                });

                const data = await res.json();

                if (data.success) {
                    // Update the "Available" text to match the new value
                    document.getElementById('display_qty_' + productId).textContent = 'Available: ' + newQty;
                    alert('Quantity updated successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Network error occurred.');
            }
        }
    </script>
</body>
</html>