<?php
// callback.php
require_once __DIR__ . '/connection.php';
session_start();

// --- CONFIGURATION ---
$paystack_secret_key = "sk_test_b4ea7a38715b980df461f712bf5ac92423ffd6d3"; 
// ---------------------

$reference = isset($_GET['reference']) ? $_GET['reference'] : '';

if (!$reference) {
    die("No reference supplied");
}

// 1. Verify Transaction
$url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer " . $paystack_secret_key,
    "Cache-Control: no-cache",
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

if ($response && $response['status'] && $response['data']['status'] === 'success') {
    // PAYMENT SUCCESSFUL
    $amount_paid = $response['data']['amount'] / 100; 
    $user_id = $_SESSION['user_id'];
    $cart = isset($_SESSION['temp_cart']) ? $_SESSION['temp_cart'] : [];
    
    // Default to 'pickup' if delivery option wasn't saved in session, or fetch it
    $orderType = isset($_SESSION['deliveryOption']) ? $_SESSION['deliveryOption'] : 'pickup';

    if (empty($cart)) {
        die("Session expired. Ref: " . $reference);
    }

    $conn->begin_transaction();
    try {
        // A. Insert into ORDERS Table (No payment method here anymore)
        $order_sql = "INSERT INTO Orders (userID, totalExpense, orderStatus, orderType) VALUES (?, ?, 'confirmed', ?)";
        $stmt = $conn->prepare($order_sql);
        $stmt->bind_param("ids", $user_id, $amount_paid, $orderType);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // B. Insert into PAYMENTS Table (New Requirement)
        // Mapping Paystack to 'MobileMoney' as per your ENUM list
        $payment_sql = "INSERT INTO Payments (orderID, paymentMethod, paymentStatus, amount, transactionID) VALUES (?, 'MobileMoney', 'confirmed', ?, ?)";
        $stmt_pay = $conn->prepare($payment_sql);
        $stmt_pay->bind_param("ids", $order_id, $amount_paid, $reference);
        $stmt_pay->execute();

        // Prepare statements for Items and Inventory
        $item_sql = "INSERT INTO orderItems (orderID, productID, quantity, unitPrice) VALUES (?, ?, ?, ?)";
        // NOTE: Updating 'storeInventory', not 'Products'
        $stock_sql = "UPDATE storeInventory SET stockQuantity = stockQuantity - ? WHERE productID = ?";
        
        $stmt_item = $conn->prepare($item_sql);
        $stmt_stock = $conn->prepare($stock_sql);

        foreach ($cart as $item) {
            $p_id = $item['id'];
            $qty = $item['quantity'];
            $price = $item['price'];

            // Save Item
            $stmt_item->bind_param("iiid", $order_id, $p_id, $qty, $price);
            $stmt_item->execute();

            // Decrease Stock in Inventory Table
            $stmt_stock->bind_param("ii", $qty, $p_id);
            $stmt_stock->execute();
        }

        $conn->commit();
        
        unset($_SESSION['temp_cart']); 
        header("Location: ../Views/checkout.php?order_id=" . $order_id . "&amount=" . $amount_paid);
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error processing order: " . $e->getMessage();
    }

} else {
    header("Location: ../Views/checkoutCart.php?msg=payment_failed");
    exit();
}
?>