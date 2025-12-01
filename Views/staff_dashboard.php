<?php
session_start(); // Start session at the top
require_once '../php/connection.php';

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
$target_file = $target_dir . $file_name; 

if(file_exists($target_file)) {
    $msg = "File already exists. Please rename your image or use a different one.";
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Use proper MySQLi prepared statement
        $sql = "INSERT INTO Products (productName, info, price, quantity, category, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssdiss", $name, $info, $price, $quantity, $category, $target_file);
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

}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
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

        body {
    background: #f3f7ff;
    font-family: Arial, sans-serif;
}

.form-card {
    background: white;
    padding: 2rem;
    max-width: 500px;
    margin: 120px auto;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.15);
}

.form-card h1 {
    color: #0d6efd;
    text-align: center;
    margin-bottom: 1.5rem;
}

input, textarea, select {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #bcd0ff;
    font-size: 14px;
}

input:focus, textarea:focus, select:focus {
    border-color: #0d6efd;
    outline: none;
    box-shadow: 0 0 4px rgba(13,110,253,0.3);
}

.file-label {
    font-size: 14px;
    margin-top: 10px;
    color: #333;
}

.btn-primary {
    background: #0d6efd;
    color: white;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: 0.2s;
}

.btn-primary:hover {
    background: #0b5ed7;
}

.msg {
    background: #d1e7dd;
    color: #0f5132;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
}

    </style>
</head>
<body>
    <nav>
        <div class="logo">Essentials - Staff</div>
        <ul class="nav-links">
            <li><a href="homepage.html">Home</a></li>
            <li><a href="staff_product_page.php">View Products</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
        </ul>
    </nav>
    <main class="content-wrapper">
    <div class="form-card">
        <h1>Add New Product</h1>

        <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="productName" placeholder="Product Name" required>

            <textarea name="info" placeholder="Description" required></textarea>

            <input type="number" step="0.01" name="price" placeholder="Price (GHS)" required>

            <input type="number" name="quantity" placeholder="Quantity" required>

            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Beverages">Beverages</option>
                <option value="Snacks">Snacks</option>
                <option value="Groceries">Groceries</option>
                <option value="Toiletries">Toiletries</option>
            </select>

            <label class="file-label">Product Image</label>
            <input type="file" name="image" required>

            <button type="submit" class="btn-primary">Upload Product</button>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script  src="../js/logout.js" defer></script>
</body>
</html>
