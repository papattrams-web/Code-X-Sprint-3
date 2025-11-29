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
        /* Updated navigation bar matching products page style */
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
            <li><a href="products.php">Back to Store</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>

    <div class="content-wrapper">
        <main class="cart-container">
            <h1>Your Shopping Cart</h1>
            
            <div id="cart-items-container">
            </div>

            <div id="cart-footer" class="cart-summary" style="display:none;">
                <p><strong>Total: GHS <span id="cart-total">0.00</span></strong></p>
                <button onclick="processCheckout()" class="btn" style="margin-top: 1rem;">Confirm & Pay</button>
            </div>
        </main>
    </div>

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
                container.innerHTML = '<div class="empty-msg"><h3>Your cart is empty</h3><a href="products.php" class="btn">Go Shopping</a></div>';
                footer.style.display = 'none';
                // Redirect to products page after a short delay
                setTimeout(function() {
                    window.location.href = 'products.php';
                }, 1500);
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
            
            // If cart is now empty, redirect to products page
            if (cart.length === 0) {
                setTimeout(function() {
                    window.location.href = 'products.php';
                }, 1500);
            }
            
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
                    window.location.href = 'products.php';
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