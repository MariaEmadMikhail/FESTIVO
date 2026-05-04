<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../Login Page/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivo | Cake & Catering</title>
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
                <a href="products.html">Products</a>
                <a href="catering.php" class="active">Catering</a>

                <a href="checkout.html" class="cart-icon" title="My Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-badge" id="cartBadge">0</span>
                </a>

                <a href="../backend/logout.php" class="logout-btn">
                    Logout
                </a>
            </div>

            <button class="menu-toggle" id="mobileMenuBtn">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main style="margin-top: 80px; padding-bottom: 120px;">
        <div class="section-container">
            <a href="products.html" class="back-btn" style="margin-left: 2rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Products
            </a>
            <h1 class="section-title">Cake & Catering</h1>
            <p id="occasionVibe" style="text-align: center; color: var(--secondary); font-weight: 600; margin-top: -2rem; margin-bottom: 3rem; text-transform: uppercase; letter-spacing: 2px;">
                <!-- Occasion Vibe text -->
            </p>
        </div>

        <!-- Selection Grid -->
        <div class="products-grid" id="cateringGrid">
            <!-- Dynamically populated -->
        </div>
    </main>

    <!-- Bottom Action Bar -->
    <div class="bottom-bar">
        <div class="cart-summary">
            <div class="cart-stats">
                <span class="cart-items-count" id="itemCount">0 catering items added</span>
                <span class="cart-total" id="totalPrice">$0.00</span>
            </div>
        </div>
        <button class="next-btn" id="finishBtn">
            Next: Review Order
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </div>

    <script>
        const eventData = JSON.parse(localStorage.getItem('festivoEvent')) || { occasion: 'General' };
        const occasion = eventData.occasion;
        
        let cart = JSON.parse(localStorage.getItem('festivoCart')) || [];
        
        // Themed Content Logic
        function getThemeName(baseName) {
            if (occasion.toLowerCase().includes('reveal') || occasion.toLowerCase().includes('baby')) {
                return `Pink & Blue ${baseName}`;
            } else if (occasion.toLowerCase().includes('seasonal') || occasion.toLowerCase().includes('ramadan')) {
                return `Festive ${baseName}`;
            } else if (occasion.toLowerCase().includes('birthday')) {
                return `Celebration ${baseName}`;
            }
            return `Premium ${baseName}`;
        }

        const cateringItems = [
            { id: 'cake-6a', type: 'Cake', name: getThemeName('Signature Cake'), size: '6 persons', price: 45, icon: '🎂' },
            { id: 'cake-6b', type: 'Cake', name: getThemeName('Deluxe Cake'), size: '6 persons', price: 55, icon: '🎂' },
            { id: 'cake-12a', type: 'Cake', name: getThemeName('Grand Cake'), size: '12 persons', price: 85, icon: '🎂' },
            { id: 'cake-12b', type: 'Cake', name: getThemeName('Royal Cake'), size: '12 persons', price: 105, icon: '🎂' },
            { id: 'cake-18a', type: 'Cake', name: getThemeName('Majestic Cake'), size: '18 persons', price: 140, icon: '🎂' },
            { id: 'cake-18b', type: 'Cake', name: getThemeName('Feast Cake'), size: '18 persons', price: 170, icon: '🎂' },
            { id: 'cupcake-1', type: 'Cupcakes', name: getThemeName('Dozen Cupcakes (Design A)'), size: '12 pieces', price: 35, icon: '🧁' },
            { id: 'cupcake-2', type: 'Cupcakes', name: getThemeName('Dozen Cupcakes (Design B)'), size: '12 pieces', price: 40, icon: '🧁' },
            { id: 'cakepop-1', type: 'Cake Pops', name: getThemeName('Dozen Cake Pops (Design A)'), size: '12 pieces', price: 30, icon: '🍭' },
            { id: 'cakepop-2', type: 'Cake Pops', name: getThemeName('Dozen Cake Pops (Design B)'), size: '12 pieces', price: 35, icon: '🍭' }
        ];

        const flavors = ["Chocolate", "Vanilla", "Half & Half"];

        function init() {
            document.getElementById('occasionVibe').innerText = `Styling for: ${occasion}`;
            renderCatering();
            updateCartUI();

            document.getElementById('mobileMenuBtn').addEventListener('click', () => {
                document.getElementById('navLinks').classList.toggle('active');
            });
        }

        function renderCatering() {
            const grid = document.getElementById('cateringGrid');
            grid.innerHTML = cateringItems.map(item => {
                const activeFlavorChip = document.querySelector(`#flavors-${item.id} .flavor-chip.active`);
                const currentFlavor = activeFlavorChip ? activeFlavorChip.innerText.trim() : "Chocolate";
                
                const cartItem = cart.find(c => c.id === item.id && c.flavor === currentFlavor);
                const quantity = cartItem ? cartItem.quantity : 1;
                const isAdded = !!cartItem;

                return `
                    <div class="product-card">
                        <div class="product-img">${item.icon}</div>
                        <div class="product-info">
                            <div style="font-size: 0.8rem; color: var(--secondary); font-weight: 600;">${item.size}</div>
                            <h3>${item.name}</h3>
                            <div class="product-price">$${item.price}</div>
                        </div>

                        <div class="product-controls">
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.3rem;">Pick a Flavor:</div>
                            <div class="flavor-selection" id="flavors-${item.id}" style="margin-bottom: 1.5rem;">
                                ${flavors.map(f => `
                                    <div class="flavor-chip ${f === currentFlavor ? 'active' : ''}" 
                                         onclick="setFlavor(this, '${item.id}', '${f}')">
                                        ${f}
                                    </div>
                                `).join('')}
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                                <div class="quantity-control">
                                    <button class="qty-btn" onclick="updateCateringQty('${item.id}', -1)">-</button>
                                    <span class="qty-val" id="qty-${item.id}">${quantity}</span>
                                    <button class="qty-btn" onclick="updateCateringQty('${item.id}', 1)">+</button>
                                </div>
                                <button id="btn-${item.id}" class="add-btn ${isAdded ? 'added' : ''}" onclick="toggleCatering('${item.id}')" style="flex: 1;">
                                    ${isAdded ? 'Added' : 'Add to Package'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        window.updateCateringQty = function(id, change) {
            const qtySpan = document.getElementById(`qty-${id}`);
            let val = parseInt(qtySpan.innerText) + change;
            if (val < 1) val = 1;
            qtySpan.innerText = val;

            const flavorChip = document.querySelector(`#flavors-${id} .flavor-chip.active`);
            const flavor = flavorChip ? flavorChip.innerText.trim() : "Chocolate";

            // If already in cart, update quantity
            const itemIndex = cart.findIndex(c => c.id === id && c.flavor === flavor);
            if (itemIndex > -1) {
                cart[itemIndex].quantity = val;
                saveCart();
                updateCartUI();
            }
        };

        window.setFlavor = function(el, id, flavor) {
            const parent = document.getElementById(`flavors-${id}`);
            parent.querySelectorAll('.flavor-chip').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            
            // UI Update for add button and quantity based on new flavor selection
            const cartItem = cart.find(c => c.id === id && c.flavor === flavor);
            const btn = document.getElementById(`btn-${id}`);
            const qtySpan = document.getElementById(`qty-${id}`);

            if (cartItem) {
                btn.classList.add('added');
                btn.innerText = 'Added';
                qtySpan.innerText = cartItem.quantity;
            } else {
                btn.classList.remove('added');
                btn.innerText = 'Add to Package';
                qtySpan.innerText = 1;
            }
        };

        window.toggleCatering = function(id) {
            const flavorChip = document.querySelector(`#flavors-${id} .flavor-chip.active`);
            const flavor = flavorChip ? flavorChip.innerText.trim() : "Chocolate";
            
            const index = cart.findIndex(c => c.id === id && c.flavor === flavor);
            const btn = document.getElementById(`btn-${id}`);
            const qty = parseInt(document.getElementById(`qty-${id}`).innerText);

            if (index > -1) {
                cart.splice(index, 1);
                btn.classList.remove('added');
                btn.innerText = 'Add to Package';
            } else {
                const item = cateringItems.find(i => i.id === id);
                cart.push({ ...item, quantity: qty, color: null, flavor: flavor });
                btn.classList.add('added');
                btn.innerText = 'Added';
            }
            saveCart();
            updateCartUI();
        };

        function saveCart() {
            localStorage.setItem('festivoCart', JSON.stringify(cart));
        }

        function updateCartUI() {
            const count = cart.length;
            const total = cart.reduce((s, i) => s + (i.price * (i.quantity || 1)), 0);
            document.getElementById('itemCount').innerText = `${count} items in your package`;
            document.getElementById('totalPrice').innerText = `$${total.toFixed(2)}`;
            document.getElementById('cartBadge').innerText = count;
        }

        document.getElementById('finishBtn').addEventListener('click', () => {
             window.location.href = 'checkout.html';
        });

        init();
    </script>
</body>

</html>
