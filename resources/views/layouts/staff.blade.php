<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Staff Dashboard - Thư Viện Online')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Staff Dashboard for Library Management System" name="description">
    <meta content="Library Management" name="author">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --staff-primary: #667eea;
            --staff-secondary: #764ba2;
            --staff-accent: #f093fb;
            --staff-bg: #f8f9fa;
        }
        
        body {
            background-color: var(--staff-bg);
        }
        
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 14px 20px;
            border-radius: 10px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-weight: 600;
        }
        
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 250px;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }
        
        .staff-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 8px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        
        .navbar {
            background: white !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1rem 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
                    <div class="p-3">
                        <h5 class="navbar-brand mb-0">
                            <i class="fas fa-book-open me-2"></i>
                            Thư Viện
                        </h5>
                        <div class="mt-2">
                            <span class="staff-badge">
                                <i class="fas fa-user-tie me-1"></i>
                                Nhân viên
                            </span>
                        </div>
                    </div>
                    
                    <nav class="nav flex-column px-2 mt-3">
                        <a class="nav-link {{ request()->routeIs('staff.dashboard*') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.books*') ? 'active' : '' }}" href="{{ route('staff.books.index') }}">
                            <i class="fas fa-book me-2"></i>
                            <span>Quản lý sách</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.categories*') ? 'active' : '' }}" href="{{ route('staff.categories.index') }}">
                            <i class="fas fa-folder me-2"></i>
                            <span>Danh mục</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.readers*') ? 'active' : '' }}" href="{{ route('staff.readers.index') }}">
                            <i class="fas fa-users me-2"></i>
                            <span>Quản lý độc giả</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.borrows*') ? 'active' : '' }}" href="{{ route('staff.borrows.index') }}">
                            <i class="fas fa-hand-holding me-2"></i>
                            <span>Mượn/Trả sách</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.reservations*') ? 'active' : '' }}" href="{{ route('staff.reservations.index') }}">
                            <i class="fas fa-calendar-check me-2"></i>
                            <span>Đặt chỗ</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.reviews*') ? 'active' : '' }}" href="{{ route('staff.reviews.index') }}">
                            <i class="fas fa-star me-2"></i>
                            <span>Đánh giá</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.fines*') ? 'active' : '' }}" href="{{ route('staff.fines.index') }}">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span>Phạt</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.reports*') ? 'active' : '' }}" href="{{ route('staff.reports.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>
                            <span>Báo cáo</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('staff.notifications*') ? 'active' : '' }}" href="{{ route('staff.notifications.index') }}">
                            <i class="fas fa-bell me-2"></i>
                            <span>Thông báo</span>
                        </a>
                        
                        <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.2); margin: 20px 12px !important;">
                        
                        <a class="nav-link" href="{{ route('home') }}" style="color: rgba(255, 255, 255, 0.7);">
                            <i class="fas fa-home me-2"></i>
                            <span>Về trang chủ</span>
                        </a>
                    </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <button class="btn btn-link" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Thông tin cá nhân</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (required for some Bootstrap features) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>

