<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal | Order Processor</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/cooper-black" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --staff-bg: #F8F9FA;
            --staff-brown: #4A2C2A;
            --staff-accent: #B07051; 
            --staff-white: #FFFFFF;
        }

        body { 
            margin: 0; 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--staff-bg); 
            color: var(--staff-brown);
            display: flex;
            transition: all 0.3s ease;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 280px;
            background: var(--staff-white);
            height: 100vh;
            position: fixed;
            left: 0;
            border-right: 1px solid #E0E0E0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1000;
            box-sizing: border-box;
        }

        .sidebar.hidden { left: -280px; }

        .search-bar {
            background: #F5F5F5;
            border-radius: 12px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .search-bar input { border: none; background: transparent; outline: none; width: 100%; margin-left: 10px; font-size: 14px; }

        .nav-group { margin-bottom: 20px; }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--staff-brown);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 5px;
            font-size: 14px;
            transition: 0.2s;
        }

        .nav-item i { width: 25px; font-size: 18px; margin-right: 10px; opacity: 0.7; }
        
        .nav-item:hover, .nav-item.active { 
            background: #FDF9F7;
            font-weight: 700; 
            box-shadow: inset 4px 0 0 var(--staff-accent); 
            color: var(--staff-accent);
        }

        .divider { height: 1px; background: #E0E0E0; margin: 20px 0; }

        /* --- MAIN WRAPPER --- */
        .main-wrapper { 
            margin-left: 280px; 
            width: calc(100% - 280px); 
            transition: all 0.3s ease;
        }

        .main-wrapper.expanded { margin-left: 0; width: 100%; }
        
        /* --- TOP NAV --- */
        .top-nav {
            height: 75px;
            background: var(--staff-white);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            border-bottom: 3px solid var(--staff-accent); 
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--staff-brown);
            font-size: 20px;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 18px;
            border-radius: 50px;
            border: 1.5px solid var(--staff-accent);
            background: rgba(176, 112, 81, 0.05);
        }

        .user-name { font-size: 14px; font-weight: 700; }
        .user-role { font-size: 11px; opacity: 0.6; }

        .content-body { padding: 40px; }
        
        .dashboard-content {
            background: var(--staff-white);
            border-radius: 20px;
            padding: 40px;
            min-height: 80vh;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        h1 { font-family: 'Cooper Black', serif; color: var(--staff-brown); }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="search-bar">
            <i class="fa fa-search" style="opacity:0.4;"></i>
            <input type="text" placeholder="Search">
        </div>

        <div class="nav-group">
            <a href="{{ route('staff.dashboard') }}" class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            <a href="{{ route('staff.orders') }}" class="nav-item {{ request()->routeIs('staff.orders*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Orders
            </a>
            <a href="{{ route('staff.catalog') }}" class="nav-item {{ request()->routeIs('staff.catalog') ? 'active' : '' }}">
                <i class="fa-solid fa-cookie-bite"></i> Product Catalog
            </a>
        </div>

        <div class="divider"></div>

        <div class="nav-group">
            <a href="{{ route('staff.settings') }}" class="nav-item {{ request()->routeIs('staff.settings*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="nav-item" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left; font-family: Poppins;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-wrapper" id="main-wrapper">
        <div class="top-nav">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="toggle-btn" id="toggle-sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <div style="font-weight: 600; font-size: 14px; color: #666;">
                    Staff Dashboard
                </div>
            </div>

            <div class="user-profile">
                <div style="text-align: right;">
                    <div class="user-name">{{ Auth::user()->name ?? 'Staff' }}</div>
                    <div class="user-role">Order Processor</div>
                </div>
                <i class="fa-solid fa-circle-user" style="font-size: 32px; color: var(--staff-accent);"></i>
            </div>
        </div>

        <div class="content-body">
            <div class="dashboard-content">
                @yield('staff-content')
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            mainWrapper.classList.toggle('expanded');
        });
    </script>
</body>
</html>