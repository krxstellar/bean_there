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
    </style>
</head>
<body>

    <nav class="navbar">
        <div style="width: 100px;"></div> 

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

            <div class="icon-circle" id="navTrackBtn" style="cursor: pointer;" @authenticated onclick="window.location.href='{{ route('orders.index') }}'" @endauthenticated>
                <img src="{{ asset('images/Track.png') }}" alt="Track">
            </div>
            
            <a href="{{ route('cart.index') }}" class="icon-circle {{ request()->routeIs('cart.index') ? 'active-page' : '' }}">
                <img src="{{ asset('images/Cart.png') }}" alt="Cart">
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="cart-badge">
                        {{ array_sum(array_column(session('cart'), 'quantity')) }}
                    </span>
                @endif
            </a>

            <a href="{{ route('login') }}" class="icon-circle {{ request()->is('login') || request()->is('register') ? 'active-page' : '' }}">
                <img src="{{ asset('images/User Profile.png') }}" alt="Profile">
            </a>

            @auth
                <div style="margin-left: 8px; color: #4A2C2A; font-size: 12px;">
                    <div style="margin-bottom: 4px;">{{ Auth::user()->name }}</div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #4A2C2A; cursor: pointer; font-size: 12px; text-decoration: underline;">Logout</button>
                    </form>
                </div>
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

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-column contact">
                <h3>CONTACT US</h3>
                <p>Call us today at</p>
                <p><strong>+0123456789</strong></p>
                <div style="height: 15px;"></div>
                <p>or send a message to</p>
                <p><strong>support@local.com</strong></p>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-column links">
                <h3>QUICK LINKS</h3>
                <ul>
                    <li><a id="footerSearchLink">Search</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                    <li><a href="{{ route('checkout') }}">Shipping Policy</a></li>
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

            // Live filtering for menu pages
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

            // Popup auto-fade
            if (popup) {
                setTimeout(() => {
                    popup.classList.add('fade-out');
                    setTimeout(() => popup.remove(), 500); 
                }, 3000); 
            }

            // Handle search from URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');
            if (searchQuery) {
                navSearchInput.classList.add('active');
                navSearchInput.value = searchQuery;
                setTimeout(() => navSearchInput.dispatchEvent(new Event('input')), 100);
            }
        });
    </script>
</body>
</html>