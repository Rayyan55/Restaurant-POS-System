<?php
require '../db/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['name']);
$price = floatval($data['price']);

$sql = "INSERT INTO menu (name, price) VALUES ('$name', $price)";
$conn->query($sql);

echo json_encode(["success" => true]);
?>
