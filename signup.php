<?php
require_once 'confi.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $conn = $database->getConnection();

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $phone = $_POST["phone"] ?? '';
    $userType = $_POST["userType"]; // Get the role

    $query = "INSERT INTO Users (username, email, acc_password, phoneNo, userType) VALUES (:username, :email, :password, :phone, :userType)";
    
    try {
        $stmt = $conn->prepare($query);
        // PDO Binding
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':userType', $userType);

        if($stmt->execute()) {
            header("Location: login.php?msg=registered");
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up - Essentials</title>
    <link rel="stylesheet" href="main.css">
</head>
<body class="auth-body">
    <div class="mainCard">
        <form method="POST" class="card">
            <h2>Sign Up</h2> 
            <input type="text" name="username" placeholder="Username" class="input" required>
            <input type="email" name="email" placeholder="Email" class="input" required>
            <input type="text" name="phone" placeholder="Phone Number" class="input">
            
            <select name="userType" class="input" required style="background: transparent; color: white;">
                <option value="customer" style="color:black;">Student / Faculty</option>
                <option value="staff" style="color:black;">Essentials Staff</option>
            </select>

            <input type="password" name="password" placeholder="Create Password" class="input" required>
            <button type="submit">Sign Up</button>
            
            <p id="paragraph">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>