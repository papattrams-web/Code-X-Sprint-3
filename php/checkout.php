<?php
session_start();
require_once __DIR__ . '/connection.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'] ?? "0";
$amount = $_GET['amount'] ?? "0.00";
$order_ref = "#ORD-" . str_pad($order_id, 6, "0", STR_PAD_LEFT);

// Use firstName since username column was deleted
$customerName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : "Customer";

$order_items_list = [];

// --- 1. Fetch Order Items from Database ---
if ($order_id != "0" && is_numeric($order_id)) {
    // Join orderItems with Products to get the name and quantity
    $sql = "
        SELECT 
            oi.quantity, 
            p.productName
        FROM orderItems oi
        JOIN Products p ON oi.productID = p.productID
        WHERE oi.orderID = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Build a concise item list for the QR code
        $order_items_list[] = [
            'name' => $row['productName'],
            'qty' => $row['quantity']
        ];
    }
}
// --- END Fetch Order Items ---

// --- 2. Build QR Code Content ---
// The QR code content is now a JSON object containing the summary AND the item list.
$qr_content_data = [
    "ID" => $order_ref,
    "User" => $customerName,
    "Total" => "GHS " . $amount,
    "Status" => "PAID",
    "Items" => $order_items_list // ADDED: List of items
];

$qr_content = json_encode($qr_content_data);
// --- END Build QR Code Content ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmed</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        .confirmation-card { max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .detail-row { display: flex; justify-content: space-between; margin: 15px 0; border-bottom: 1px dashed #eee; padding-bottom: 10px; }
        .items-list { text-align: left; margin-top: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .items-list h4 { margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .item-entry { display: flex; justify-content: space-between; padding: 5px 0; font-size: 0.9em; }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <h3>Essentials</h3>
            <ul class="nav-links">
                <li><a href="products.php">Store</a></li>
            </ul>
        </nav>
    </header>

    <div class="confirmation-card">
        <h1 style="color: #198754;">Order Confirmed!</h1>
        <p>Thank you, <?php echo htmlspecialchars($customerName); ?>.</p>
        <p>Please show this QR code at the counter.</p>

        <div id="qrcode" style="display: flex; justify-content: center; margin: 30px;"></div>

        <div class="confirmation-details">
            <div class="detail-row">
                <strong>Order Ref</strong>
                <span><?php echo $order_ref; ?></span>
            </div>
            <div class="detail-row">
                <strong>Total Paid</strong>
                <span>GHS <?php echo $amount; ?></span>
            </div>
            <div class="detail-row">
                <strong>Payment Status</strong>
                <span style="color: green; font-weight: bold;">Confirmed</span>
            </div>
        </div>
        
        <?php if (!empty($order_items_list)): ?>
        <div class="items-list">
            <h4>Order Items (for verification)</h4>
            <?php foreach ($order_items_list as $item): ?>
                <div class="item-entry">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                    <span>x<?php echo htmlspecialchars($item['qty']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <a href="products.php" class="btn" style="margin-top: 20px;">Continue Shopping</a>
    </div>

    <script>
        window.onload = function() {
            // 1. Initialize QR Code with PHP-generated JSON content
            new QRCode(document.getElementById("qrcode"), {
                text: '<?php echo $qr_content; ?>',
                width: 150,
                height: 150
            });
            
            // 2. Clear Cart from LocalStorage
            localStorage.removeItem('cart');
            localStorage.removeItem('cart-itemNo');
        };
    </script>
</body>
</html>