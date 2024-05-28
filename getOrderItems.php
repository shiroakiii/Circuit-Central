<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: *");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "circuit central";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderID = $_POST['orderID'];

// Fetch order items and product details
$orderItemsQuery = $conn->prepare("SELECT oi.Quantity, p.ProductName, p.Price
                                   FROM orderitem oi
                                   JOIN product p ON oi.ProductID = p.ProductID
                                   WHERE oi.orderID = ?");
$orderItemsQuery->bind_param("i", $orderID);
$orderItemsQuery->execute();
$orderItemsResult = $orderItemsQuery->get_result();
$orderItems = array();

if ($orderItemsResult && $orderItemsResult->num_rows > 0) {
    while ($row = $orderItemsResult->fetch_assoc()) {
        $orderItems[] = $row;
    }
}

$orderItemsQuery->close();
$conn->close();

// Output the JSON data
echo json_encode($orderItems);
?>
