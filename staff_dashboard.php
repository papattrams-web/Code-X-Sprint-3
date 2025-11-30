<?php
session_start(); // Start session at the top
require_once 'connection.php';

// Access Control: only allow staff
if (!isset($_SESSION['user_id']) || ($_SESSION['usertype'] ?? '') !== 'staff') {
    header("Location: login.php");
    exit();
}

// Function to generate image URL using Unsplash
function getProductImage($productName, $category) {
    $searchTerm = urlencode(strtolower($productName . ' ' . $category . ' product'));
    return "https://source.unsplash.com/400x300/?product,grocery," . $searchTerm;
}

// Handle Bulk Product Insertion
$bulkMsg = '';
if (isset($_POST['bulk_insert'])) {
    $products = [
        // Beverages
        ['productName' => 'Coca Cola 500ml', 'category' => 'Beverages', 'price' => 3.50, 'quantity' => 50, 'info' => 'Refreshing carbonated soft drink'],
        ['productName' => 'Sprite 500ml', 'category' => 'Beverages', 'price' => 3.50, 'quantity' => 45, 'info' => 'Lemon-lime flavored soft drink'],
        ['productName' => 'Fanta Orange 500ml', 'category' => 'Beverages', 'price' => 3.50, 'quantity' => 40, 'info' => 'Orange flavored carbonated drink'],
        ['productName' => 'Pepsi 500ml', 'category' => 'Beverages', 'price' => 3.50, 'quantity' => 35, 'info' => 'Classic cola soft drink'],
        ['productName' => 'Mineral Water 500ml', 'category' => 'Beverages', 'price' => 2.00, 'quantity' => 100, 'info' => 'Pure drinking water'],
        ['productName' => 'Orange Juice 1L', 'category' => 'Beverages', 'price' => 8.00, 'quantity' => 30, 'info' => 'Fresh orange juice'],
        ['productName' => 'Apple Juice 1L', 'category' => 'Beverages', 'price' => 8.00, 'quantity' => 25, 'info' => 'Sweet apple juice'],
        ['productName' => 'Energy Drink', 'category' => 'Beverages', 'price' => 5.00, 'quantity' => 40, 'info' => 'High energy drink'],
        ['productName' => 'Malt Drink', 'category' => 'Beverages', 'price' => 4.50, 'quantity' => 30, 'info' => 'Nutritious malt beverage'],
        ['productName' => 'Chocolate Drink', 'category' => 'Beverages', 'price' => 4.00, 'quantity' => 35, 'info' => 'Rich chocolate flavored drink'],
        
        // Snacks
        ['productName' => 'Potato Chips', 'category' => 'Snacks', 'price' => 4.50, 'quantity' => 60, 'info' => 'Crispy potato chips'],
        ['productName' => 'Plantain Chips', 'category' => 'Snacks', 'price' => 5.00, 'quantity' => 50, 'info' => 'Local favorite plantain chips'],
        ['productName' => 'Biscuits', 'category' => 'Snacks', 'price' => 3.00, 'quantity' => 80, 'info' => 'Sweet biscuits'],
        ['productName' => 'Cookies', 'category' => 'Snacks', 'price' => 4.00, 'quantity' => 70, 'info' => 'Chocolate cookies'],
        ['productName' => 'Peanuts', 'category' => 'Snacks', 'price' => 3.50, 'quantity' => 45, 'info' => 'Roasted peanuts'],
        ['productName' => 'Crackers', 'category' => 'Snacks', 'price' => 3.50, 'quantity' => 55, 'info' => 'Salty crackers'],
        ['productName' => 'Popcorn', 'category' => 'Snacks', 'price' => 4.00, 'quantity' => 40, 'info' => 'Buttered popcorn'],
        ['productName' => 'Nuts Mix', 'category' => 'Snacks', 'price' => 6.00, 'quantity' => 35, 'info' => 'Mixed nuts'],
        ['productName' => 'Chocolate Bar', 'category' => 'Snacks', 'price' => 5.50, 'quantity' => 50, 'info' => 'Milk chocolate bar'],
        ['productName' => 'Candy', 'category' => 'Snacks', 'price' => 2.50, 'quantity' => 100, 'info' => 'Assorted candies'],
        
        // Groceries - Canned Goods
        ['productName' => 'Canned Beans', 'category' => 'Groceries', 'price' => 6.50, 'quantity' => 40, 'info' => 'Canned baked beans'],
        ['productName' => 'Canned Tomatoes', 'category' => 'Groceries', 'price' => 5.00, 'quantity' => 35, 'info' => 'Canned diced tomatoes'],
        ['productName' => 'Canned Corn', 'category' => 'Groceries', 'price' => 5.50, 'quantity' => 30, 'info' => 'Sweet corn in can'],
        ['productName' => 'Canned Sardines', 'category' => 'Groceries', 'price' => 8.00, 'quantity' => 25, 'info' => 'Canned sardines in oil'],
        ['productName' => 'Canned Tuna', 'category' => 'Groceries', 'price' => 9.00, 'quantity' => 20, 'info' => 'Canned tuna fish'],
        
        // Groceries - Packaged Foods
        ['productName' => 'Instant Noodles', 'category' => 'Groceries', 'price' => 3.50, 'quantity' => 80, 'info' => 'Quick instant noodles'],
        ['productName' => 'Pasta', 'category' => 'Groceries', 'price' => 7.00, 'quantity' => 45, 'info' => 'Spaghetti pasta'],
        ['productName' => 'Rice 1kg', 'category' => 'Groceries', 'price' => 12.00, 'quantity' => 50, 'info' => 'Long grain rice'],
        ['productName' => 'Cereal', 'category' => 'Groceries', 'price' => 15.00, 'quantity' => 30, 'info' => 'Breakfast cereal'],
        ['productName' => 'Oats', 'category' => 'Groceries', 'price' => 10.00, 'quantity' => 25, 'info' => 'Rolled oats'],
        ['productName' => 'Flour 1kg', 'category' => 'Groceries', 'price' => 8.00, 'quantity' => 40, 'info' => 'All-purpose flour'],
        ['productName' => 'Sugar 1kg', 'category' => 'Groceries', 'price' => 6.50, 'quantity' => 50, 'info' => 'Granulated sugar'],
        ['productName' => 'Salt', 'category' => 'Groceries', 'price' => 2.50, 'quantity' => 60, 'info' => 'Table salt'],
        ['productName' => 'Cooking Oil 1L', 'category' => 'Groceries', 'price' => 18.00, 'quantity' => 35, 'info' => 'Vegetable cooking oil'],
        ['productName' => 'Baking Powder', 'category' => 'Groceries', 'price' => 4.00, 'quantity' => 30, 'info' => 'Baking powder'],
        
        // Groceries - Condiments
        ['productName' => 'Ketchup', 'category' => 'Groceries', 'price' => 5.50, 'quantity' => 40, 'info' => 'Tomato ketchup'],
        ['productName' => 'Mayonnaise', 'category' => 'Groceries', 'price' => 6.00, 'quantity' => 35, 'info' => 'Creamy mayonnaise'],
        ['productName' => 'Hot Sauce', 'category' => 'Groceries', 'price' => 4.50, 'quantity' => 30, 'info' => 'Spicy hot sauce'],
        ['productName' => 'Soy Sauce', 'category' => 'Groceries', 'price' => 5.00, 'quantity' => 25, 'info' => 'Dark soy sauce'],
        ['productName' => 'Pepper', 'category' => 'Groceries', 'price' => 3.00, 'quantity' => 40, 'info' => 'Black pepper'],
        ['productName' => 'Curry Powder', 'category' => 'Groceries', 'price' => 4.00, 'quantity' => 30, 'info' => 'Curry spice mix'],
        
        // Toiletries
        ['productName' => 'Toothpaste', 'category' => 'Toiletries', 'price' => 8.00, 'quantity' => 50, 'info' => 'Fluoride toothpaste'],
        ['productName' => 'Toothbrush', 'category' => 'Toiletries', 'price' => 3.50, 'quantity' => 60, 'info' => 'Soft bristle toothbrush'],
        ['productName' => 'Shampoo', 'category' => 'Toiletries', 'price' => 12.00, 'quantity' => 40, 'info' => 'Hair shampoo'],
        ['productName' => 'Conditioner', 'category' => 'Toiletries', 'price' => 12.00, 'quantity' => 35, 'info' => 'Hair conditioner'],
        ['productName' => 'Body Soap', 'category' => 'Toiletries', 'price' => 5.00, 'quantity' => 70, 'info' => 'Bar soap'],
        ['productName' => 'Body Lotion', 'category' => 'Toiletries', 'price' => 15.00, 'quantity' => 30, 'info' => 'Moisturizing body lotion'],
        ['productName' => 'Deodorant', 'category' => 'Toiletries', 'price' => 10.00, 'quantity' => 40, 'info' => 'Antiperspirant deodorant'],
        ['productName' => 'Toilet Paper', 'category' => 'Toiletries', 'price' => 8.50, 'quantity' => 50, 'info' => 'Soft toilet paper'],
        ['productName' => 'Tissue Paper', 'category' => 'Toiletries', 'price' => 4.00, 'quantity' => 60, 'info' => 'Facial tissue'],
        ['productName' => 'Hand Sanitizer', 'category' => 'Toiletries', 'price' => 6.00, 'quantity' => 45, 'info' => 'Alcohol-based sanitizer'],
        ['productName' => 'Razor', 'category' => 'Toiletries', 'price' => 5.50, 'quantity' => 40, 'info' => 'Disposable razor'],
        ['productName' => 'Shaving Cream', 'category' => 'Toiletries', 'price' => 7.00, 'quantity' => 30, 'info' => 'Foaming shaving cream'],
        
        // Cleaning Supplies
        ['productName' => 'Dish Soap', 'category' => 'Groceries', 'price' => 6.50, 'quantity' => 40, 'info' => 'Liquid dishwashing soap'],
        ['productName' => 'Laundry Detergent', 'category' => 'Groceries', 'price' => 20.00, 'quantity' => 25, 'info' => 'Powder laundry detergent'],
        ['productName' => 'Sponges', 'category' => 'Groceries', 'price' => 3.00, 'quantity' => 50, 'info' => 'Cleaning sponges'],
        ['productName' => 'Bleach', 'category' => 'Groceries', 'price' => 5.50, 'quantity' => 30, 'info' => 'Household bleach'],
        ['productName' => 'Air Freshener', 'category' => 'Groceries', 'price' => 8.00, 'quantity' => 25, 'info' => 'Room air freshener'],
    ];

    $successCount = 0;
    $errorCount = 0;
    $errors = [];

    $sql = "INSERT INTO Products (productName, info, price, quantity, category, image_url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        foreach ($products as $product) {
            // Generate image URL using Unsplash
            $imageUrl = getProductImage($product['productName'], $product['category']);
            
            $stmt->bind_param("ssdiss", 
                $product['productName'],
                $product['info'],
                $product['price'],
                $product['quantity'],
                $product['category'],
                $imageUrl
            );
            
            if ($stmt->execute()) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = $product['productName'] . ': ' . $stmt->error;
            }
        }
        $stmt->close();
        
        if ($successCount > 0) {
            $bulkMsg = "✅ Successfully added $successCount products!";
        }
        if ($errorCount > 0) {
            $bulkMsg .= " ❌ $errorCount products failed to insert.";
        }
    } else {
        $bulkMsg = "Database prepare error: " . $conn->error;
    }
}

// Handle Upload
$msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['bulk_insert'])) {
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
        
        .bulk-section {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #0d6efd;
        }
        
        .bulk-btn {
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .bulk-btn:hover {
            background: #218838;
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
    <?php if($bulkMsg) echo "<p style='color: green; padding: 10px; background: #d4edda; border-radius: 5px;'>$bulkMsg</p>"; ?>

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
    
    <div class="bulk-section">
        <h2>🚀 Quick Add: Bulk Products</h2>
        <p>Add 50+ convenience store products at once with automatic images!</p>
        <form method="POST" style="margin-top: 15px;">
            <input type="hidden" name="bulk_insert" value="1">
            <button type="submit" class="bulk-btn" onclick="return confirm('This will add 50+ products to your database. Continue?');">
                Add 50+ Products Now
            </button>
        </form>
    </div>
</main>
</body>
</html>
