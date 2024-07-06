<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$restaurant_dbname = "restaurant_db";

$conn_restaurant = new mysqli($servername, $username, $password, $restaurant_dbname);
if ($conn_restaurant->connect_error) {
    die("Connection to restaurant database failed: " . $conn_restaurant->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    // Delete the order
    $delete_order_sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn_restaurant->prepare($delete_order_sql);
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        echo "error";
        $stmt->close();
        $conn_restaurant->close();
        exit();
    }
    $stmt->close();

    echo "success";
} else {
    echo "error";
}

$conn_restaurant->close();
?>
