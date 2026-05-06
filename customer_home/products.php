<?php
session_start();

/* session check disabled for development */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivo | Select Decor & Items</title>
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
                <a href="index.php">Home</a>
                <a href="occasions.php">Occasions</a>
                <a href="products.php" class="active">Products</a>
                <a href="catering.php">Catering</a>
                <a href="my-orders.php">My Orders</a>

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
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <main style="margin-top: 80px; padding-bottom: 120px;">
        <div class="section-container" style="padding-bottom: 0;">
            <a href="occasions.php" class="back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Occasions
            </a>
            <h1 class="section-title" style="margin-bottom: 1rem;">Select Your Decor</h1>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 3rem;">Browse through categories and
                add items to your event package. <br /><strong style="color: var(--secondary);">Note: Prices may differ
                    according to your booking duration.</strong></p>
        </div>

        <!-- Category Bar -->
        <div class="category-container">
            <div class="category-bar" id="categoryBar">
                <!-- Dynamically populated -->
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
            <!-- Dynamically populated -->
        </div>
    </main>

    <!-- Bottom Action Bar -->
    <div class="bottom-bar">
        <div class="cart-summary">
            <div class="cart-stats">
                <span class="cart-items-count" id="itemCount">0 items selected</span>
                <span class="cart-total" id="totalPrice">$0.00</span>
            </div>
        </div>
        <button class="next-btn" id="checkoutBtn" disabled>
            Next: Review Order
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </div>

    <script>
        // Categories and Product Data will be fetched from the backend
        let categories = [];
        let productData = {};

        let currentCategory = "";
        let cart = JSON.parse(localStorage.getItem('festivoCart')) || [];

        // DOM Elements
        const categoryBar = document.getElementById('categoryBar');
        const productsGrid = document.getElementById('productsGrid');
        const itemCount = document.getElementById('itemCount');
        const totalPrice = document.getElementById('totalPrice');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const cartBadge = document.getElementById('cartBadge');

        // Initialize Page
        async function init() {
            const eventData = JSON.parse(localStorage.getItem('festivoEvent')) || { hours: 1 };
            const hours = eventData.hours || 1;

            // Pricing Multiplier Table
            let multiplier = 1.00;
            if (hours >= 10) multiplier = 0.60;
            else if (hours >= 7) multiplier = 0.68;
            else if (hours >= 4) multiplier = 0.80;

            window.pricingMultiplier = multiplier;
            window.bookingHours = hours;

            await fetchCategories();
            if (categories.length > 0) {
                currentCategory = categories[0];
                await fetchProducts();
            }
            updateCartUI();

            // Mobile menu toggle
            document.getElementById('mobileMenuBtn').addEventListener('click', function () {
                document.getElementById('navLinks').classList.toggle('active');
                this.classList.toggle('open');
            });
        }

        async function fetchCategories() {
            const response = await fetch('../backend/get_categories.php');
            const data = await response.json();
            categories = [];
            data.forEach(cat => {
                if (cat.name === "Candles and Lanterns") {
                    categories.push("Candles");
                    categories.push("Lanterns");
                } else {
                    categories.push(cat.name);
                }
            });
            renderCategories();
        }

        async function fetchProducts() {
            productsGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem;">Loading products...</div>';

            let queryCategory = currentCategory;
            let filterTerm = null;

            if (currentCategory === "Candles") {
                queryCategory = "Candles and Lanterns";
                filterTerm = "candle";
            } else if (currentCategory === "Lanterns") {
                queryCategory = "Candles and Lanterns";
                filterTerm = "lantern";
            }

            const response = await fetch(`../backend/get_products.php?category=${encodeURIComponent(queryCategory)}`);
            const data = await response.json();

            let filteredData = Array.isArray(data) ? data : [];
            if (filterTerm) {
                filteredData = filteredData.filter(p => p.name.toLowerCase().includes(filterTerm));
            }

            productData[currentCategory] = filteredData.map(p => ({
                id: p.product_id,
                name: p.name,
                price: parseFloat(p.base_price),
                icon: p.icon || "📦",
                colors: p.colors || []
            }));

            renderProducts();
        }

        function renderCategories() {
            categoryBar.innerHTML = categories.map(cat => `
                <button class="category-btn ${cat === currentCategory ? 'active' : ''}" onclick="setCategory('${cat}')">
                    ${cat}
                </button>
            `).join('');
        }

        window.setCategory = async function (cat) {
            currentCategory = cat;
            renderCategories();
            await fetchProducts();
        };

        function renderProducts() {
            const products = productData[currentCategory] || [];
            if (products.length === 0) {
                productsGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--text-muted);">No products found in this category.</div>';
                return;
            }

            productsGrid.innerHTML = products.map(product => {
                // Find if this item is in cart to get the quantity/color
                const cartItem = cart.find(item => item.id === product.id);
                const currentColor = cartItem ? cartItem.color : (product.colors && product.colors.length > 0 ? product.colors[0] : null);
                const quantity = cartItem ? cartItem.quantity : 1;
                const isAdded = !!cartItem;

                const name = product.name.toLowerCase();
                const category = currentCategory.toLowerCase();
                const isFlatRate = name.includes('balloon') || category.includes('balloon') ||
                    name.includes('candle') || category.includes('candle') ||
                    name.includes('flower') || category.includes('flower') ||
                    name.includes('floral') || category.includes('floral');

                const effectivePrice = isFlatRate ? product.price : (product.price * window.pricingMultiplier);
                const totalItemPrice = isFlatRate ? (product.price * 1) : (effectivePrice * window.bookingHours);

                return `
                    <div class="product-card">
                        <div class="product-img">${product.icon}</div>
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <div class="product-price">
                                $${totalItemPrice.toFixed(2)} 
                                <span style="font-size: 0.7rem; color: var(--text-muted); display: block;">
                                    ${isFlatRate ? '(Flat rate)' : `($${product.price}/hr x ${window.bookingHours}hrs ${window.pricingMultiplier < 1 ? ` x ${window.pricingMultiplier} discount` : ''})`}
                                </span>
                            </div>
                        </div>
                        
                        <div class="product-controls">
                            <div class="options-row">
                                <div class="quantity-control">
                                    <button class="qty-btn" onclick="updateQty('${product.id}', -1)">-</button>
                                    <span class="qty-val" id="qty-${product.id}">${quantity}</span>
                                    <button class="qty-btn" onclick="updateQty('${product.id}', 1)">+</button>
                                </div>
                                
                                ${product.colors && product.colors.length > 0 ? `
                                    <div class="color-swatches" id="colors-${product.id}">
                                        ${product.colors.map((color, idx) => `
                                            <div class="color-dot ${idx === 0 ? 'active' : ''}" 
                                                 style="background: ${color}" 
                                                 onclick="setColor(this, '${product.id}', '${color}')">
                                            </div>
                                        `).join('')}
                                    </div>
                                ` : ''}
                            </div>
                            
                            <button class="add-btn ${isAdded ? 'added' : ''}" id="btn-${product.id}" onclick="toggleCart('${product.id}')">
                                ${isAdded ? 'Added to Package' : 'Add to Package'}
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        window.updateQty = function (id, change) {
            const qtySpan = document.getElementById(`qty-${id}`);
            if (!qtySpan) return;
            let val = parseInt(qtySpan.innerText) + change;
            if (val < 1) val = 1;
            qtySpan.innerText = val;

            const colorEl = document.querySelector(`#colors-${id} .color-dot.active`);
            const currentColor = colorEl ? colorEl.style.backgroundColor : null;

            // If item+color is already in cart, update it
            const itemIndex = cart.findIndex(item => item.id == id && item.color === currentColor);
            if (itemIndex > -1) {
                cart[itemIndex].quantity = val;
                saveCart();
                updateCartUI();
            }
        };

        window.setColor = function (el, id, color) {
            const parent = document.getElementById(`colors-${id}`);
            parent.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
            el.classList.add('active');

            // Check if this new color is in cart
            const cartItem = cart.find(item => item.id == id && item.color === color);
            const btn = document.getElementById(`btn-${id}`);
            const qtySpan = document.getElementById(`qty-${id}`);

            if (cartItem) {
                btn.classList.add('added');
                btn.innerText = 'Added to Package';
                qtySpan.innerText = cartItem.quantity;
            } else {
                btn.classList.remove('added');
                btn.innerText = 'Add to Package';
                qtySpan.innerText = 1;
            }
        };

        window.toggleCart = function (id) {
            const colorEl = document.querySelector(`#colors-${id} .color-dot.active`);
            const currentColor = colorEl ? colorEl.style.backgroundColor : null;

            const itemIndex = cart.findIndex(item => item.id == id && item.color === currentColor);
            const btn = document.getElementById(`btn-${id}`);

            if (itemIndex > -1) {
                // Remove
                cart.splice(itemIndex, 1);
                btn.classList.remove('added');
                btn.innerText = 'Add to Package';
            } else {
                // Add
                const prod = productData[currentCategory].find(p => p.id == id);
                const qty = parseInt(document.getElementById(`qty-${id}`).innerText);

                cart.push({
                    id: prod.id,
                    name: prod.name,
                    price: prod.price,
                    quantity: qty,
                    color: currentColor,
                    icon: prod.icon,
                    category: currentCategory
                });
                btn.classList.add('added');
                btn.innerText = 'Added to Package';
            }
            saveCart();
            updateCartUI();
        };

        function saveCart() {
            localStorage.setItem('festivoCart', JSON.stringify(cart));
        }

        function updateCartUI() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalPriceVal = cart.reduce((sum, item) => {
                const name = item.name.toLowerCase();
                const category = (item.category || '').toLowerCase();
                const isFlatRate = item.type ||
                    name.includes('balloon') || category.includes('balloon') ||
                    name.includes('candle') || category.includes('candle') ||
                    name.includes('flower') || category.includes('flower') ||
                    name.includes('floral') || category.includes('floral');

                const itemHours = isFlatRate ? 1 : window.bookingHours;
                const multiplier = isFlatRate ? 1 : window.pricingMultiplier;
                return sum + (item.price * item.quantity * itemHours * multiplier);
            }, 0);

            itemCount.innerText = `${cart.length} unique items selected`;
            totalPrice.innerText = `$${totalPriceVal.toFixed(2)}`;
            cartBadge.innerText = cart.length;
            checkoutBtn.disabled = cart.length === 0;
        }

        checkoutBtn.addEventListener('click', () => {
            window.location.href = 'catering.php';
        });

        init();
    </script>
</body>

</html>