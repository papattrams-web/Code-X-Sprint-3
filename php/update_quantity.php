<?php
session_start();
require_once __DIR__ . '/connection.php';
header('Content-Type: application/json');

// 1. Check if user is staff
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'staff') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// 2. Get POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['productID'] ?? null;
    $qty = $_POST['quantity'] ?? null;

    if ($id && $qty !== null) {
        if ($qty == 0) {
            // 3a. Quantity is 0 → delete product and image
            // First, get the image path from database
            $stmt = $conn->prepare("SELECT image_url FROM Products WHERE productID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($image_url);
            if ($stmt->fetch()) {
                // Delete image file if exists
                if ($image_url && file_exists(__DIR__ . '/../' . $image_url)) {
                    unlink(__DIR__ . '/../' . $image_url);
                }
            }
            $stmt->close();

            // Delete product from database
            $stmtDel = $conn->prepare("DELETE FROM Products WHERE productID = ?");
            $stmtDel->bind_param("i", $id);
            if ($stmtDel->execute()) {
                echo json_encode(['success' => true, 'message' => 'Product deleted because quantity is 0']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
            }
            $stmtDel->close();

        } else {
            // 3b. Quantity > 0 → just update
            $stmt = $conn->prepare("UPDATE Products SET quantity = ? WHERE productID = ?");
            $stmt->bind_param("ii", $qty, $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Quantity updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed']);
            }
            $stmt->close();
        }

        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}
?>
