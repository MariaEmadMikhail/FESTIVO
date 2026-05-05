<?php
session_start();

/* session check disabled for development */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivo | Finalize Order</title>
    <link rel="stylesheet" href="/FESTIVO/home%20page/styles.css">
    <link rel="stylesheet" href="/FESTIVO/customer_home/customer.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Background Shapes -->
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
                <a href="checkout.php" class="cart-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>
            </div>
            <button class="menu-toggle" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main style="margin-top: 100px; padding-bottom: 50px;">
        <div class="checkout-container">
            <a href="checkout.php" class="back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Cart
            </a>
            
            <h1 class="section-title" style="text-align: left; margin-top: 1rem;">Finalize Your Order</h1>
            
            <div class="payment-grid">
                <!-- Left: Forms -->
                <div class="payment-form-section">
                    <h2 style="margin-bottom: 2rem; color: var(--secondary);">Event Location</h2>
                    <div class="form-group">
                        <label>Area</label>
                        <select class="form-input" id="area" required>
                            <option value="">Select an area</option>
                            <option value="Heliopolis">Heliopolis</option>
                            <option value="New Cairo">New Cairo</option>
                            <option value="Sheraton">Sheraton</option>
                            <option value="Nasr City">Nasr City</option>
                            <option value="Shrouk">Shrouk</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Detailed Address</label>
                        <input type="text" class="form-input" id="address" placeholder="Enter the full event address" required>
                    </div>

                    <h2 style="margin: 3rem 0 2rem; color: var(--secondary);">Payment Details</h2>
                    <div class="form-group">
                        <label>Cardholder Name</label>
                        <input type="text" class="form-input" id="cardName" placeholder="Full name on card">
                    </div>
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" class="form-input" id="cardNumber" placeholder="0000 0000 0000 0000">
                    </div>
                    <div class="card-details-grid">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="text" class="form-input" id="expiry" placeholder="MM/YY">
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="password" class="form-input" id="cvv" placeholder="123">
                        </div>
                    </div>

                    <button class="primary-btn" id="confirmBtn" style="width: 100%; margin-top: 2rem; padding: 1.5rem;">
                        Confirm Your Order
                    </button>
                </div>

                <!-- Right: Sticky Summary -->
                <div class="summary-box">
                    <h2 class="summary-title">Summary Recap</h2>
                    
                    <div id="eventRecap">
                        <!-- Occasion & Time info -->
                    </div>

                    <div style="margin: 2rem 0; font-size: 0.9rem;">
                        <div style="font-weight: 700; color: var(--secondary); margin-bottom: 1rem;">Selected Items:</div>
                        <div id="itemsRecap" style="max-height: 200px; overflow-y: auto; color: var(--text-muted);">
                            <!-- List of items -->
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 1.5rem;">
                        <div class="summary-item" style="font-size: 1.2rem;">
                            <span class="label">Grand Total:</span>
                            <span class="value" id="finalTotal" style="color: var(--secondary);">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const cart = JSON.parse(localStorage.getItem('festivoCart')) || [];
        const eventData = JSON.parse(localStorage.getItem('festivoEvent')) || {};

        function init() {
            if (cart.length === 0) window.location.href = 'products.php';
            
            renderRecap();
            
            document.getElementById('mobileMenuBtn').addEventListener('click', () => {
                document.getElementById('navLinks').classList.toggle('active');
            });

            document.getElementById('confirmBtn').addEventListener('click', handleConfirmation);
        }

        function renderRecap() {
            // Event Details
            const eventHtml = `
                <div class="summary-item">
                    <span class="label">Occasion:</span>
                    <span class="value">${eventData.occasion || 'General'}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Date:</span>
                    <span class="value">${eventData.date || 'TBD'}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Start Time:</span>
                    <span class="value">${eventData.startTime || 'TBD'}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Duration:</span>
                    <span class="value">${eventData.hours ? eventData.hours + ' Hours' : (eventData.slot || 'N/A')}</span>
                </div>
            `;
            document.getElementById('eventRecap').innerHTML = eventHtml;

            const hours = eventData.hours || 1;

            // Items List
            document.getElementById('itemsRecap').innerHTML = cart.map(i => {
                const itemHours = i.type ? 1 : hours;
                return `
                <div class="summary-item" style="margin-bottom: 0.5rem;">
                    <span class="label">${i.name} x${i.quantity || 1} ${itemHours > 1 ? `(${itemHours} hr)` : ''}</span>
                    <span class="value">$${(i.price * (i.quantity || 1) * itemHours).toFixed(2)}</span>
                </div>
                `;
            }).join('');

            // Total
            const total = cart.reduce((s, i) => {
                const itemHours = i.type ? 1 : hours;
                return s + (i.price * (i.quantity || 1) * itemHours);
            }, 0);
            document.getElementById('finalTotal').innerText = `$${total.toFixed(2)}`;
        }

        async function handleConfirmation() {
            const area = document.getElementById('area').value;
            const address = document.getElementById('address').value;
            if (!area || !address) return alert('Please provide the full event address and area.');

            const confirmBtn = document.getElementById('confirmBtn');
            const originalText = confirmBtn.innerText;
            confirmBtn.disabled = true;
            confirmBtn.innerText = 'Processing...';

            const hours = eventData.hours || 1;
            const total = cart.reduce((s, i) => {
                const itemHours = i.type ? 1 : hours;
                return s + (i.price * (i.quantity || 1) * itemHours);
            }, 0);

            const orderData = {
                event: eventData,
                cart: cart,
                total: total,
                location: `${area}, ${address}`
            };

            try {
                const response = await fetch('../backend/save_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();

                if (result.success) {
                    localStorage.setItem('lastOrderId', result.order_id);
                    localStorage.setItem('lastOrderSummary', JSON.stringify({
                        address: address,
                        total: document.getElementById('finalTotal').innerText,
                        itemsCount: cart.length
                    }));
                    window.location.href = 'thank-you.php';
                } else {
                    alert('Error saving order: ' + (result.error || 'Unknown error'));
                    confirmBtn.disabled = false;
                    confirmBtn.innerText = originalText;
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('Failed to connect to the server. Please check your connection.');
                confirmBtn.disabled = false;
                confirmBtn.innerText = originalText;
            }
        }

        init();
    </script>
</body>

</html>
