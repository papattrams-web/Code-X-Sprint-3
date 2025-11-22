<?php
session_start();
// 1. Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Cart - Essentials</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <style>
        /* Quick inline styles for the cart layout */
        .cart-container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding: 1rem 0; }
        .cart-details h4 { margin: 0 0 0.5rem 0; }
        .btn-remove { background: #ff4444; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px; }
        .cart-summary { margin-top: 2rem; text-align: right; font-size: 1.2rem; }
        .empty-msg { text-align: center; margin-top: 3rem; color: #666; }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <ul class="nav-links">
                <li><a href="products.php">Back to Store</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="cart-container">
        <h1>Your Shopping Cart</h1>
        
        <div id="cart-items-container">
            </div>

        <div id="cart-footer" class="cart-summary" style="display:none;">
            <p><strong>Total: GHS <span id="cart-total">0.00</span></strong></p>
            <button onclick="processCheckout()" class="btn" style="margin-top: 1rem;">Confirm & Pay (Simulated)</button>
        </div>
    </main>

    <script>
        // Load Cart on Page Load
        window.onload = function() {
            renderCart();
        };

        function renderCart() {
            const container = document.getElementById('cart-items-container');
            const footer = document.getElementById('cart-footer');
            const totalEl = document.getElementById('cart-total');
            
            container.innerHTML = '';
            let total = 0;
            let itemCount = localStorage.getItem('cart-itemNo') ? parseInt(localStorage.getItem('cart-itemNo')) : 0;
            let hasItems = false;

            if (itemCount === 0) {
                container.innerHTML = '<div class="empty-msg"><h3>Your cart is empty</h3><a href="products.php" class="btn">Go Shopping</a></div>';
                footer.style.display = 'none';
                return;
            }

            // Loop through localStorage to find items
            for (let i = 0; i <= itemCount; i++) {
                let name = localStorage.getItem('cart-item_' + i + '_name');
                let price = localStorage.getItem('cart-item_' + i + '_price');
                let qty = localStorage.getItem('cart-item_' + i + '_quantity');

                if (name) { // If item exists (wasn't deleted)
                    hasItems = true;
                    let itemTotal = parseFloat(price) * parseInt(qty);
                    total += itemTotal;

                    let html = `
                        <div class="cart-item" id="item-${i}">
                            <div class="cart-details">
                                <h4>${name}</h4>
                                <span>GHS ${price} x ${qty}</span>
                            </div>
                            <div class="cart-actions">
                                <strong>GHS ${itemTotal.toFixed(2)}</strong>
                                <button class="btn-remove" onclick="removeItem(${i})">Remove</button>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                }
            }

            if (!hasItems) {
                container.innerHTML = '<div class="empty-msg"><h3>Your cart is empty</h3><a href="products.php" class="btn">Go Shopping</a></div>';
                footer.style.display = 'none';
                // Reset counter if truly empty
                localStorage.setItem('cart-itemNo', 0);
            } else {
                footer.style.display = 'block';
                totalEl.textContent = total.toFixed(2);
                localStorage.setItem('orderTotal', total.toFixed(2)); // Save for checkout page
            }
        }

        function removeItem(index) {
            localStorage.removeItem('cart-item_' + index + '_name');
            localStorage.removeItem('cart-item_' + index + '_price');
            localStorage.removeItem('cart-item_' + index + '_quantity');
            renderCart(); // Re-render to update view
        }

        async function processCheckout() {
            const btn = document.querySelector('#cart-footer button');
            btn.textContent = "Processing...";
            btn.disabled = true;

            // 1. Gather Cart Data from LocalStorage
            let orderItems = [];
            let itemCount = localStorage.getItem('cart-itemNo') ? parseInt(localStorage.getItem('cart-itemNo')) : 0;

            for (let i = 0; i <= itemCount; i++) {
                let id = localStorage.getItem('cart-item_' + i + '_id');
                let qty = localStorage.getItem('cart-item_' + i + '_quantity');
                
                // Only add if the item exists (wasn't deleted)
                if (id && qty) {
                    orderItems.push({
                        id: parseInt(id),
                        quantity: parseInt(qty)
                    });
                }
            }

            if (orderItems.length === 0) {
                alert("Cart is empty!");
                btn.disabled = false;
                return;
            }

            // 2. Send Data to PHP Backend
            try {
                let response = await fetch('process_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ items: orderItems })
                });

                let result = await response.json();

                if (result.success) {
                    // 3. If DB update worked, redirect to receipt
                    window.location.href = 'checkout.php';
                } else {
                    alert("Order Failed: " + result.message);
                    btn.disabled = false;
                    btn.textContent = "Confirm & Pay";
                }
            } catch (error) {
                console.error(error);
                alert("Network Error");
                btn.disabled = false;
            }
        }
        
        window.onload = function() {
            renderCart();
        };

    </script>
</body>
</html>