<?php
// update_quantity.php
session_start();
require_once __DIR__ . '/confi.php';

header('Content-Type: application/json');

// 1. Check if user is staff
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// 2. Get POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['productID'] ?? null;
    $qty = $_POST['quantity'] ?? null;

    if ($id && $qty !== null) {
        $database = new Database();
        $conn = $database->getConnection();

        try {
            // 3. Update Database
            $query = "UPDATE Products SET quantity = :qty WHERE productID = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':qty', $qty);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}
?>