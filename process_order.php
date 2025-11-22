<?php
// process_order.php
require_once __DIR__ . '/confi.php';
session_start();

header('Content-Type: application/json');

// 1. Get the JSON data sent from JavaScript
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'No items in cart']);
    exit();
}

$database = new Database();
$conn = $database->getConnection();

try {
    // 2. Loop through cart items and decrement stock
    // We use "quantity - :qty" to subtract safely
    $sql = "UPDATE Products SET quantity = quantity - :qty WHERE productID = :id";
    $stmt = $conn->prepare($sql);

    foreach ($data['items'] as $item) {
        $stmt->execute([
            ':qty' => $item['quantity'],
            ':id'  => $item['id']
        ]);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>