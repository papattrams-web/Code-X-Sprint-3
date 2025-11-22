<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Generate a random Order ID for this session
$orderNum = "#ADE-" . rand(100, 999) . "-" . rand(100, 999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmation...Essentials</title>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initials-scale=1.0">
    <link rel="stylesheet" href="checkout.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <ul class="nav-links">
                <li><a href="products.php">Store</a></li>
            </ul>
        </nav>
    </header>

    <main class="header">
        <div class="confirmation-card">
            <h1 class="confirmation-title">Order Confirmed</h1>
            <p>Show this QR code to the cashier for pickup.</p>

            <div id="qrcode" style="display: flex; justify-content: center; margin: 20px;"></div>

            <div class="confirmation-details">
                <div class="detail-row">
                    <strong>Order No</strong>
                    <span><?php echo $orderNum; ?></span>
                </div>
                <div class="detail-row">
                    <strong>Total Paid</strong>
                    <span id="confirmed-total">...</span>
                </div>
                <div class="detail-row">
                    <strong>Payment Method</strong>
                    <span id="payment-method">...</span>
                </div>
            </div>
            
            <div class="afterOrder">
                <a href="products.php" class="btn">Continue shopping</a>
            </div>
        </div>
    </main>

    <script>
        window.onload = function() {
            // 1. Get details from LocalStorage
            let total = localStorage.getItem('orderTotal') || '0.00';
            let method = localStorage.getItem('paymentMethod') || 'Cash';
            
            document.getElementById('confirmed-total').textContent = total;
            document.getElementById('payment-method').textContent = method;

            // 2. Generate QR Code
            // The QR code contains the Order Number and Total Price
            let qrData = "Order: <?php echo $orderNum; ?> | Total: " + total + " | User: <?php echo $_SESSION['username']; ?>";
            
            new QRCode(document.getElementById("qrcode"), {
                text: qrData,
                width: 128,
                height: 128
            });

            // 3. Clear Cart
            localStorage.removeItem('cart-itemNo');
            // Note: In a real app, you would clear specific keys, but for now this works to reset.
            localStorage.clear(); 
        };
    </script>
</body>
</html>