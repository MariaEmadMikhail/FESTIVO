<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connection.php';

if (!isset($_SESSION["username"])) {
    // Session check disabled for development - use first available customer
    $res = $conn->query("SELECT username FROM customer LIMIT 1");
    $row = $res ? $res->fetch_assoc() : null;
    if ($row) {
        $_SESSION["username"] = $row["username"];
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized - no customer account found']);
        exit();
    }
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'No data provided']);
    exit();
}

$conn->begin_transaction();

try {
    // 1. Get customer_id from username
    $stmt = $conn->prepare("SELECT customer_id FROM customer WHERE username = ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    $customer_id = $stmt->get_result()->fetch_assoc()['customer_id'];

    // 2. Get event_type_id
    $stmt = $conn->prepare("SELECT event_type_id FROM event_type WHERE name = ?");
    $stmt->bind_param("s", $data['event']['occasion']);
    $stmt->execute();
    $event_type_result = $stmt->get_result();
    
    if ($event_type_result->num_rows > 0) {
        $event_type_id = $event_type_result->fetch_assoc()['event_type_id'];
    } else {
        // Insert new event type if it doesn't exist (optional, or just handle error)
        $stmt = $conn->prepare("INSERT INTO event_type (name) VALUES (?)");
        $stmt->bind_param("s", $data['event']['occasion']);
        $stmt->execute();
        $event_type_id = $conn->insert_id;
    }

    // 3. Insert Order
    $start_time = $data['event']['date'] . ' ' . $data['event']['startTime'];
    $duration = $data['event']['duration'];
    $hours = isset($data['event']['hours']) ? (float)$data['event']['hours'] : 1.0;
    
    // Pricing Multiplier Logic
    $multiplier = 1.00;
    if ($hours >= 10) $multiplier = 0.60;
    else if ($hours >= 7) $multiplier = 0.68;
    else if ($hours >= 4) $multiplier = 0.80;

    $total_price = 0;
    foreach ($data['cart'] as $item) {
        $name = strtolower($item['name']);
        $category = isset($item['category']) ? strtolower($item['category']) : '';
        
        $isFlatRate = isset($item['type']) || 
                      strpos($name, 'balloon') !== false || strpos($category, 'balloon') !== false ||
                      strpos($name, 'candle') !== false || strpos($category, 'candle') !== false ||
                      strpos($name, 'flower') !== false || strpos($category, 'flower') !== false ||
                      strpos($name, 'floral') !== false || strpos($category, 'floral') !== false;
        
        $itemHours = $isFlatRate ? 1 : $hours;
        $itemMultiplier = $isFlatRate ? 1 : $multiplier;
        
        $total_price += ($item['price'] * $item['quantity'] * $itemHours * $itemMultiplier);
    }

    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, event_type_id, event_date, duration, start_time, status, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssd", $customer_id, $event_type_id, $data['event']['date'], $duration, $start_time, $status, $total_price);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // 4. Insert Order Items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
    
    foreach ($data['cart'] as $item) {
        $prod_id = is_numeric($item['id']) ? $item['id'] : null;
        if ($prod_id) {
            $stmt->bind_param("iiid", $order_id, $prod_id, $item['quantity'], $item['price']);
            $stmt->execute();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
