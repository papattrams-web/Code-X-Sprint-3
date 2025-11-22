<?php
session_start();
require_once 'confi.php';

// Access Control
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header("Location: login.php");
    exit();
}

// Handle Upload
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $conn = $database->getConnection();

    $name = $_POST['productName'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $info = $_POST['info'];

    // Image Upload Logic
    $target_dir = "uploads/";
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name; // Unique name
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO Products (productName, info, price, quantity, category, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $info, $price, $quantity, $category, $target_file]);;
        $msg = "Product Added Successfully!";
    } else {
        $msg = "Error uploading image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="main.css"> </head>
<body>
    <header class="header">
        <nav class="navbar container">
            <h3>Staff Dashboard</h3>
            <a href="staff_product_page.php" class="btn">Products View</a>
            <a href="login.php" class="btn">Logout</a>
        </nav>
    </header>
    <main class="container" style="padding-top: 2rem;">
        <h1>Add New Product</h1>
        <?php if(isset($msg)) echo "<p style='color: green;'>$msg</p>"; ?>
        
        <form method="POST" enctype="multipart/form-data" style="max-width: 500px; display: flex; flex-direction: column; gap: 1rem;">
            <input type="text" name="productName" placeholder="Product Name" required style="padding: 10px;">
            <textarea name="info" placeholder="Description" required style="padding: 10px;"></textarea>
            <input type="number" step="0.01" name="price" placeholder="Price (GHS)" required style="padding: 10px;">
            <input type="number" step="1" name="quantity" placeholder="Quantity" required style="padding: 10px;">
            
            <select name="category" required style="padding: 10px;">
                <option value="Beverages">Drinks/Beverages</option>
                <option value="Toiletries">Toiletries</option>
                <option value="Snacks">Snacks</option>
                <option value="Groceries">Groceries</option>
            </select>

            <label>Product Image:</label>
            <input type="file" name="image" required>

            <button type="submit" class="btn">Upload Product</button>
        </form>
    </main>
</body>
</html>