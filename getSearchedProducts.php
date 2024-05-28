<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$search = isset($_GET['search']) ? $_GET['search'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

$sql = "SELECT * FROM product WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND ProductName LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($brand)) {
    $sql .= " AND SUBSTRING_INDEX(ProductName, ' ', 1) = '" . $conn->real_escape_string($brand) . "'";
}

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