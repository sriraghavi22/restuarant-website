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
$contact_form_dbname = "contact_form_db";

$conn_restaurant = new mysqli($servername, $username, $password, $restaurant_dbname);
if ($conn_restaurant->connect_error) {
    die("Connection to restaurant database failed: " . $conn_restaurant->connect_error);
}

$conn_contact_form = new mysqli($servername, $username, $password, $contact_form_dbname);
if ($conn_contact_form->connect_error) {
    die("Connection to contact form database failed: " . $conn_contact_form->connect_error);
}

$order_sql = "SELECT orders.id, customers.name AS customer_name, orders.item_name, orders.quantity, orders.price 
              FROM orders 
              JOIN customers ON orders.customer_id = customers.id";
$order_result = $conn_restaurant->query($order_sql);

$customer_sql = "SELECT * FROM customers";
$customer_result = $conn_restaurant->query($customer_sql);

$submissions_sql = "SELECT * FROM submissions";
$submissions_result = $conn_contact_form->query($submissions_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .sidebar {
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      width: 200px;
      background-color: #343a40;
      color: white;
      padding: 15px;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 10px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #007bff;
    }
    .content {
      margin-left: 220px;
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Staff Dashboard</h2>
    <a href="#overview">Overview</a>
    <a href="#orders">Orders</a>
    <a href="#submissions">Contact Form</a>
    <!-- <a href="#reservations">Reservations</a>
    <a href="#menu">Menu</a>
    <a href="#staff">Staff</a>
    <a href="#reports">Reports</a>
    <a href="#settings">Settings</a> -->
  </div>
  <div class="content">
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <section id="overview">
      <h2>Overview</h2>
      <p>Today's tasks.</p>
    </section>
    <section id="orders">
    <h2>Orders</h2>
    <?php
    if ($order_result->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>";
        while($row = $order_result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['customer_name']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                    <td><button class='btn btn-danger delete-order' data-order-id='{$row['id']}'>Delivered</button></td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No orders found.</p>";
    }
    ?>
</section>


    <!-- <section id="reservations">
      <h2>Reservations</h2>
      <p>Manage and track customer reservations.</p>
    </section>
    <section id="menu">
      <h2>Menu</h2>
      <p>Manage the restaurant menu items.</p>
    </section>
    <section id="staff">
      <h2>Staff</h2>
      <p>Manage staff details and schedules.</p>
    </section>
    <section id="reports">
      <h2>Reports</h2>
      <p>View and generate reports.</p>
    </section>
    <section id="settings">
      <h2>Settings</h2>
      <p>Manage system settings and profile.</p>
    </section> -->
    <section id="customers">
      <h2>Customers</h2>
      <?php
      if ($customer_result->num_rows > 0) {
          echo "<table class='table table-bordered'>
                  <thead>
                    <tr>
                      <th>Customer ID</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Address</th>
                    </tr>
                  </thead>
                  <tbody>";
          while($row = $customer_result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['phone']}</td>
                      <td>{$row['address']}</td>
                    </tr>";
          }
          echo "</tbody></table>";
      } else {
          echo "<p>No customers found.</p>";
      }
      ?>
    </section>
    <section id="submissions">
      <h2>Contact Form</h2>
      <?php
      if ($submissions_result->num_rows > 0) {
          echo "<table class='table table-bordered'>
                  <thead>
                    <tr>
                      <th>Submission ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Message</th>
                      <th>Created At</th>
                    </tr>
                  </thead>
                  <tbody>";
          while($row = $submissions_result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['message']}</td>
                      <td>{$row['created_at']}</td>
                    </tr>";
          }
          echo "</tbody></table>";
      } else {
          echo "<p>No form submissions found.</p>";
      }
      ?>
    </section>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll(".delete-order");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function() {
            const orderId = this.getAttribute("data-order-id");

            if (confirm("Are you sure this order is delivered?")) {
                fetch('delete_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=${orderId}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        location.reload(); // Reload the page to update the orders list
                    } else {
                        alert("Error deleting order.");
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});
</script>

</body>
</html>

<?php
$conn_restaurant->close();
$conn_contact_form->close();
?>

