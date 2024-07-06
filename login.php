<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "your_database_name";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, email, password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: menu.html"); 
            exit;
        } else {
            echo "Invalid credentials";
        }
    } else {
        echo "Invalid credentials";
    }
}

$conn->close();
?>
