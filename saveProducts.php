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

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$orderID = $_POST['orderID'];
$products = json_decode($_POST['products'], true);

foreach ($products as $product) {
    $productName = $product['productName'];
    $quantity = $product['quantity'];
    
    $productIDQuery = $conn->prepare("SELECT ProductID FROM product WHERE ProductName = ?");
    $productIDQuery->bind_param("s", $productName); 
    $productIDQuery->execute();
    $productIDQuery->bind_result($productID);
    $productIDQuery->fetch();
    $productIDQuery->close();

    if ($productID) {
        $insertOrderItemQuery = $conn->prepare("INSERT INTO orderitem (OrderID, ProductID, Quantity) VALUES (?, ?, ?)");
        $insertOrderItemQuery->bind_param("iii", $orderID, $productID, $quantity);
        if (!$insertOrderItemQuery->execute()) {
            error_log("Error inserting order item: " . $insertOrderItemQuery->error);
        }
        $insertOrderItemQuery->close();
    } else {
        error_log("Product ID not found for product: " . $product['productName']);
    }
}

$customerQuery = $conn->prepare("SELECT CustomerID FROM customer WHERE FirstName = ? AND LastName = ?");
$customerQuery->bind_param("ss", $firstName, $lastName);
$customerQuery->execute();
$customerQuery->bind_result($customerID);
$customerQuery->fetch();
$customerQuery->close();

echo $customerID;
?>
