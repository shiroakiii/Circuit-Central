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

$customerID = $_GET['customerID'];

// Fetch customer information
$customerQuery = $conn->prepare("SELECT FirstName, LastName, Street, City, ZipCode FROM customer 
                                 INNER JOIN address1 ON customer.AddressID = address1.AddressID
                                 WHERE CustomerID = ?");
$customerQuery->bind_param("i", $customerID);
$customerQuery->execute();
$customerQuery->bind_result($firstName, $lastName, $street, $city, $zipCode);
$customerQuery->fetch();
$customerQuery->close();

$customerData = [
    'FirstName' => $firstName,
    'LastName' => $lastName,
    'Street' => $street,
    'City' => $city,
    'ZipCode' => $zipCode
];

echo json_encode($customerData);
?>