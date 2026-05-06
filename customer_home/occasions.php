<?php
session_start();

/* session check disabled for development */
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivo | Choose Your Occasion</title>
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
                <a href="occasions.php" class="active">Occasions</a>
                <a href="products.php">Products</a>
                <a href="catering.php">Catering</a>
                <a href="my-orders.php">My Orders</a>

                <!-- Cart Icon -->
                <a href="checkout.php" class="cart-icon" title="My Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-badge" id="cartBadge">0</span>
                </a>

                <!-- Logout -->
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

    <main>
        <!-- Occasions Selection Section -->
        <section class="occasions-section" style="margin-top: 4rem;">
            <div class="section-container">
                <a href="index.php" class="back-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Home
                </a>
                <h1 class="section-title">Plan Your Next Big Moment</h1>
                <p class="occasions-subtitle">Select your occasion type and the perfect date & time to get started.</p>

                <!-- Occasion Cards Grid -->
                <div class="occasions-grid">
                    <div class="occasion-card" data-occasion="Birthdays">
                        <div class="occasion-icon">🎂</div>
                        <h3>Birthdays</h3>
                        <p>Celebrate another year with style</p>
                    </div>
                    <div class="occasion-card" data-occasion="Gender Reveals / Baby Showers">
                        <div class="occasion-icon">👶</div>
                        <h3>Gender Reveals / Baby Showers</h3>
                        <p>Welcome the newest member of your family</p>
                    </div>
                    <div class="occasion-card" data-occasion="Dinners">
                        <div class="occasion-icon">🍽️</div>
                        <h3>Dinners</h3>
                        <p>Elegant dining experiences to remember</p>
                    </div>
                    <div class="occasion-card" data-occasion="Seasonal Options">
                        <div class="occasion-icon">🌙</div>
                        <h3>Seasonal Options</h3>
                        <p>Ramadan, Christmas, Sohour, Iftar, Easter & more</p>
                    </div>
                    <div class="occasion-card" data-occasion="Customize Your Own">
                        <div class="occasion-icon">🎨</div>
                        <h3>Customize Your Own</h3>
                        <p>Design a unique event from scratch</p>
                    </div>
                </div>

                <!-- Date, Start Time & Duration Picker -->
                <div class="event-details">
                    <h3 class="event-details-title">Pick Your Date & Time Slot</h3>
                    <div class="event-details-row">
                        <div class="input-group">
                            <label for="eventDate">Event Date</label>
                            <input type="date" id="eventDate" class="event-input" />
                        </div>
                        <div style="display: flex; gap: 1rem; flex: 1;">
                            <div class="input-group" style="flex: 1;">
                                <label for="timeFrom">From Time</label>
                                <input type="time" id="timeFrom" class="event-input" />
                            </div>
                            <div class="input-group" style="flex: 1;">
                                <label for="timeTo">To Time</label>
                                <input type="time" id="timeTo" class="event-input" />
                            </div>
                        </div>
                    </div>
                    <div id="durationError" style="display: none; color: #ff5252; margin-top: 1rem; font-size: 0.9rem; text-align: center;"></div>
                </div>

                <!-- Next Button -->
                <div class="next-btn-container">
                    <button class="next-btn" id="nextBtn" disabled>
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h2>Festivo</h2>
                <p>Making every moment special.</p>
            </div>

            <div class="footer-contact">
                <h3>Contact Us</h3>
                <ul>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <a href="mailto:hello@festivo.com">hello@festivo.com</a>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        <a href="tel:+12345678900">+1 (234) 567-8900</a>
                    </li>
                </ul>
            </div>

            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#" class="social-icon">IG</a>
                    <a href="#" class="social-icon">FB</a>
                    <a href="#" class="social-icon">TW</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Festivo. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Simple mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function () {
            document.getElementById('navLinks').classList.toggle('active');
            this.classList.toggle('open');
        });

        // Handle Logout confirmation
        document.querySelector('.logout-btn').addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = this.getAttribute('href');
            }
        });

        // Update cart badge from localStorage
        function updateBadge() {
            const cartBadge = document.getElementById('cartBadge');
            if (cartBadge) {
                const cart = JSON.parse(localStorage.getItem('festivoCart')) || [];
                cartBadge.innerText = cart.length;
            }
        }
        updateBadge();

        // ===== Occasions Selection Logic =====
        const cards = document.querySelectorAll('.occasion-card');
        const eventDate = document.getElementById('eventDate');
        const timeFrom = document.getElementById('timeFrom');
        const timeTo = document.getElementById('timeTo');
        const durationError = document.getElementById('durationError');
        const nextBtn = document.getElementById('nextBtn');
        
        let selectedOccasion = null;
        let diffHours = 0;

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        eventDate.setAttribute('min', today);

        // Card selection (single-select)
        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                selectedOccasion = card.getAttribute('data-occasion');
                validateForm();
                
                if(window.innerWidth < 768) {
                    document.querySelector('.event-details').scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        eventDate.addEventListener('change', validateForm);
        timeFrom.addEventListener('change', validateForm);
        timeTo.addEventListener('change', validateForm);

        function validateForm() {
            let isValid = false;
            let errorMsg = '';
            
            if (eventDate.value && timeFrom.value && timeTo.value) {
                const fromDateTime = new Date(`${eventDate.value}T${timeFrom.value}`);
                let toDateTime = new Date(`${eventDate.value}T${timeTo.value}`);
                
                // If 'To' time is earlier than 'From' time, assume the party ends past midnight the next day.
                if (toDateTime <= fromDateTime) {
                    toDateTime.setDate(toDateTime.getDate() + 1);
                }
                
                diffHours = (toDateTime - fromDateTime) / (1000 * 60 * 60);
                
                if (diffHours >= 2 && diffHours <= 12) {
                    isValid = true;
                } else if (diffHours < 2) {
                    errorMsg = 'Minimum event duration is 2 hours.';
                } else if (diffHours > 12) {
                    errorMsg = 'Maximum event duration is 12 hours.';
                }
            }

            if (errorMsg) {
                durationError.innerText = errorMsg;
                durationError.style.display = 'block';
            } else {
                durationError.style.display = 'none';
            }

            nextBtn.disabled = !(selectedOccasion && isValid);
        }

        // Next button — save and proceed
        nextBtn.addEventListener('click', () => {
            if (nextBtn.disabled) return;
            const eventData = {
                occasion: selectedOccasion,
                date: eventDate.value,
                startTime: timeFrom.value,
                duration: diffHours.toFixed(1) + ' hours',
                hours: parseFloat(diffHours.toFixed(1))
            };
            localStorage.setItem('festivoEvent', JSON.stringify(eventData));
            window.location.href = 'products.php';
        });
    </script>
</body>

</html>
