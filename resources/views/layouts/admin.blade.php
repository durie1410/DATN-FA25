<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Quản Lý Thư Viện WAKA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00ff99;
            --primary-dark: #00d68f;
            --primary-light: #33ffad;
            --secondary-color: #ffdd00;
            --secondary-dark: #ffc700;
            --accent-purple: #8b5cf6;
            --accent-blue: #3b82f6;
            --accent-pink: #ec4899;
            
            --background-dark: #0a0a0a;
            --background-card: #141414;
            --background-elevated: #1a1a1a;
            --header-bg: linear-gradient(135deg, #0a2e1a 0%, #052d23 100%);
            --sidebar-bg: #0d1117;
            --sidebar-hover: rgba(0, 255, 153, 0.08);
            --sidebar-active: rgba(0, 255, 153, 0.15);
            
            --text-primary: #ffffff;
            --text-secondary: #d4d4d8;
            --text-muted: #9ca3af;
            --text-disabled: #6b7280;
            
            --border-color: rgba(0, 255, 153, 0.12);
            --border-hover: rgba(0, 255, 153, 0.25);
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
            --shadow-primary: 0 0 20px rgba(0, 255, 153, 0.15);
            
            --transition-fast: 0.15s;
            --transition-normal: 0.3s;
            --transition-slow: 0.5s;
            --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
            --ease-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-dark);
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(0, 255, 153, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.03) 0%, transparent 50%);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.6;
        }

        /* Header - WAKA Style */
        .admin-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--header-bg);
            padding: 18px 60px;
            box-shadow: var(--shadow-lg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 26px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all var(--transition-normal) var(--ease-smooth);
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transition: width var(--transition-normal) var(--ease-smooth);
        }

        .logo:hover {
            transform: translateY(-1px);
        }

        .logo:hover::after {
            width: 100%;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            background: rgba(20, 20, 20, 0.6);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 11px 45px 11px 18px;
            border-radius: 12px;
            width: 300px;
            transition: all var(--transition-normal) var(--ease-smooth);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }

        .header-search input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 255, 153, 0.1), var(--shadow-primary);
            width: 350px;
            background: rgba(20, 20, 20, 0.8);
        }

        .header-search input::placeholder {
            color: #888;
        }

        .header-search button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 20px;
            transition: all var(--transition-fast);
        }

        .header-search button:hover {
            background: rgba(0, 255, 153, 0.1);
        }

        .user-menu {
            position: relative;
        }

        .btn-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px 8px 8px;
            background: rgba(20, 20, 20, 0.6);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 50px;
            cursor: pointer;
            transition: all var(--transition-normal) var(--ease-smooth);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .btn-user:hover {
            background: rgba(0, 255, 153, 0.12);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md), var(--shadow-primary);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 255, 153, 0.3);
            transition: transform var(--transition-fast) var(--ease-bounce);
        }

        .btn-user:hover .user-avatar {
            transform: scale(1.1) rotate(5deg);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            min-width: 260px;
            background: var(--background-elevated);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-xl), var(--shadow-primary);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transition: all var(--transition-normal) var(--ease-bounce);
            z-index: 1000;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-fast) var(--ease-smooth);
            font-size: 14px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background: rgba(0, 255, 153, 0.1);
            color: var(--primary-color);
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 8px 0;
        }

        .logout-item {
            color: #ff6b6b;
        }

        .logout-item:hover {
            background: rgba(255, 107, 107, 0.1);
            color: #ff5252;
        }

        /* Main Layout */
        .admin-layout {
            display: flex;
            min-height: calc(100vh - 72px);
        }

        /* Sidebar - WAKA Style */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            overflow-x: hidden;
            position: sticky;
            top: 72px;
            height: calc(100vh - 72px);
            backdrop-filter: blur(10px);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 255, 153, 0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 255, 153, 0.5);
        }
        
        .sidebar-menu {
            padding: 24px 0;
        }
        
        .menu-section-title {
            padding: 16px 24px 12px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 24px;
            margin: 2px 12px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-normal) var(--ease-smooth);
            cursor: pointer;
            position: relative;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, var(--primary-color), var(--primary-light));
            border-radius: 0 3px 3px 0;
            transition: height var(--transition-normal) var(--ease-smooth);
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .menu-item:hover::before {
            height: 60%;
        }

        .menu-item.active {
            background: var(--sidebar-active);
            color: var(--primary-color);
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--primary-color), var(--shadow-sm);
        }

        .menu-item.active::before {
            height: 70%;
            background: var(--primary-color);
            box-shadow: 0 0 12px rgba(0, 255, 153, 0.4);
        }

        .menu-item i {
            width: 20px;
            font-size: 16px;
        }
        
        .menu-badge {
            margin-left: auto;
            background: var(--primary-color);
            color: #000;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .menu-badge.new {
            background: #ff6b6b;
            color: white;
        }

        .submenu {
            background: rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-height: 0;
            transition: max-height var(--transition-normal) var(--ease-smooth);
        }

        .submenu.show {
            max-height: 500px;
        }
        
        .submenu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px 12px 50px;
            color: #999;
            text-decoration: none;
            transition: all var(--transition-fast);
            font-size: 13px;
        }
        
        .submenu-item:hover {
            background: var(--sidebar-hover);
            color: var(--primary-color);
            padding-left: 55px;
        }
        
        .submenu-item.active {
            color: var(--primary-color);
        }

        .arrow {
            margin-left: auto;
            transition: transform var(--transition-normal);
            font-size: 12px;
        }
        
        .arrow.rotated {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px;
            background: transparent;
            animation: fadeIn 0.5s var(--ease-smooth);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 34px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-title i {
            color: var(--primary-color);
        }

        .page-subtitle {
            font-size: 15px;
            color: #888;
            font-weight: 400;
        }

        /* Cards - WAKA Style */
        .card {
            background: var(--background-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 25px;
            transition: all var(--transition-normal) var(--ease-smooth);
            position: relative;
            overflow: hidden;
        }

        .card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
            transform: translateX(-100%);
            transition: transform var(--transition-slow) var(--ease-smooth);
        }

        .card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-lg), var(--shadow-primary);
            transform: translateY(-4px);
        }

        .card:hover::after {
            transform: translateX(100%);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary-color);
        }

        /* Buttons - WAKA Style */
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all var(--transition-normal) var(--ease-smooth);
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #000;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md), 0 0 20px rgba(0, 255, 153, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-color);
        }

        .btn-danger {
            background: #ff6b6b;
            color: white;
        }

        .btn-danger:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .btn-warning {
            background: var(--secondary-color);
            color: #000;
        }

        .btn-warning:hover {
            background: #ffc700;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 221, 0, 0.3);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 16px;
        }

        /* Forms - WAKA Style */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all var(--transition-normal) var(--ease-smooth);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 255, 153, 0.1);
            background: rgba(255, 255, 255, 0.08);
        }

        .form-control::placeholder {
            color: #666;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Tables - WAKA Style */
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead th {
            padding: 15px;
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(0, 255, 153, 0.2);
        }

        .table tbody tr {
            background: var(--background-card);
            border: 1px solid rgba(0, 255, 153, 0.1);
            transition: all var(--transition-fast);
        }

        .table tbody tr:hover {
            border-color: rgba(0, 255, 153, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .table tbody td {
            padding: 15px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .badge-danger {
            background: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
        }

        .badge-warning {
            background: rgba(255, 221, 0, 0.2);
            color: var(--secondary-color);
        }

        .badge-info {
            background: rgba(0, 255, 153, 0.2);
            color: var(--primary-color);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--background-card) 0%, var(--background-elevated) 100%);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 28px;
            transition: all var(--transition-normal) var(--ease-smooth);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--transition-normal) var(--ease-smooth);
        }

        .stat-card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-lg), var(--shadow-primary);
            transform: translateY(-6px) scale(1.02);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 13px;
            color: #888;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            transition: all var(--transition-normal) var(--ease-smooth);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon.primary {
            background: rgba(0, 255, 153, 0.15);
            color: var(--primary-color);
        }

        .stat-icon.warning {
            background: rgba(255, 221, 0, 0.15);
            color: var(--secondary-color);
        }

        .stat-icon.danger {
            background: rgba(255, 107, 107, 0.15);
            color: #ff6b6b;
        }

        .stat-icon.success {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            color: #888;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
        }

        .alert-danger {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff6b6b;
        }

        .alert-warning {
            background: rgba(255, 221, 0, 0.15);
            border: 1px solid rgba(255, 221, 0, 0.3);
            color: var(--secondary-color);
        }

        .alert-info {
            background: rgba(0, 255, 153, 0.15);
            border: 1px solid rgba(0, 255, 153, 0.3);
            color: var(--primary-color);
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 10px 15px;
            background: var(--background-card);
            border: 1px solid rgba(0, 255, 153, 0.1);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .pagination a:hover {
            background: rgba(0, 255, 153, 0.1);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .pagination .active span {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #000;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }

            .main-content {
                padding: 30px;
            }

            .admin-header {
                padding: 20px 30px;
            }

            .header-search input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                z-index: 2000;
                transition: left var(--transition-normal);
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
            padding: 20px;
            }

            .admin-header {
                padding: 15px 20px;
            }

            .header-search {
                display: none;
            }

            .page-title {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--background-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 255, 153, 0.3);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 255, 153, 0.5);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--primary-color);
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">WAKA ADMIN</div>
        </div>
        
        <div class="header-right">
            <div class="header-search">
                <form action="#" method="GET">
                    <input type="text" name="q" placeholder="Tìm kiếm...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                </div>
                
            <div class="user-menu">
                <button class="btn-user" onclick="toggleUserMenu()">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
                    <span>{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <a href="{{ route('home') }}" class="dropdown-item">
                        <i class="fas fa-home"></i>
                        Về trang chủ
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Hồ sơ của tôi
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Cài đặt
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item logout-item">
                            <i class="fas fa-sign-out-alt"></i>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
                </div>
                
    <!-- Main Layout -->
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-menu">
                <!-- Dashboard -->
                <div class="menu-section-title">DASHBOARD</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tổng quan</span>
                </a>

                <!-- Quản lý dữ liệu -->
                <div class="menu-section-title">QUẢN LÝ DỮ LIỆU</div>
                <a href="{{ route('admin.books.index') }}" class="menu-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Quản lý sách</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Thể loại</span>
                </a>
                <a href="{{ route('admin.publishers.index') }}" class="menu-item {{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Nhà xuất bản</span>
                </a>
                @if(Route::has('admin.authors.index'))
                <a href="{{ route('admin.authors.index') }}" class="menu-item {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit"></i>
                    <span>Tác giả</span>
                </a>
                @endif

                <!-- Quản lý người dùng -->
                <div class="menu-section-title">QUẢN LÝ NGƯỜI DÙNG</div>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Người dùng</span>
                </a>
                @if(Route::has('admin.readers.index'))
                <a href="{{ route('admin.readers.index') }}" class="menu-item {{ request()->routeIs('admin.readers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Độc giả</span>
                </a>
                @endif
                @if(Route::has('admin.librarians.index'))
                <a href="{{ route('admin.librarians.index') }}" class="menu-item {{ request()->routeIs('admin.librarians.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Thủ thư</span>
                </a>
                @endif

                <!-- Mượn trả sách -->
                <div class="menu-section-title">MƯỢN TRẢ SÁCH</div>
                <a href="{{ route('admin.borrows.index') }}" class="menu-item {{ request()->routeIs('admin.borrows.*') && !request('trang_thai') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Tất cả giao dịch</span>
                </a>
                @can('view-reservations')
                @if(Route::has('admin.reservations.index'))
                <a href="{{ route('admin.reservations.index') }}" class="menu-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Duyệt mượn sách</span>
                </a>
                @endif
                @endcan
                <a href="{{ route('admin.borrows.index', ['trang_thai' => 'Dang muon']) }}" class="menu-item {{ request()->routeIs('admin.borrows.index') && request('trang_thai')==='Dang muon' ? 'active' : '' }}">
                    <i class="fas fa-undo"></i>
                    <span>Duyệt trả sách</span>
                </a>

                <!-- Tài chính -->
                @if(Route::has('admin.fines.index'))
                <div class="menu-section-title">TÀI CHÍNH</div>
                <a href="{{ route('admin.fines.index') }}" class="menu-item {{ request()->routeIs('admin.fines.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Quản lý phí phạt</span>
                </a>
                @endif

                <!-- Báo cáo & Đánh giá -->
                <div class="menu-section-title">BÁO CÁO & ĐÁNH GIÁ</div>
                @if(Route::has('admin.reports.index'))
                <a href="{{ route('admin.reports.index') }}" class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Báo cáo</span>
                </a>
                @endif
                @if(Route::has('admin.reviews.index'))
                <a href="{{ route('admin.reviews.index') }}" class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Đánh giá</span>
                </a>
                @endif

                <!-- Hệ thống -->
                <div class="menu-section-title">HỆ THỐNG</div>
                @if(Route::has('admin.logs.index'))
                <a href="{{ route('admin.logs.index') }}" class="menu-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Nhật ký hệ thống</span>
                </a>
                @endif
                @can('manage-notifications')
                @if(Route::has('admin.notifications.index'))
                <a href="{{ route('admin.notifications.index') }}" class="menu-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Thông báo</span>
                </a>
                @endif
                @endcan
                @if(Route::has('admin.settings.index'))
                <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Cài đặt</span>
                </a>
                @endif
                @if(Route::has('admin.banners.index'))
                <a href="{{ route('admin.banners.index') }}" class="menu-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Quản lý Banner</span>
                </a>
                @endif
            </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
            <div>
                        <strong>Có lỗi xảy ra:</strong>
                        <ul style="margin: 8px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
            </div>
        </div>
            @endif
        
            @yield('content')
        </div>
    </div>
    
    <script>
        // Toggle user menu
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            if (!userMenu.contains(event.target)) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });

        // Toggle submenu
        function toggleSubmenu(id, element) {
            const submenu = document.getElementById(id);
            const arrow = element.querySelector('.arrow');
            
            submenu.classList.toggle('show');
            arrow.classList.toggle('rotated');
        }

        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
