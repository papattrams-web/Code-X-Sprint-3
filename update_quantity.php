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
        // Using $conn from connection.php (assumes MySQLi)
        $stmt = $conn->prepare("UPDATE Products SET quantity = ? WHERE productID = ?");
        $stmt->bind_param("ii", $qty, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}
?>
