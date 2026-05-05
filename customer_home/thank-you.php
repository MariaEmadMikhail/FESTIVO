<?php
session_start();

/* session check disabled for development */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivo | Thank You!</title>
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
        </div>
    </nav>

    <main style="margin-top: 100px;">
        <div class="thank-you-card">
            <div style="font-size: 5rem; margin-bottom: 2rem;">✨</div>
            <h1 class="section-title">Thank You For Your Order!</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;">
                Your celebration package has been confirmed. We've received your details and our team will contact you shortly to finalize everything.
            </p>

            <div class="order-number" id="finalOrderId">#FST-00000</div>

            <div class="payment-form-section" style="margin-top: 3rem; text-align: left;">
                <h3 style="color: var(--secondary); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Final Summary Recap
                </h3>
                
                <div id="finalSummaryContent">
                    <!-- Loaded from localStorage -->
                </div>
            </div>

            <div style="margin-top: 3rem;">
                <button class="primary-btn" onclick="goHome()" style="padding: 1.2rem 3rem;">
                    Back to Dashboard
                </button>
            </div>
        </div>
    </main>

    <script>
        function init() {
            const orderId = localStorage.getItem('lastOrderId') || '#FST-XXXXX';
            const rawSummary = localStorage.getItem('lastOrderSummary');
            const eventData = JSON.parse(localStorage.getItem('festivoEvent')) || {};
            const cart = JSON.parse(localStorage.getItem('festivoCart')) || [];
            
            document.getElementById('finalOrderId').innerText = orderId;

            if (rawSummary) {
                const summary = JSON.parse(rawSummary);
                const html = `
                    <p style="margin-bottom: 2rem; color: var(--text-muted); border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                        <strong>Delivery Address:</strong><br>
                        ${summary.address}
                    </p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Event Date:</span><br>
                            <strong>${eventData.date || 'TBD'}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Start Time:</span><br>
                            <strong>${eventData.time || 'TBD'}</strong>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 1rem;">
                         <span style="color: var(--text-muted);">${summary.itemsCount} items in your package</span>
                         <span style="font-size: 1.5rem; font-weight: 800; color: var(--secondary);">${summary.total}</span>
                    </div>
                `;
                document.getElementById('finalSummaryContent').innerHTML = html;
            }
        }

        window.goHome = function() {
            // Final cleanup
            localStorage.removeItem('festivoCart');
            localStorage.removeItem('festivoEvent');
            localStorage.removeItem('lastOrderId');
            localStorage.removeItem('lastOrderSummary');
            window.location.href = 'index.php';
        };

        init();
    </script>
</body>

</html>
