<?php
session_start(); // Start session at the top
require_once 'connection.php';

// Access Control: only allow staff
if (!isset($_SESSION['user_id']) || ($_SESSION['usertype'] ?? '') !== 'staff') {
    header("Location: login.php");
    exit();
}

// Handle Upload
$msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['productName'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $info = $_POST['info'];

    // Image Upload
    $target_dir = "uploads/";
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name; // Unique name

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Use proper MySQLi prepared statement
        $sql = "INSERT INTO Products (productName, info, price, quantity, category, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssdis", $name, $info, $price, $quantity, $category, $target_file);
            if ($stmt->execute()) {
                $msg = "Product Added Successfully!";
            } else {
                $msg = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $msg = "Database prepare error: " . $conn->error;
        }
    } else {
        $msg = "Error uploading image.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="main.css">
    <style>
        /* Updated navigation bar matching homepage style */
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
    <nav>
        <div class="logo">Essentials - Staff</div>
        <ul class="nav-links">
            <li><a href="staff_product_page.php">Products View</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
<main class="container content-wrapper" style="padding-top: 2rem;">
    <h1>Add New Product</h1>
    <?php if($msg) echo "<p style='color: green;'>$msg</p>"; ?>

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
