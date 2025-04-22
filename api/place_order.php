<?php
require '../db/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$items = $conn->real_escape_string(json_encode($data['items']));
$conn->query("INSERT INTO orders (items) VALUES ('$items')");
$orderId = $conn->insert_id;

// Deduct stock for each item
foreach ($data['items'] as $menuName) {
    $menuResult = $conn->query("SELECT id FROM menu WHERE name = '$menuName'");
    if ($menuResult->num_rows > 0) {
        $menu = $menuResult->fetch_assoc();
        $menuId = $menu['id'];

        $recipeResult = $conn->query("SELECT ingredient_id, amount FROM recipes WHERE menu_id = $menuId");
        while ($row = $recipeResult->fetch_assoc()) {
            $ingredientId = $row['ingredient_id'];
            $amount = $row['amount'];
            $conn->query("UPDATE inventory SET quantity = quantity - $amount WHERE id = $ingredientId");
        }
    }
}

echo json_encode(["order_id" => $orderId]);
?>
