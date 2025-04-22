<?php
require '../db/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['name']);
$quantity = intval($data['quantity']);
$unit = $conn->real_escape_string($data['unit']);

$sql = "INSERT INTO inventory (name, quantity, unit) VALUES ('$name', $quantity, '$unit')";
$conn->query($sql);

echo json_encode(["success" => true]);
?>
