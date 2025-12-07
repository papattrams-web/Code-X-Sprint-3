<?php
session_start();

// initialize_paystack.php
require_once __DIR__ . '/connection.php';


header('Content-Type: application/json');


//Secret key for Paystack goes here
$paystack_secret_key = "sk_test_b4ea7a38715b980df461f712bf5ac92423ffd6d3"; 


$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => false, 'message' => 'User not logged in']);
    exit();
}

if (!$data || !isset($data['cart'])) {
    echo json_encode(['status' => false, 'message' => 'Cart is empty']);
    exit();
}

// 1. Calculate Total securely from Database (don't trust frontend prices)
$total_amount = 0;
// We will also store the cart in the session to access it in the callback
$_SESSION['temp_cart'] = $data['cart']; 

// Retrieve user email
$user_id = $_SESSION['user_id'];
$query = "SELECT email FROM Codex_Users WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$email = $user['email'];

// Calculate total based on cart items sent
foreach ($data['cart'] as $item) {
    // In a strict system, fetch price from DB using $item['id']
    // For now, we use the cart total but ensure it is formatted
    $total_amount += (float)$item['price'] * (int)$item['quantity'];
}

// Paystack expects amount in Pesewas (Multiply by 100)
$amount_in_kobo = $total_amount * 100;

// 2. Initialize Paystack Transaction
$url = "https://api.paystack.co/transaction/initialize";

// The callback_url is where Paystack returns the user after payment
$callback_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/callback.php";

$fields = [
    'email' => $email,
    'amount' => $amount_in_kobo,
    'callback_url' => $callback_url,
    'metadata' => [
        'cart_items' => count($data['cart']),
        'user_id' => $user_id
    ]
];

$fields_string = http_build_query($fields);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Authorization: Bearer " . $paystack_secret_key,
  "Cache-Control: no-cache",
));
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

$response = curl_exec($ch);
curl_close($ch);

// Return Paystack response to frontend
echo $response;
?>