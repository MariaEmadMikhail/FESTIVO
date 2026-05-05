<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

try {
    // Get category filter if provided
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              JOIN categories c ON p.category_id = c.category_id";
    
    if ($category) {
        $query .= " WHERE c.name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $category);
    } else {
        $stmt = $conn->prepare($query);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        // Fetch colors for each product
        $productId = $row['product_id'];
        $colorQuery = "SELECT color_name FROM product_colors WHERE product_id = ?";
        $colorStmt = $conn->prepare($colorQuery);
        $colorStmt->bind_param("i", $productId);
        $colorStmt->execute();
        $colorResult = $colorStmt->get_result();
        $colors = [];
        while ($colorRow = $colorResult->fetch_assoc()) {
            $colors[] = $colorRow['color_name'];
        }
        
        $row['colors'] = $colors;
        $products[] = $row;
    }
    
    echo json_encode($products);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
