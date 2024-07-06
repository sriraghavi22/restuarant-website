<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, name FROM staff WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['name'];
        $_SESSION['staff_id'] = $row['id'];
        header("Location: staff_dashboard.php");
        exit();
    } else {
        echo "Invalid email or password";
    }
}

$conn->close();
?>
