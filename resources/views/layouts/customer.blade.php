<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bean There</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/cooper-black" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body { 
            margin: 0; 
            font-family: 'Poppins', sans-serif; 
            background-color: #FDF9F0; 
            padding-top: 60px; 
            color: #4A2C2A; 
        }

        /* --- NAVBAR STYLES --- */
        .navbar {
            background-color: #AEA9A0; 
            height: 60px; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            box-sizing: border-box; 
        }

        .nav-greeting {
            min-width: 150px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: #4A2C2A;
        }

        .logout-btn {
            background: none;
            border: 1.5px solid #4A2C2A;
            color: #4A2C2A;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #4A2C2A;
            color: #FDF9F0;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 50px; 
            margin: 0;
            padding: 0;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-links li { position: relative; }

        .nav-links a {
            text-decoration: none;
            color: #4A2C2A; 
            font-weight: 500;
            font-size: 14px;
            text-transform: capitalize;
            transition: all 0.3s ease;
            position: relative;
            padding-bottom: 5px;
            display: inline-block;
            cursor: pointer;
        }

        .nav-links a:hover { color: #FDF9F0; }

        .nav-links a.active-page {
            font-weight: 800;
            border-bottom: 2px solid #4A2C2A; 
        }

        /* --- DROPDOWN MENU STYLES --- */
        .dropdown-content {
            display: none; 
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #FDF9F0;
            min-width: 180px;
            z-index: 1001;
            margin-top: 15px;
            padding: 10px 0;
            border: 2px solid #4A2C2A; 
            border-radius: 15px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.15); 
        }

        .dropdown-content.show { display: block; }

        .dropdown-content a {
            color: #4A2C2A;
            padding: 12px 20px;
            display: block;
            font-weight: 500;
            text-align: left;
            border-bottom: none !important;
            transition: background 0.2s;
        }

        .dropdown-content a:hover {
            background-color: rgba(174, 169, 160, 0.2);
            color: #4A2C2A;
        }

        .fa-chevron-down {
            font-size: 10px;
            margin-left: 5px;
            transition: transform 0.3s;
        }

        /* --- NAV ICONS --- */
        .nav-icons { display: flex; gap: 12px; margin-left: auto; align-items: center; }

        .icon-circle {
            background-color: #FDF9F0; 
            width: 32px; 
            height: 32px; 
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: opacity 0.2s;
            text-decoration: none; 
            position: relative; 
        }

        .icon-circle:hover { opacity: 0.8; }
        .icon-circle img { width: 16px; height: 16px; object-fit: contain; }

        /* --- SEARCH STYLES --- */
        .search-wrapper {
            display: flex;
            align-items: center;
            position: relative;
        }

        #navSearchInput {
            width: 0;
            opacity: 0;
            padding: 5px 0;
            border: none;
            border-bottom: 2px solid #4A2C2A;
            background: transparent;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: #4A2C2A;
            transition: all 0.4s ease;
            outline: none;
        }

        #navSearchInput.active {
            width: 150px;
            opacity: 1;
            padding: 5px 10px;
            margin-right: 5px;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #4A2C2A; 
            color: #FDF9F0;             
            font-size: 10px;
            font-weight: 800;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #FDF9F0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            font-family: 'Poppins', sans-serif;
        }

        .container { width: 100%; }

        .cart-popup {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #4A2C2A;
            color: #FDF9F0;
            padding: 15px 25px;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .fade-out {
            transition: opacity 0.5s ease-out;
            opacity: 0;
        }

        /* --- FOOTER STYLES --- */
        .main-footer {
            background-color: #AEA9A0;
            padding: 80px 8%;
            color: #4A2C2A; 
            margin-top: 0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: stretch; 
        }

        .footer-column { flex: 1; display: flex; flex-direction: column; }
        .footer-column.contact { text-align: right; padding-right: 40px; }
        .footer-column.links { text-align: center; padding: 0 20px; }
        .footer-column.bestie { text-align: left; padding-left: 40px; }

        .footer-column h3 {
            font-family: 'Cooper Black', serif;
            font-size: 1.5rem;
            margin-bottom: 25px;
            text-transform: uppercase;
            color: #4A2C2A;
        }

        .footer-column p, .footer-column ul { font-size: 0.85rem; line-height: 1.6; margin: 0; }
        .footer-column ul { list-style: none; padding: 0; }
        .footer-column ul li { margin-bottom: 8px; }
        .footer-column ul li a { text-decoration: none; color: #4A2C2A; transition: opacity 0.2s; cursor: pointer; }
        .footer-column ul li a:hover { text-decoration: underline; opacity: 0.7; }

        .footer-divider { width: 1px; background-color: #4A2C2A; opacity: 0.3; margin: 10px 0; }

        .footer-socials {
            margin-top: 15px;
            display: flex;
            flex-direction: row;
            gap: 15px; 
            align-items: center;
            justify-content: flex-start;
        }

        .footer-socials a { color: #4A2C2A; font-size: 1.1rem; text-decoration: none; transition: opacity 0.2s; }
        .footer-socials a:hover { opacity: 0.6; }

        @media (max-width: 850px) {
            .footer-container { flex-direction: column; text-align: center; gap: 40px; }
            .footer-column.contact, .footer-column.links, .footer-column.bestie { text-align: center; padding: 0; }
            .footer-divider { display: none; }
            .footer-socials { justify-content: center; }
        }


        .qty-input {
            -moz-appearance: textfield;
            -webkit-appearance: none;
            appearance: none;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid rgba(74,44,42,0.2);
            background: #fff;
            text-align: center;
        }
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .qty-controls { display: flex; align-items: center; gap: 8px; width: 220px; margin: 0 auto; height: 44px; box-sizing: border-box; }
        .qty-controls .qty-decrement,
        .qty-controls .qty-increment {
            width: 44px;
            height: 44px;
            padding: 0;
            border-radius: 8px;
            border: 1.5px solid #4A2C2A;
            background: transparent;
            color: #4A2C2A;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            box-sizing: border-box;
        }
        .qty-controls .qty-input { flex: 1; min-width: 50px; max-width: 120px; height: 44px; box-sizing: border-box; font-size: 16px; }
        .qty-controls + .add-to-cart-btn {
            display: block;
            margin: 8px auto 0 auto;
            width: 220px;
            height: 44px;
            padding: 8px 12px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-greeting">
            @auth
                <span>Hello, {{ Auth::user()->name }}!</span>
            @endauth
        </div>

        <ul class="nav-links">
            <li><a href="{{ route('welcome') }}" class="{{ request()->routeIs('welcome') ? 'active-page' : '' }}">Home</a></li>
            <li>
                <a id="menuToggle" class="{{ request()->routeIs('menu.*') ? 'active-page' : '' }}">
                    Menu <i class="fa-solid fa-chevron-down"></i>
                </a>
                <div id="menuDropdown" class="dropdown-content">
                    <a href="{{ route('menu.pastries') }}">Pastries</a>
                    <a href="{{ route('menu.drinks') }}">Drinks</a>
                </div>
            </li>
            <li><a href="{{ route('faqs') }}" class="{{ request()->routeIs('faqs') ? 'active-page' : '' }}">FAQs</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active-page' : '' }}">About us</a></li>
        </ul>

        <div class="nav-icons">
            <div class="search-wrapper">
                <input type="text" id="navSearchInput" placeholder="Search products...">
                <div class="icon-circle" id="navSearchBtn">
                    <img src="{{ asset('images/Search.png') }}" alt="Search">
                </div>
            </div>

            <div class="icon-circle" id="navTrackBtn" style="cursor: pointer;" 
                @auth 
                    onclick="window.location.href='{{ route('orders.index') }}'" 
                @else 
                    onclick="showLoginModal('orders')" 
                @endauth>
                <img src="{{ asset('images/Track.png') }}" alt="Track">
            </div>
            
            @auth
                <a href="{{ route('cart.index') }}" class="icon-circle {{ request()->routeIs('cart.index') ? 'active-page' : '' }}">
                    <img src="{{ asset('images/Cart.png') }}" alt="Cart">
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="cart-badge">
                            {{ array_sum(array_column(session('cart'), 'quantity')) }}
                        </span>
                    @endif
                </a>
            @else
                <div class="icon-circle" style="cursor: pointer;" onclick="showLoginModal('cart')">
                    <img src="{{ asset('images/Cart.png') }}" alt="Cart">
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="icon-circle {{ request()->is('login') || request()->is('register') ? 'active-page' : '' }}">
                    <img src="{{ asset('images/User Profile.png') }}" alt="Profile">
                </a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}" style="margin: 0; margin-left: 5px;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @if(session('success'))
        <div id="cart-popup" class="cart-popup">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('order_success'))
        <div id="shipping-policy-overlay" class="policy-overlay">
            <div class="policy-modal">
                <div class="policy-header">
                    <i class="fa-solid fa-check-circle" style="color: #27ae60; font-size: 2.5rem;"></i>
                    <h2>Order Placed Successfully!</h2>
                </div>
                <div class="policy-content">
                    <h3>Shipping Policy</h3>
                    <div class="policy-item">
                        <strong>How we process deliveries</strong>
                        <p>We ensure all baked goods are packed securely to maintain freshness. Deliveries are scheduled within Metro Manila from Monday to Saturday.</p>
                    </div>
                    <div class="policy-item">
                        <strong>Pick-up Details</strong>
                        <p>For pick-up orders, please wait for your "Processing" notification before heading to our location.</p>
                    </div>
                    <div class="policy-item">
                        <strong>Standard Lead Time</strong>
                        <p>Most orders require a 1-day lead time to prepare your items fresh from the oven.</p>
                    </div>
                </div>
                <button id="policy-ok-btn" class="policy-ok-btn">OK, Got it!</button>
            </div>
        </div>

        <style>
            .policy-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }
            .policy-modal {
                background: #FDF9F0;
                border: 2px solid #4A2C2A;
                border-radius: 20px;
                padding: 40px;
                max-width: 500px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
                text-align: center;
                font-family: 'Poppins', sans-serif;
                color: #4A2C2A;
            }
            .policy-header h2 {
                font-family: 'Cooper Black', serif;
                font-size: 1.5rem;
                margin: 15px 0 20px 0;
                color: #4A2C2A;
            }
            .policy-content h3 {
                font-size: 1.1rem;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid rgba(74, 44, 42, 0.2);
            }
            .policy-item {
                text-align: left;
                margin-bottom: 15px;
            }
            .policy-item strong {
                display: block;
                font-size: 0.9rem;
                margin-bottom: 5px;
            }
            .policy-item p {
                font-size: 0.85rem;
                line-height: 1.5;
                margin: 0;
                color: #5a4a48;
            }
            .policy-ok-btn {
                background-color: #4A2C2A;
                color: #FDF9F0;
                border: none;
                padding: 14px 50px;
                border-radius: 25px;
                font-family: 'Poppins', sans-serif;
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                margin-top: 20px;
                transition: opacity 0.3s;
            }
            .policy-ok-btn:hover {
                opacity: 0.9;
            }
        </style>

        <script>
            document.getElementById('policy-ok-btn').addEventListener('click', function() {
                document.getElementById('shipping-policy-overlay').style.display = 'none';
            });
        </script>
    @endif

    <!-- Login Required Modal -->
    <div id="login-modal-overlay" class="login-modal-overlay" style="display: none;">
        <div class="login-modal">
            <div class="login-modal-header">
                <i class="fa-solid fa-lock" style="color: #4A2C2A; font-size: 2.5rem;"></i>
                <h2>Login Required</h2>
            </div>
            <div class="login-modal-content">
                <p id="login-modal-message">Please login first to view your order history.</p>
            </div>
            <button id="login-modal-btn" class="login-modal-btn">OK, Login Now</button>
        </div>
    </div>

    <style>
        .login-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .login-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .login-modal {
            background: #FDF9F0;
            border: 2px solid #4A2C2A;
            border-radius: 20px;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            color: #4A2C2A;
            transform: scale(0.9) translateY(-20px);
            transition: transform 0.3s ease;
        }
        .login-modal-overlay.show .login-modal {
            transform: scale(1) translateY(0);
        }
        .login-modal-header h2 {
            font-family: 'Cooper Black', serif;
            font-size: 1.5rem;
            margin: 15px 0 10px 0;
            color: #4A2C2A;
        }
        .login-modal-content p {
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
            color: #5a4a48;
        }
        .login-modal-btn {
            background-color: #4A2C2A;
            color: #FDF9F0;
            border: none;
            padding: 14px 40px;
            border-radius: 25px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 25px;
            transition: opacity 0.3s, transform 0.2s;
        }
        .login-modal-btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
    </style>

    <script>
        function showLoginModal(type) {
            const messages = {
                'orders': 'Please login first to view your order history.',
                'cart': 'Please login first to access your cart.'
            };
            document.getElementById('login-modal-message').textContent = messages[type] || messages['orders'];
            const overlay = document.getElementById('login-modal-overlay');
            overlay.style.display = 'flex';
            // Trigger reflow for animation
            overlay.offsetHeight;
            overlay.classList.add('show');
        }
        function hideLoginModal() {
            const overlay = document.getElementById('login-modal-overlay');
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }
        document.getElementById('login-modal-btn').addEventListener('click', function() {
            window.location.href = '{{ route("login") }}';
        });
        document.getElementById('login-modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                hideLoginModal();
            }
        });
    </script>

    @php $settings = cache('admin.settings', []); @endphp

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-column contact">
                <h3>CONTACT US</h3>
                <p>Call us today at</p>
                <p><strong>{{ $settings['contact_number'] ?? '0987 654 3210' }}</strong></p>
                <div style="height: 15px;"></div>
                <p>or send a message to</p>
                <p><strong>{{ $settings['email'] ?? 'support@beanthere.com' }}</strong></p>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-column links">
                <h3>QUICK LINKS</h3>
                <ul>
                    <li><a id="footerSearchLink">Search</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                    <li><a href="{{ route('shipping.policy') }}">Shipping Policy</a></li>
                </ul>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-column bestie">
                <h3>HEY, BESTIE!</h3>
                <p>Follow us on Facebook, X, and</p>
                <p>Instagram for exclusive updates!</p>
                <div class="footer-socials">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navSearchBtn = document.getElementById('navSearchBtn');
            const navSearchInput = document.getElementById('navSearchInput');
            const footerSearchLink = document.getElementById('footerSearchLink');
            const menuToggle = document.getElementById('menuToggle');
            const menuDropdown = document.getElementById('menuDropdown');
            const popup = document.getElementById('cart-popup');

            function openSearch() {
                navSearchInput.classList.add('active');
                navSearchInput.focus();
            }

            function executeGlobalSearch() {
                const query = navSearchInput.value.toLowerCase().trim();
                const isMenuPage = window.location.pathname.includes('pastries') || 
                                   window.location.pathname.includes('drinks');

                if (query !== "") {
                    if (isMenuPage) {
                    } else {
                        window.location.href = "{{ route('menu.pastries') }}?search=" + encodeURIComponent(query);
                    }
                }
            }

            navSearchBtn.addEventListener('click', function() {
                if (navSearchInput.classList.contains('active') && navSearchInput.value.trim() !== "") {
                    executeGlobalSearch();
                } else {
                    navSearchInput.classList.toggle('active');
                    if (navSearchInput.classList.contains('active')) navSearchInput.focus();
                }
            });

            navSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') executeGlobalSearch();
            });

            // LIVE FILTERING FOR MENU PAGES
            navSearchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const productCards = document.querySelectorAll('.product-card');
                const categories = document.querySelectorAll('.category-section');

                productCards.forEach(card => {
                    const title = card.querySelector('h3')?.textContent.toLowerCase() || "";
                    card.style.display = title.includes(query) ? '' : 'none';
                });

                categories.forEach(section => {
                    const visibleCards = section.querySelectorAll('.product-card:not([style*="display: none"])');
                    section.style.display = (visibleCards.length > 0 || query === "") ? "block" : "none";
                });
            });

            footerSearchLink.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                openSearch();
            });

            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                menuDropdown.classList.toggle('show');
            });

            document.addEventListener('click', (e) => {
                if (!menuToggle.contains(e.target)) menuDropdown.classList.remove('show');
                if (!navSearchBtn.contains(e.target) && !navSearchInput.contains(e.target)) {
                    navSearchInput.classList.remove('active');
                }
            });

            // POPUP AUTO-FADE
            if (popup) {
                setTimeout(() => {
                    popup.classList.add('fade-out');
                    setTimeout(() => popup.remove(), 500); 
                }, 3000); 
            }

            // HANDLE SEARCH FROM URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');
            if (searchQuery) {
                navSearchInput.classList.add('active');
                navSearchInput.value = searchQuery;
                setTimeout(() => navSearchInput.dispatchEvent(new Event('input')), 100);
            }
        });
    </script>
    <script>
        // Quantity plus/minus handlers for add-to-cart forms
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('qty-increment') || e.target.classList.contains('qty-decrement')) {
                // find the closest form and its .qty-input
                const form = e.target.closest('form');
                const input = form ? form.querySelector('.qty-input') : e.target.parentElement.querySelector('.qty-input');
                if (!input) return;
                const step = 1;
                const min = parseInt(input.getAttribute('min')) || 1;
                let value = parseInt(input.value) || min;
                if (e.target.classList.contains('qty-increment')) {
                    value = value + step;
                } else {
                    value = Math.max(min, value - step);
                }
                input.value = value;
                // trigger input event in case other listeners rely on it
                input.dispatchEvent(new Event('input'));
            }
        });

        // Ensure manual edits respect min
        document.addEventListener('input', function(e) {
            if (e.target.classList && e.target.classList.contains('qty-input')) {
                const min = parseInt(e.target.getAttribute('min')) || 1;
                let val = parseInt(e.target.value) || min;
                if (val < min) e.target.value = min;
            }
        });
    </script>
</body>
</html>