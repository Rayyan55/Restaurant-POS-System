<?php
require '../db/connection.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$where = $status ? "WHERE status = '$status'" : '';

$sql = "SELECT * FROM orders $where ORDER BY id DESC";
$result = $conn->query($sql);

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
