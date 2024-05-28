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

$sql = "SELECT ProductName, ProductDesc, Price, StockQuantity, ImageURL FROM product";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product = array(
            'ProductName' => $row['ProductName'],
            'ProductDesc' => $row['ProductDesc'],
            'Price' => $row['Price'],
            'StockQuantity' => $row['StockQuantity'],
            'ImageURL' => $row['ImageURL']
        );
        //$products[] = $row;
        $products[] = $product;
    }
}
    
echo json_encode($products);
$conn->close();
?>
