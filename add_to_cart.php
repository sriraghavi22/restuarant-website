<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $customerName = $conn->real_escape_string($_POST['customer_name']);
    $customerPhone = $conn->real_escape_string($_POST['customer_phone']);
    $customerAddress = $conn->real_escape_string($_POST['customer_address']);
    
   
    $orderItems = json_decode($_POST['order_items'], true);  

    $sql = "INSERT INTO customers (name, phone, address) VALUES ('$customerName', '$customerPhone', '$customerAddress')";

    if ($conn->query($sql) === TRUE) {
        $customerId = $conn->insert_id; 


        foreach ($orderItems as $item) {
            $itemName = $conn->real_escape_string($item['name']);
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];

            $sql = "INSERT INTO orders (customer_id, item_name, quantity, price) VALUES ($customerId, '$itemName', $quantity, $price)";
            if (!$conn->query($sql)) {
                echo json_encode(['success' => false, 'message' => 'Failed to add order item: ' . $conn->error]);
                exit;
            }
        }

        echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add customer: ' . $conn->error]);
    }
}

$conn->close();
?>
