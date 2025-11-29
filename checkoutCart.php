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
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
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

        .cart-container { 
            max-width: 900px; 
            margin: 2rem auto; 
            padding: 0 1rem; 
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        .cart-container h1 {
            color: #333;
            margin-bottom: 2rem;
        }

        .cart-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #eee; 
            padding: 1.5rem 0; 
        }

        .cart-details h4 { 
            margin: 0 0 0.5rem 0; 
            color: #333;
            font-size: 18px;
        }

        .cart-details span {
            color: #666;
        }

        .cart-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .cart-actions strong {
            color: #1e90ff;
            font-size: 18px;
        }

        .btn-remove { 
            background: #ff4444; 
            color: white; 
            border: none; 
            padding: 8px 16px; 
            cursor: pointer; 
            border-radius: 4px;
            transition: background 0.3s;
        }

        .btn-remove:hover {
            background: #cc0000;
        }

        .cart-summary { 
            margin-top: 2rem; 
            text-align: right; 
            font-size: 1.3rem;
            padding-top: 1rem;
            border-top: 2px solid #1e90ff;
        }

        .cart-summary p {
            margin: 1rem 0;
            color: #333;
        }

        .btn {
            padding: 12px 30px;
            background: #1e90ff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 16px;
            font-weight: 600;
        }

        .btn:hover {
            background: #1873cc;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .empty-msg { 
            text-align: center; 
            margin-top: 3rem; 
            color: #666; 
        }

        .empty-msg h3 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        .empty-msg .btn {
            display: inline-block;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <ul class="nav-links">
                <li><a href="products.php">Back to Store</a></li>
                <li><a href="homepage.html">Home</a></li>
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
            <button onclick="processCheckout()" class="btn" style="margin-top: 1rem;">Confirm & Pay</button>
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

            // Get cart from localStorage (JSON format)
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            if (cart.length === 0) {
                container.innerHTML = '<div class="empty-msg"><h3>Your cart is empty</h3><a href="main.php" class="btn">Go Shopping</a></div>';
                footer.style.display = 'none';
                return;
            }

            // Loop through cart items
            cart.forEach((item, index) => {
                let itemTotal = parseFloat(item.price) * parseInt(item.quantity);
                total += itemTotal;

                let html = `
                    <div class="cart-item" id="item-${index}">
                        <div class="cart-details">
                            <h4>${item.name}</h4>
                            <span>GHS ${parseFloat(item.price).toFixed(2)} x ${item.quantity}</span>
                        </div>
                        <div class="cart-actions">
                            <strong>GHS ${itemTotal.toFixed(2)}</strong>
                            <button class="btn-remove" onclick="removeItem(${index})">Remove</button>
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });

            footer.style.display = 'block';
            totalEl.textContent = total.toFixed(2);
        }

        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        async function processCheckout() {
            const btn = document.querySelector('#cart-footer button');
            btn.textContent = "Processing...";
            btn.disabled = true;

            // Get cart data
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            if (cart.length === 0) {
                alert("Cart is empty!");
                btn.disabled = false;
                btn.textContent = "Confirm & Pay";
                return;
            }

            // Send to backend
            try {
                let response = await fetch('process_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart: cart })
                });

                let result = await response.json();

                if (result.success) {
                    alert('Order placed successfully!');
                    localStorage.removeItem('cart');
                    window.location.href = 'main.php';
                } else {
                    alert("Order Failed: " + result.message);
                    btn.disabled = false;
                    btn.textContent = "Confirm & Pay";
                }
            } catch (error) {
                console.error(error);
                alert("Network Error");
                btn.disabled = false;
                btn.textContent = "Confirm & Pay";
            }
        }
    </script>
</body>
</html>