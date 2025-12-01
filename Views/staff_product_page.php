<?php
session_start();
require_once ('../php/connection.php');

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
    <link rel="stylesheet" href="../css/main.css">
    <style>
        /* Navigation bar */
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
            text-decoration: none;
        }

        .nav-links a:hover { color: #0d6efd; }

        .content-wrapper { margin-top: 80px; }

        .filter-bar { padding: 20px; display: flex; gap: 10px; justify-content: center; }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            background: white;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        /* Quantity input and buttons styling */
        .quantity-section {
            margin-top: 10px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .quantity-section label {
            font-size: 0.9rem;
            display: block;
            margin-bottom: 5px;
        }

        .quantity-section input[type="number"] {
            width: 100%;
            padding: 8px;
            font-size: 0.9rem;
            border-radius: 6px;
            border: 1px solid #bcd0ff;
            text-align: center;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .quantity-section input[type="number"]:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: 0 0 4px rgba(13,110,253,0.3);
        }

        .quantity-section .button-row {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            cursor: pointer;
            border: none;
            border-radius: 6px;
            padding: 7px 0;
            font-size: 0.9rem;
            flex: 1;
            transition: 0.2s;
        }

        .btn-update { background: #0d6efd; color: white; }
        .btn-update:hover { background: #0b5ed7; }

        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #b02a37; }
    </style>
</head>
<body>
<nav>
    <div class="logo">Essentials - Staff</div>
    <ul class="nav-links">
        <li><a href="staff_dashboard.php">Add Product</a></li>
        <li><a href="../php/logout.php">Logout</a></li>
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
            <img src="../<?php echo htmlspecialchars($p['image_url']); ?>" alt="Product">
            <h3><?php echo htmlspecialchars($p['productName']); ?></h3>
            <p>GHS <?php echo number_format($p['price'], 2); ?></p>
            <p id="display_qty_<?php echo $p['productID']; ?>" style="font-weight:bold; color:#0d6efd;">
                Available: <?php echo $p['quantity']; ?>
            </p>

            <!-- Quantity input and buttons -->
            <div class="quantity-section">
                <label for="input_qty_<?php echo $p['productID']; ?>">Set New Quantity:</label>
                <input type="number" id="input_qty_<?php echo $p['productID']; ?>" value="<?php echo $p['quantity']; ?>" min="0">

                <div class="button-row">
                    <button class="btn btn-update" onclick="updateProduct(<?php echo $p['productID']; ?>)">Update</button>
                    <button class="btn btn-delete" onclick="deleteProduct(<?php echo $p['productID']; ?>, '<?php echo addslashes($p['image_url']); ?>')">Delete</button>
                </div>
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
    if (isNaN(newQty) || newQty < 0) { alert('Please enter a valid quantity.'); return; }

    try {
        const form = new FormData();
        form.append('productID', productId);
        form.append('quantity', newQty);

        const res = await fetch('../php/update_quantity.php', { method: 'POST', body: form });
        const data = await res.json();

        if (data.success) {
            document.getElementById('display_qty_' + productId).textContent = 'Available: ' + newQty;
            alert('Quantity updated successfully!');
        } else { alert('Error: ' + data.message); }
    } catch (err) { console.error(err); alert('Network error occurred.'); }
}

async function deleteProduct(productId, imageUrl) {
    if (!confirm('Are you sure you want to delete this product?')) return;

    try {
        const form = new FormData();
        form.append('productID', productId);
        form.append('imageUrl', imageUrl);

        const res = await fetch('../php/delete_product.php', { method: 'POST', body: form });
        const data = await res.json();

        if (data.success) {
            const card = document.querySelector(`.product-card input[id='input_qty_${productId}']`).closest('.product-card');
            card.remove();
            alert('Product deleted successfully!');
        } else { alert('Error: ' + data.message); }
    } catch (err) { console.error(err); alert('Network error occurred.'); }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/logout.js" defer></script>
</body>
</html>
