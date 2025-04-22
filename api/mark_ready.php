<?php
require '../db/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = intval($data['order_id']);

$sql = "UPDATE orders SET status = 'ready' WHERE id = $order_id";
$conn->query($sql);

echo json_encode(["success" => true]);
?>
