<?php
session_start();
require_once ('connection.php');

// Security: Ensure only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'staff') {
    header("Location: login.php");
    exit();
}

// Fetch products
$stmt = $conn->prepare("SELECT * FROM Products");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Store - Essentials (Staff)</title>
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

        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; padding: 20px; }
        .product-card { background: white; border: 1px solid #eee; padding: 15px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .product-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;}
        .filter-bar { padding: 20px; display: flex; gap: 10px; justify-content: center; }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Essentials - Staff</div>
        <ul class="nav-links">
            <li><a href="staff_dashboard.php">List Product</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

<div class="content-wrapper">
<div class="filter-bar">
    <button class="btn btn-outline active" onclick="filterProducts('all')">All</button>
    <button class="btn btn-outline" onclick="filterProducts('Beverages')">Beverages</button>
    <button class="btn btn-outline" onclick="filterProducts('Snacks')">Snacks</button>
    <button class="btn btn-outline" onclick="filterProducts('Toiletries')">Toiletries</button>
</div>

<div class="product-grid">
    <?php foreach($products as $p): ?>
        <div class="product-card" data-category="<?php echo htmlspecialchars($p['category']); ?>">
            <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="Product">
            <h3><?php echo htmlspecialchars($p['productName']); ?></h3>
            <p>GHS <?php echo number_format($p['price'], 2); ?></p>
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
</div>

    <script>
function filterProducts(category) {
    const items = document.querySelectorAll('.product-card');
    const buttons = document.querySelectorAll('.filter-bar button');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    items.forEach(item => {
        item.style.display = (category === 'all' || item.dataset.category === category) ? 'block' : 'none';
    });
}

async function updateProduct(productId) {
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

        const res = await fetch('update_quantity.php', { method: 'POST', body: form });
        const data = await res.json();

        if (data.success) {
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