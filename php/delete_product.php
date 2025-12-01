<?php
session_start();
require_once 'connection.php';
header('Content-Type: application/json');

// Only staff can delete
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'staff') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get POST data
$productID = $_POST['productID'] ?? null;
$imageUrl  = $_POST['imageUrl'] ?? null;

if (!$productID) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

// Delete product image from server
if ($imageUrl && file_exists($imageUrl)) {
    unlink($imageUrl);
}

// Delete product from database
$stmt = $conn->prepare("DELETE FROM Products WHERE productID = ?");
$stmt->bind_param("i", $productID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
}
?>
