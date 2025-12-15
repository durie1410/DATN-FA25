<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thư Viện Online')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6C63FF; /* màu tím trendy */
            --secondary-color: #48c6ef;
            --success-color: #00BFA6;
            --warning-color: #FFC700;
            --danger-color: #FF5252;
            --info-color: #29b6f6;
            --light-color: #f4f8fb;
            --dark-color: #1a2035;
            --gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        body {
            font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-color);
            color: var(--dark-color);
            letter-spacing: 0.02em;
        }
        .container-fluid {
            padding: 32px 8px 8px 8px;
        }
        .mobile-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #fff;
            border-top: 2px solid #e0e3ed;
            box-shadow: 0 -3px 14px 0 rgba(108,99,255,0.07);
            z-index: 1000;
            display: none;
        }
        .mobile-nav-item {
            flex: 1;
            text-align: center;
            padding: 14px 5px 7px 5px;
            font-weight: 600;
            font-size: 1.02rem;
            color: #585858;
            text-decoration: none;
            border-top: 2.5px solid transparent;
            transition: color .2s, border-color .2s, background .2s;
            background: none;
        }
        .mobile-nav-item.active,
        .mobile-nav-item:hover {
            color: var(--primary-color);
            border-top: 2.5px solid var(--primary-color);
            background: rgba(108,99,255,0.07);
        }
        .mobile-nav-item i {
            display: block;
            font-size: 1.26rem;
            margin-bottom: 2px;
        }
        .mobile-nav-item span {
            font-size: 0.8rem;
        }
        .card {
            border: none;
            border-radius: 18px !important;
            background: #fff;
            transition: box-shadow 0.2s;
            box-shadow: 0 4px 28px rgba(108,99,255,0.08);
            margin-bottom: 28px;
        }
        .card-header {
            background: var(--gradient);
            color: #fff;
            border-radius: 18px 18px 0 0 !important;
            border: none;
            font-size: 1.23rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        .btn {
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.02rem;
            letter-spacing: 0.02em;
            box-shadow: 0 1px 6px rgba(108,99,255,0.10);
            border: none;
            transition: all 0.18s ease;
        }
        .btn-primary {
            background: var(--gradient) !important;
            color: #fff !important;
            border: none;
        }
        .btn-primary:hover, .btn-primary:focus {
            filter: brightness(0.97);
            box-shadow: 0 3px 18px 0 rgba(108,99,255,0.17);
            transform: translateY(-3px) scale(1.03);
            opacity: .94;
        }
        .btn-secondary {
            background: #ecefff !important;
            color: #585858 !important;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #ff9068, #fd746c) !important;
            color: #fff !important;
        }
        .form-control {
            border-radius: 12px;
            border: 2px solid #ecefff;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.15rem rgba(108, 99, 255, .10);
        }
        .table-responsive {
            border-radius: 15px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 3px 20px 0 rgba(108,99,255,0.08);
        }
        .toast-container {
            position: fixed; right: 20px; top: 28px; z-index: 2000; }
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .loading-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        @media (max-width: 768px) {
            .mobile-nav { display: flex; }
            body { padding-bottom: 90px; }
            .container-fluid { padding-top: 20px; }
            .card { margin-bottom: 14px; }
            .btn { width: 100%; margin-bottom: 11px; }
            .modal-dialog { margin: 10px; }
        }
        @media (min-width:1025px) {
            .container-fluid { max-width: 1280px; margin: 0 auto; }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Main Content -->
    <div class="container-fluid">
        @yield('content')
    </div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Trang chủ</span>
        </a>
        <a href="{{ route('books.public') }}" class="mobile-nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span>Sách</span>
        </a>
        <a href="{{ route('categories.index') }}" class="mobile-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-list"></i>
            <span>Danh mục</span>
        </a>
        @auth
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Tài khoản</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="mobile-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
            <i class="fas fa-sign-in-alt"></i>
            <span>Đăng nhập</span>
        </a>
        <a href="{{ route('register') }}" class="mobile-nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i>
            <span>Đăng ký</span>
        </a>
        <a href="{{ route('register.reader.form') }}" class="mobile-nav-item {{ request()->routeIs('register.reader.*') ? 'active' : '' }}">
            <i class="fas fa-book-reader"></i>
            <span>Đăng ký độc giả</span>
        </a>
        @endauth
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Mobile utilities
        const MobileUtils = {
            // Show loading spinner
            showLoading: function() {
                document.getElementById('loadingSpinner').style.display = 'block';
            },
            
            // Hide loading spinner
            hideLoading: function() {
                document.getElementById('loadingSpinner').style.display = 'none';
            },
            
            // Show toast notification
            showToast: function(message, type = 'info') {
                const toastContainer = document.getElementById('toastContainer');
                const toastId = 'toast-' + Date.now();
                
                const toastHtml = `
                    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-${this.getToastIcon(type)} text-${type} me-2"></i>
                            <strong class="me-auto">Thông báo</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    toastElement.remove();
                }, 5000);
            },
            
            getToastIcon: function(type) {
                const icons = {
                    'success': 'check-circle',
                    'error': 'exclamation-circle',
                    'warning': 'exclamation-triangle',
                    'info': 'info-circle'
                };
                return icons[type] || 'info-circle';
            },
            
            // Handle form submissions with loading
            handleFormSubmit: function(formSelector) {
                const forms = document.querySelectorAll(formSelector);
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        MobileUtils.showLoading();
                    });
                });
            },
            
            // Initialize mobile features
            init: function() {
                this.handleFormSubmit('form');
                
                // Add touch feedback for buttons
                document.querySelectorAll('.btn').forEach(btn => {
                    btn.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.95)';
                    });
                    
                    btn.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                    });
                });
            }
        };
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            MobileUtils.init();
            // Hide loading spinner when page is fully loaded
            MobileUtils.hideLoading();
        });
        
        // Also hide loading spinner when window is fully loaded
        window.addEventListener('load', function() {
            MobileUtils.hideLoading();
        });
    </script>
    
    @yield('scripts')
</body>
</html>
