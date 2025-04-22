<?php
require '../db/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$menu_id = intval($data['menu_id']);
$ingredient_id = intval($data['ingredient_id']);
$amount = intval($data['amount']);

$sql = "INSERT INTO recipes (menu_id, ingredient_id, amount) VALUES ($menu_id, $ingredient_id, $amount)";
$conn->query($sql);

echo json_encode(["success" => true]);
?>
