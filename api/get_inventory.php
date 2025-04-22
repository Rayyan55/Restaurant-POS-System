<?php
require '../db/connection.php';

$sql = "SELECT * FROM inventory ORDER BY name ASC";
$result = $conn->query($sql);

$inventory = [];
while ($row = $result->fetch_assoc()) {
    $inventory[] = $row;
}

echo json_encode($inventory);
?>
