<?php
session_start();
require_once 'confi.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $conn = $database->getConnection();
    
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT userID, username, email, acc_password, userType FROM Users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($password, $user['acc_password'])) {
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['userType'];

            // Redirection Logic
            if($user['userType'] === 'staff') {
                header("Location: staff_dashboard.php");
            } else {
                header("Location: products.php");
            }
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="main.css">
</head>
<body class="auth-body">
    <div class="mainCard">
        <form method="POST" class="card">
            <h2>Login</h2> 
            <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
            <input type="email" name="email" placeholder="Email" class="input" required>
            <input type="password" name="password" placeholder="Password" class="input" required>
            <button type="submit">Login</button>
            <p id="paragraph">Don't have an account? <a href="signup.php">Signup</a></p>
        </form>
    </div>
</body>
</html>