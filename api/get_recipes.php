<?php
require '../db/connection.php';

$menu_id = intval($_GET['menu_id']);
$sql = "
    SELECT r.id, i.name AS ingredient, r.amount, i.unit
    FROM recipes r
    JOIN inventory i ON r.ingredient_id = i.id
    WHERE r.menu_id = $menu_id
";
$result = $conn->query($sql);

$recipes = [];
while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;
}

echo json_encode($recipes);
?>
