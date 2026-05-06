<?php
session_start();
require_once '../backend/db_connection.php';

/* session check disabled for development */
if (!isset($_SESSION["username"])) {
    // For development, we'll use a guest if not logged in
    $username = "Guest";
} else {
    $username = $_SESSION["username"];
}

// Fetch customer orders
$orders = [];
if ($username !== "Guest") {
    $stmt = $conn->prepare("
        SELECT o.order_id, et.name as occasion, o.event_date, o.start_time, o.duration, o.status, o.total_price 
        FROM orders o 
        JOIN event_type et ON o.event_type_id = et.event_type_id 
        JOIN customer c ON o.customer_id = c.customer_id 
        WHERE c.username = ? 
        ORDER BY o.event_date DESC
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivo | My Orders</title>
    <link rel="stylesheet" href="/FESTIVO/home%20page/styles.css">
    <link rel="stylesheet" href="/FESTIVO/customer_home/customer.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        .orders-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .order-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
        }
        .order-main-info h3 {
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        .order-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-pending { background: #ffd70033; color: #ffd700; }
        .status-confirmed { background: #00ff0033; color: #00ff00; }
        .status-completed { background: #0088ff33; color: #0088ff; }
        .order-price {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-light);
        }
        .empty-orders {
            text-align: center;
            padding: 5rem 0;
        }
    </style>
</head>

<body>
    <div class="background-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">
                <img src="/FESTIVO/logo.png" alt="Festivo" style="height: 40px; vertical-align: middle;">
            </a>

            <div class="nav-links" id="navLinks">
                <a href="index.php">HOME</a>
                <a href="occasions.php">Occasions</a>
                <a href="products.php">Products</a>
                <a href="catering.php">Catering</a>
                <a href="my-orders.php" class="active">My Orders</a>
                <a href="checkout.php" class="cart-icon" title="My Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-badge" id="cartBadge">0</span>
                </a>
                <a href="../backend/logout.php" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="margin-right: 5px; vertical-align: text-bottom;">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </a>
            </div>

            <button class="menu-toggle" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main style="margin-top: 100px;">
        <div class="orders-container">
            <h1 class="section-title" style="text-align: left;">My Orders</h1>
            <p style="color: var(--text-muted); margin-bottom: 3rem;">Track and manage your celebration bookings.</p>

            <?php if (empty($orders)): ?>
                <div class="empty-orders">
                    <div style="font-size: 4rem; margin-bottom: 1.5rem;">📅</div>
                    <h2>No orders found</h2>
                    <p style="color: var(--text-muted); margin-bottom: 2rem;">You haven't booked any events yet.</p>
                    <a href="occasions.php" class="primary-btn" style="display: inline-block;">Start Planning</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-main-info">
                            <h3><?php echo htmlspecialchars($order['occasion']); ?></h3>
                            <div class="order-meta">
                                <span>Order #FST-<?php echo str_pad($order['order_id'], 5, '0', STR_PAD_LEFT); ?></span> • 
                                <span><?php echo date('M d, Y', strtotime($order['event_date'])); ?></span> • 
                                <span><?php echo $order['duration']; ?></span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div class="order-status status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </div>
                            <div class="order-price" style="margin-top: 0.5rem;">
                                $<?php echo number_format($order['total_price'], 2); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Simple mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function () {
            document.getElementById('navLinks').classList.toggle('active');
            this.classList.toggle('open');
        });

        // Update cart badge from localStorage
        function updateBadge() {
            const cart = JSON.parse(localStorage.getItem('festivoCart')) || [];
            document.getElementById('cartBadge').innerText = cart.length;
        }
        updateBadge();
    </script>
</body>

</html>
