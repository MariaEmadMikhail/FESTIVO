<?php
require_once 'db_connection.php';

$res = $conn->query("SELECT COUNT(*) as count FROM categories");
$cat_count = $res->fetch_assoc()['count'];

$res = $conn->query("SELECT COUNT(*) as count FROM products");
$prod_count = $res->fetch_assoc()['count'];

echo "Categories: $cat_count\n";
echo "Products: $prod_count\n";

if ($cat_count == 0) {
    echo "Seeding categories...\n";
    $categories = ["Balloons", "Chairs and Tables", "Bean Bags", "Flowers", "Draping Fabrics", "Themed Props", "Candles and Lanterns", "Arches", "Table Centerpieces"];
    foreach ($categories as $cat) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $cat);
        $stmt->execute();
    }
}

if ($prod_count == 0) {
    echo "Seeding products...\n";
    $res = $conn->query("SELECT category_id, name FROM categories");
    while ($row = $res->fetch_assoc()) {
        for ($i = 1; $i <= 3; $i++) {
            $name = $row['name'] . " Option $i";
            $price = 20 + rand(0, 100);
            $stmt = $conn->prepare("INSERT INTO products (category_id, name, base_price, available_quantity, description) VALUES (?, ?, ?, ?, ?)");
            $qty = 50;
            $desc = "High quality " . $row['name'];
            $stmt->bind_param("isdis", $row['category_id'], $name, $price, $qty, $desc);
            $stmt->execute();
        }
    }
}
?>
