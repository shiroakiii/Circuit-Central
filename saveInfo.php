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
$street = $_POST['street'];
$city = $_POST['city'];
$zipcode = $_POST['zipcode'];
$orderDate = $_POST['date'];
$totalAmount = $_POST['totalAmount'];

$addressQuery = $conn->prepare("SELECT AddressID FROM address1 WHERE Street = ? AND City = ? AND ZipCode = ?");
$addressQuery->bind_param("sss", $street, $city, $zipcode);
$addressQuery->execute();
$addressQuery->store_result();

if ($addressQuery->num_rows > 0) {
    $addressQuery->bind_result($addressID);
    $addressQuery->fetch();
} else {
    $insertAddressQuery = $conn->prepare("INSERT INTO address1 (Street, City, ZipCode) VALUES (?, ?, ?)");
    $insertAddressQuery->bind_param("sss", $street, $city, $zipcode);
    $insertAddressQuery->execute();
    $addressID = $insertAddressQuery->insert_id;
    $insertAddressQuery->close();
}

$addressQuery->close();

$customerQuery = $conn->prepare("SELECT CustomerID FROM customer WHERE FirstName = ? AND LastName = ? AND AddressID = ?");
$customerQuery->bind_param("ssi", $firstName, $lastName, $addressID);
$customerQuery->execute();
$customerQuery->store_result();

if ($customerQuery->num_rows > 0) {
    $customerQuery->bind_result($customerID);
    $customerQuery->fetch();
} else {
    $insertCustomerQuery = $conn->prepare("INSERT INTO customer (FirstName, LastName, AddressID) VALUES (?, ?, ?)");
    $insertCustomerQuery->bind_param("ssi", $firstName, $lastName, $addressID);
    $insertCustomerQuery->execute();
    $customerID = $insertCustomerQuery->insert_id;
    $insertCustomerQuery->close();
}

$customerQuery->close();

$insertOrderQuery = $conn->prepare("INSERT INTO orders (CustomerID, OrderDate, TotalAmount) VALUES (?, ?, ?)");
$insertOrderQuery->bind_param("iss", $customerID, $orderDate, $totalAmount);
$insertOrderQuery->execute();
$orderID = $insertOrderQuery->insert_id;
$insertOrderQuery->close();
echo $orderID;

?>
