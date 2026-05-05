<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

try {
    $query = "SELECT * FROM categories";
    $result = $conn->query($query);
    $categories = [];
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    echo json_encode($categories);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
