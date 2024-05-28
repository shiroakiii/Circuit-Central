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

$orderDateTotal = $conn->prepare("SELECT OrderDate, TotalAmount FROM orders WHERE OrderID = ?");
$orderDateTotal->bind_param("i", $orderID);
$orderDateTotal->execute();
$orderDateTotal->bind_result($OrderDate, $TotalAmount);
$orderDateTotal->fetch();
$orderDateTotal->close();

$dateTotalData = [
    'OrderDate' => $OrderDate,
    'TotalAmount' => $TotalAmount,
];

echo json_encode($dateTotalData);
?>
