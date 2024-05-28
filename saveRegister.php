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

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$insertAccount = $conn->prepare("INSERT INTO accounts (name1, email, password1) VALUES (?, ?, ?)");
$insertAccount->bind_param("sss", $name, $email, $password);
if ($insertAccount->execute()) {
    echo "success";
} else {
    echo "error";
}
$insertAccount->close();
$conn->close();
?>
