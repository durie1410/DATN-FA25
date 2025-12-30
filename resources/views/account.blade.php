<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - Nhà Xuất Bản Xây Dựng</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <div class="logo-section">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-part1">THƯ VIỆN</span>
                    <span class="logo-part2">LIBHUB</span>
                </div>
            </div>
            <div class="hotline-section">
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách lẻ:</span>
                    <a href="tel:0327888669" class="hotline-number">0327888669</a>
                </div>
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách sỉ:</span>
                    <a href="tel:02439741791" class="hotline-number">02439741791 - 0327888669</a>
                </div>
            </div>
            <div class="user-actions">
                @auth
                    <div class="user-menu-dropdown" style="position: relative;">
                        <a href="#" class="auth-link user-menu-toggle">
                            <span class="user-icon">👤</span>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="user-dropdown-menu">
                            <div class="dropdown-header" style="padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: 600; color: #333;">
                                <span class="user-icon">👤</span>
                                {{ auth()->user()->name }}
                            </div>
                            @if(auth()->user()->reader)
                            <a href="{{ route('account.borrowed-books') }}" class="dropdown-item">
                                <span>📚</span> Sách đang mượn
                            </a>
                            @endif
                            <a href="{{ route('account') }}" class="dropdown-item">
                                <span>👤</span> Thông tin tài khoản
                            </a>
                            <a href="{{ route('account.change-password') }}" class="dropdown-item">
                                <span>🔒</span> Đổi mật khẩu
                            </a>
                            <a href="{{ route('orders.index') }}" class="dropdown-item">
                                <span>⏰</span> Lịch sử mua hàng
                            </a>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                            <div style="border-top: 1px solid #eee; margin-top: 5px;"></div>
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <span>📊</span> Dashboard
                            </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <span>➡️</span> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                    <style>
                        .user-menu-dropdown {
                            position: relative;
                        }
                        .user-menu-dropdown .user-dropdown-menu {
                            display: none;
                            position: absolute;
                            top: calc(100% + 5px);
                            right: 0;
                            background: white;
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                            min-width: 220px;
                            z-index: 1000;
                            overflow: hidden;
                        }
                        .user-menu-dropdown:hover .user-dropdown-menu {
                            display: block;
                        }
                        .user-menu-dropdown .dropdown-item {
                            display: block;
                            padding: 10px 15px;
                            color: #333;
                            text-decoration: none;
                            border-bottom: 1px solid #eee;
                            transition: background-color 0.2s;
                            cursor: pointer;
                        }
                        .user-menu-dropdown .dropdown-item:hover {
                            background-color: #f5f5f5;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn {
                            border: none;
                            background: none;
                            width: 100%;
                            text-align: left;
                            color: #d32f2f;
                            border-top: 1px solid #eee;
                            margin-top: 5px;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn:hover {
                            background-color: #ffebee;
                        }
                        .user-menu-dropdown .dropdown-item span {
                            margin-right: 8px;
                        }
                    </style>
                @else
                    <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
                @endauth
            </div>
        </div>
        <div class="header-nav">
            <div class="search-bar">
                <form action="{{ route('books.public') }}" method="GET" class="search-form">
                    <input type="text" name="keyword" placeholder="Tìm sách, tác giả, sản phẩm mong muốn..." value="{{ request('keyword') }}" class="search-input">
                    <button type="submit" class="search-button">🔍 Tìm kiếm</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb-nav">
        <div class="breadcrumb-container">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Thông tin tài khoản</span>
        </div>
    </nav>

    <main class="account-container">
        <aside class="account-sidebar">
            <div class="user-profile">
                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="username">{{ $user->name }}</div>
            </div>
            <nav class="account-nav">
                <ul>
                    @if($user->reader)
                    <li><a href="{{ route('account.borrowed-books') }}"><span class="icon">📚</span> Sách đang mượn</a></li>
                    @endif
                    <li class="active"><a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin khách hàng</a></li>
                    <li><a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a></li>
                    <li><a href="{{ route('orders.index') }}"><span class="icon">🛒</span> Lịch sử mua hàng</a></li>
                    <li><a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon">➡️</span> Đăng xuất</a></li>
                </ul>
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        <section class="account-content">
            <div class="account-details-form">
                <h2 class="form-title">Thông tin tài khoản</h2>
                
                @if(session('success'))
                    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul style="margin: 8px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="fullName">Tên đầy đủ của bạn</label>
                        <div class="input-with-icon">
                            <input type="text" id="fullName" name="name" value="{{ $user->name }}" readonly>
                            <span class="input-icon">📋</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại của bạn</label>
                        <div class="input-with-icon">
                            <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" value="{{ $user->phone ?? '' }}">
                            <span class="input-icon">📞</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email của bạn</label>
                        <div class="input-with-icon">
                            <input type="email" id="email" name="email" value="{{ $user->email }}" readonly>
                            <span class="input-icon">✉️</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="so_cccd">Số CCCD/CMND của bạn <span style="color: red;">*</span></label>
                        <div class="input-with-icon">
                            <input type="text" id="so_cccd" name="so_cccd" placeholder="Số CCCD/CMND" value="{{ $user->so_cccd ?? '' }}" maxlength="20" required>
                            <span class="input-icon">🆔</span>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="cccd_image">Ảnh CCCD/CMND <span style="color: red;">*</span></label>
                        <div style="margin-bottom: 10px;">
                            <input type="file" id="cccd_image" name="cccd_image" accept="image/jpeg,image/jpg,image/png,image/webp" style="display: none;" {{ !$user->cccd_image ? 'required' : '' }}>
                            <button type="button" onclick="document.getElementById('cccd_image').click()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                📷 Chọn ảnh CCCD/CMND
                            </button>
                            <span id="cccd_file_name" style="margin-left: 10px; color: #666; font-size: 14px;"></span>
                        </div>
                        <small style="color: #666; display: block; margin-bottom: 10px;">
                            💡 Lưu ý: Vui lòng upload ảnh rõ ràng, đầy đủ thông tin của CCCD/CMND. Định dạng: JPG, PNG, WEBP. Kích thước tối đa: 2MB.
                        </small>
                        <div id="cccd_image_preview" style="margin-top: 10px; display: none;">
                            <p style="margin-bottom: 5px; font-weight: 600; color: #333;">Xem trước ảnh:</p>
                            <div style="max-width: 400px;">
                                <img id="cccd_preview_img" src="" alt="Preview CCCD" onclick="openImageModal(this.src)" style="max-width: 100%; max-height: 250px; width: auto; height: auto; border: 2px solid #ddd; border-radius: 4px; padding: 5px; background: #f9f9f9; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; display: block; object-fit: contain;" onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Click để xem phóng to">
                            </div>
                            <p style="color: #666; font-size: 12px; margin-top: 5px;">💡 Click vào ảnh để xem phóng to</p>
                            <button type="button" onclick="removeCccdImage()" style="margin-top: 10px; padding: 8px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                ❌ Xóa ảnh
                            </button>
                        </div>
                        @if($user->cccd_image)
                        <div id="current_cccd_image" style="margin-top: 10px;">
                            <p style="margin-bottom: 5px; font-weight: 600; color: #333;">Ảnh hiện tại:</p>
                            @php
                                $imagePath = $user->cccd_image;
                                // Sử dụng asset() thay vì Storage::url() để đảm bảo URL đúng
                                $imageUrl = asset('storage/' . $imagePath);
                                $imageExists = Storage::disk('public')->exists($imagePath);
                            @endphp
                            @if($imageExists)
                            <div style="position: relative; display: inline-block; width: 100%; max-width: 400px;">
                                <img id="current_cccd_img" src="{{ $imageUrl }}?t={{ time() }}" alt="CCCD hiện tại" onclick="openImageModal('{{ $imageUrl }}')" style="max-width: 100%; max-height: 250px; width: auto; height: auto; border: 2px solid #ddd; border-radius: 4px; padding: 5px; background: #f9f9f9; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; display: block; object-fit: contain;" onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" title="Click để xem phóng to" onload="this.style.display='block'; document.getElementById('image_loading').style.display='none';" onerror="handleImageError(this)" loading="lazy">
                                <div id="image_loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.9); padding: 10px; border-radius: 4px; z-index: 10;">
                                    <span>Đang tải ảnh...</span>
                                </div>
                            </div>
                            <div id="image_error" style="display: none; padding: 20px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; color: #856404; margin-top: 10px;">
                                ⚠️ Không thể tải ảnh. Vui lòng upload lại ảnh CCCD/CMND.
                                <br><small>Đường dẫn: {{ $imagePath }}</small>
                            </div>
                            @else
                            <div style="padding: 20px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; color: #856404;">
                                ⚠️ Ảnh không tồn tại tại đường dẫn: {{ $imagePath }}. Vui lòng upload lại ảnh CCCD/CMND.
                            </div>
                            @endif
                            <p style="color: #666; font-size: 12px; margin-top: 5px;">💡 Click vào ảnh để xem phóng to | Nếu bạn upload ảnh mới, ảnh này sẽ được thay thế.</p>
                        </div>
                        @endif
                        
                        <!-- Modal để xem ảnh phóng to -->
                        <div id="imageModal" style="display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.95); cursor: pointer; animation: fadeIn 0.3s; overflow: auto;" onclick="closeImageModal()">
                            <span id="closeModalBtn" style="position: fixed; top: 20px; right: 35px; color: #f1f1f1; font-size: 50px; font-weight: bold; cursor: pointer; z-index: 100000; line-height: 1; transition: transform 0.2s; background: rgba(0,0,0,0.5); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.transform='scale(1.2)'; this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.transform='scale(1)'; this.style.background='rgba(0,0,0,0.5)'">&times;</span>
                            <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
                                <img id="modalImage" style="max-width: 95%; max-height: 95vh; border-radius: 8px; box-shadow: 0 8px 32px rgba(0,0,0,0.6); cursor: default; object-fit: contain; display: block;" onclick="event.stopPropagation();" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5LbMOzbmcgdGjhu4cgdMOhaSDhuqFuaDwvdGV4dD48L3N2Zz4='; alert('Không thể tải ảnh. Vui lòng thử lại.');">
                            </div>
                            <div style="text-align: center; color: #f1f1f1; padding: 15px; font-size: 14px; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.7); border-radius: 20px; padding: 10px 20px; z-index: 100000;">
                                💡 Nhấn ESC hoặc click bên ngoài ảnh để đóng
                            </div>
                        </div>
                        
                        <style>
                            @keyframes fadeIn {
                                from { opacity: 0; }
                                to { opacity: 1; }
                            }
                            
                            #imageModal {
                                animation: fadeIn 0.3s ease-in-out;
                            }
                            
                            #modalImage {
                                animation: zoomIn 0.3s ease-in-out;
                            }
                            
                            @keyframes zoomIn {
                                from { transform: scale(0.8); opacity: 0; }
                                to { transform: scale(1); opacity: 1; }
                            }
                        </style>
                    </div>
                    <div class="form-group half-width">
                        <label for="ngay_sinh">Ngày sinh</label>
                        <div class="input-with-icon">
                            <input type="date" id="ngay_sinh" name="ngay_sinh" value="{{ $user->ngay_sinh ? $user->ngay_sinh->format('Y-m-d') : '' }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            <span class="input-icon">📅</span>
                        </div>
                    </div>
                    <div class="form-group half-width">
                        <label for="gioi_tinh">Giới tính</label>
                        <div class="input-with-icon">
                            <select id="gioi_tinh" name="gioi_tinh">
                                <option value="">Chọn giới tính</option>
                                <option value="Nam" {{ $user->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nu" {{ $user->gioi_tinh == 'Nu' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khac" {{ $user->gioi_tinh == 'Khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            <span class="input-icon arrow-down">▼</span>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="address">Địa chỉ nhận hàng <small style="color: #666;">(Nhập địa chỉ để tự động điền Tỉnh/Thành phố và Quận/Huyện)</small> <span style="color: red;">*</span></label>
                        <div class="input-with-icon">
                            <input type="text" id="address" name="address" placeholder="Ví dụ: 123 Nguyễn Văn A, Quận 1, Hồ Chí Minh" value="{{ $user->address ?? '' }}" autocomplete="off" required>
                            <span class="input-icon">🏠</span>
                        </div>
                        <small style="color: #666; display: block; margin-top: 5px;">
                            💡 Gợi ý: Nhập địa chỉ đầy đủ, hệ thống sẽ tự động nhận diện và điền Tỉnh/Thành phố, Quận/Huyện. Hoặc bạn có thể chọn trực tiếp từ danh sách bên dưới.
                        </small>
                    </div>
                    <div class="form-group half-width">
                        <label for="province">Tỉnh/Thành phố <span style="color: red;">*</span></label>
                        <div class="input-with-icon">
                            <select id="province" name="province" required>
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                            </select>
                            <span class="input-icon arrow-down">▼</span>
                        </div>
                    </div>
                    <div class="form-group half-width">
                        <label for="district">Quận/Huyện <span style="color: red;">*</span></label>
                        <div class="input-with-icon">
                            <select id="district" name="district" required>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                            <span class="input-icon arrow-down">▼</span>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-update">Cập nhật</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    @include('components.footer')
    
    <!-- Load dữ liệu địa chỉ Việt Nam -->
    <script src="{{ asset('js/vietnam-address.js') }}"></script>
    
    <script>
        // Khởi tạo dropdown Tỉnh/Thành phố
        function initProvinceSelect() {
            const provinceSelect = document.getElementById('province');
            const currentProvince = @json($user->province ?? '');
            
            // Thêm tất cả các tỉnh/thành phố vào dropdown
            for (const province in vietnamAddresses) {
                const option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                if (province === currentProvince) {
                    option.selected = true;
                }
                provinceSelect.appendChild(option);
            }
            
            // Lắng nghe sự kiện thay đổi để cập nhật Quận/Huyện
            provinceSelect.addEventListener('change', function() {
                updateDistrictSelect(this.value);
            });
            
            // Khởi tạo Quận/Huyện nếu đã có Tỉnh/Thành phố
            if (currentProvince) {
                updateDistrictSelect(currentProvince);
            }
        }
        
        // Cập nhật dropdown Quận/Huyện dựa trên Tỉnh/Thành phố đã chọn
        function updateDistrictSelect(province) {
            const districtSelect = document.getElementById('district');
            const currentDistrict = @json($user->district ?? '');
            
            // Xóa tất cả options cũ
            districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
            
            if (province && vietnamAddresses[province]) {
                const districts = vietnamAddresses[province];
                districts.forEach(function(district) {
                    const option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    if (district === currentDistrict) {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });
            }
        }
        
        // Tự động điền địa chỉ khi người dùng nhập
        function initAddressAutoFill() {
            const addressInput = document.getElementById('address');
            let timeout;
            
            addressInput.addEventListener('input', function() {
                clearTimeout(timeout);
                
                // Đợi 500ms sau khi người dùng ngừng gõ
                timeout = setTimeout(function() {
                    const addressText = addressInput.value.trim();
                    if (addressText.length > 5) { // Chỉ xử lý nếu địa chỉ đủ dài
                        autoFillAddress(addressText);
                    }
                }, 500);
            });
            
            // Xử lý khi người dùng paste
            addressInput.addEventListener('paste', function() {
                setTimeout(function() {
                    const addressText = addressInput.value.trim();
                    if (addressText.length > 5) {
                        autoFillAddress(addressText);
                    }
                }, 100);
            });
        }
        
        // Hàm hiển thị lỗi validation
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const formGroup = field.closest('.form-group');
            
            // Xóa lỗi cũ nếu có
            const existingError = formGroup.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Thêm border đỏ cho trường lỗi
            field.style.borderColor = '#dc3545';
            
            // Tạo thông báo lỗi
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.style.color = '#dc3545';
            errorDiv.style.fontSize = '13px';
            errorDiv.style.marginTop = '5px';
            errorDiv.style.display = 'block';
            errorDiv.textContent = '⚠️ ' + message;
            
            // Thêm vào sau input/select
            const inputContainer = formGroup.querySelector('.input-with-icon');
            if (inputContainer) {
                inputContainer.parentNode.insertBefore(errorDiv, inputContainer.nextSibling);
            } else {
                formGroup.appendChild(errorDiv);
            }
        }
        
        // Hàm xóa lỗi validation
        function clearFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            const formGroup = field.closest('.form-group');
            
            // Xóa border đỏ
            field.style.borderColor = '';
            
            // Xóa thông báo lỗi
            const existingError = formGroup.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
        }
        
        // Validate địa chỉ
        function validateAddress(address) {
            const addressTrimmed = address.trim();
            
            if (!addressTrimmed) {
                return {
                    valid: false,
                    message: 'Vui lòng nhập địa chỉ nhận hàng'
                };
            }
            
            if (addressTrimmed.length < 10) {
                return {
                    valid: false,
                    message: 'Địa chỉ phải có ít nhất 10 ký tự (ví dụ: 123 Nguyễn Văn A, Quận 1)'
                };
            }
            
            // Kiểm tra địa chỉ có chứa số nhà hoặc tên đường không
            const hasNumber = /\d/.test(addressTrimmed);
            const hasStreetName = /(đường|phố|ngõ|ngách|hẻm|thôn|xóm|tổ|khu|phường|xã)/i.test(addressTrimmed);
            
            if (!hasNumber && !hasStreetName) {
                return {
                    valid: false,
                    message: 'Địa chỉ phải chứa số nhà hoặc tên đường (ví dụ: 123 Nguyễn Văn A)'
                };
            }
            
            return { valid: true };
        }
        
        // Validate Tỉnh/Thành phố
        function validateProvince(province) {
            if (!province || province.trim() === '') {
                return {
                    valid: false,
                    message: 'Vui lòng chọn Tỉnh/Thành phố'
                };
            }
            return { valid: true };
        }
        
        // Validate Quận/Huyện
        function validateDistrict(district) {
            if (!district || district.trim() === '') {
                return {
                    valid: false,
                    message: 'Vui lòng chọn Quận/Huyện'
                };
            }
            return { valid: true };
        }
        
        // Real-time validation khi người dùng rời khỏi trường
        function initRealTimeValidation() {
            const addressInput = document.getElementById('address');
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            
            // Validate địa chỉ khi blur
            addressInput.addEventListener('blur', function() {
                const address = this.value;
                const validation = validateAddress(address);
                
                if (!validation.valid) {
                    showFieldError('address', validation.message);
                } else {
                    clearFieldError('address');
                }
            });
            
            // Validate Tỉnh/Thành phố khi thay đổi
            provinceSelect.addEventListener('change', function() {
                const province = this.value;
                const validation = validateProvince(province);
                
                if (!validation.valid) {
                    showFieldError('province', validation.message);
                } else {
                    clearFieldError('province');
                    // Reset Quận/Huyện nếu Tỉnh/Thành phố thay đổi
                    if (districtSelect.value) {
                        const districtValidation = validateDistrict(districtSelect.value);
                        if (!districtValidation.valid) {
                            showFieldError('district', districtValidation.message);
                        }
                    }
                }
            });
            
            // Validate Quận/Huyện khi thay đổi
            districtSelect.addEventListener('change', function() {
                const district = this.value;
                const validation = validateDistrict(district);
                
                if (!validation.valid) {
                    showFieldError('district', validation.message);
                } else {
                    clearFieldError('district');
                }
            });
            
            // Xóa lỗi khi người dùng bắt đầu nhập lại
            addressInput.addEventListener('input', function() {
                if (this.value.trim().length >= 10) {
                    clearFieldError('address');
                }
            });
        }
        
        // Validate toàn bộ form trước khi submit
        function validateForm(event) {
            event.preventDefault();
            
            // Xóa tất cả lỗi cũ
            document.querySelectorAll('.field-error').forEach(error => error.remove());
            document.querySelectorAll('input, select').forEach(field => {
                field.style.borderColor = '';
            });
            
            let isValid = true;
            
            // Validate số CCCD
            const cccdInput = document.getElementById('so_cccd');
            if (cccdInput) {
                const cccdValidation = validateCccd(cccdInput.value);
                if (!cccdValidation.valid) {
                    showFieldError('so_cccd', cccdValidation.message);
                    isValid = false;
                }
            }
            
            // Validate ảnh CCCD
            const cccdImageValidation = validateCccdImage();
            if (!cccdImageValidation.valid) {
                const cccdImageInput = document.getElementById('cccd_image');
                if (cccdImageInput) {
                    cccdImageInput.style.borderColor = '#dc3545';
                }
                const formGroup = document.querySelector('label[for="cccd_image"]')?.closest('.form-group');
                if (formGroup) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'field-error';
                    errorDiv.style.color = '#dc3545';
                    errorDiv.style.fontSize = '13px';
                    errorDiv.style.marginTop = '5px';
                    errorDiv.textContent = '⚠️ ' + cccdImageValidation.message;
                    formGroup.appendChild(errorDiv);
                }
                isValid = false;
            }
            
            // Validate địa chỉ
            const addressInput = document.getElementById('address');
            const addressValidation = validateAddress(addressInput.value);
            if (!addressValidation.valid) {
                showFieldError('address', addressValidation.message);
                isValid = false;
            }
            
            // Validate Tỉnh/Thành phố
            const provinceSelect = document.getElementById('province');
            const provinceValidation = validateProvince(provinceSelect.value);
            if (!provinceValidation.valid) {
                showFieldError('province', provinceValidation.message);
                isValid = false;
            }
            
            // Validate Quận/Huyện
            const districtSelect = document.getElementById('district');
            const districtValidation = validateDistrict(districtSelect.value);
            if (!districtValidation.valid) {
                showFieldError('district', districtValidation.message);
                isValid = false;
            }
            
            // Nếu có lỗi, cuộn đến trường đầu tiên có lỗi
            if (!isValid) {
                const firstError = document.querySelector('.field-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            // Nếu hợp lệ, submit form
            event.target.submit();
        }
        
        // Xử lý preview ảnh CCCD
        function initCccdImagePreview() {
            const cccdImageInput = document.getElementById('cccd_image');
            const cccdPreview = document.getElementById('cccd_image_preview');
            const cccdPreviewImg = document.getElementById('cccd_preview_img');
            const cccdFileName = document.getElementById('cccd_file_name');
            const currentImage = document.getElementById('current_cccd_image');
            
            if (!cccdImageInput) return;
            
            cccdImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                if (!file) {
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('⚠️ Định dạng file không hợp lệ. Vui lòng chọn ảnh JPG, PNG hoặc WEBP.');
                    this.value = '';
                    return;
                }
                
                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('⚠️ Kích thước ảnh vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn.');
                    this.value = '';
                    return;
                }
                
                // Hiển thị tên file
                cccdFileName.textContent = '📄 ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                cccdFileName.style.color = '#28a745';
                
                // Ẩn ảnh hiện tại nếu có
                if (currentImage) {
                    currentImage.style.display = 'none';
                }
                
                // Hiển thị preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    cccdPreviewImg.src = e.target.result;
                    cccdPreviewImg.onclick = function() {
                        openImageModal(e.target.result);
                    };
                    cccdPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        }
        
        // Xóa ảnh đã chọn
        function removeCccdImage() {
            const cccdImageInput = document.getElementById('cccd_image');
            const cccdPreview = document.getElementById('cccd_image_preview');
            const cccdFileName = document.getElementById('cccd_file_name');
            const currentImage = document.getElementById('current_cccd_image');
            
            if (cccdImageInput) {
                cccdImageInput.value = '';
            }
            if (cccdPreview) {
                cccdPreview.style.display = 'none';
            }
            if (cccdFileName) {
                cccdFileName.textContent = '';
            }
            if (currentImage) {
                currentImage.style.display = 'block';
            }
        }
        
        // Mở modal xem ảnh phóng to
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            
            if (!modal || !modalImg) {
                console.error('Modal elements not found');
                return;
            }
            
            // Kiểm tra xem ảnh có tồn tại không
            if (!imageSrc || imageSrc.trim() === '') {
                alert('⚠️ Không có đường dẫn ảnh. Vui lòng upload lại ảnh.');
                return;
            }
            
            // Thêm timestamp để tránh cache
            const srcWithTimestamp = imageSrc + (imageSrc.includes('?') ? '&' : '?') + 't=' + new Date().getTime();
            
            // Hiển thị loading
            modalImg.style.opacity = '0.5';
            
            // Set src và hiển thị modal
            modalImg.src = srcWithTimestamp;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Ngăn scroll khi mở modal
            
            // Khi ảnh load xong
            modalImg.onload = function() {
                modalImg.style.opacity = '1';
            };
            
            // Xử lý lỗi khi load ảnh
            modalImg.onerror = function() {
                alert('⚠️ Không thể tải ảnh. Vui lòng kiểm tra lại đường dẫn hoặc upload lại ảnh.');
                closeImageModal();
            };
        }
        
        // Đóng modal xem ảnh
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Cho phép scroll lại
            }
        }
        
        // Đóng modal khi nhấn ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
        
        // Xử lý lỗi khi tải ảnh
        function handleImageError(img) {
            img.style.display = 'none';
            const errorDiv = document.getElementById('image_error');
            if (errorDiv) {
                errorDiv.style.display = 'block';
            }
        }
        
        // Kiểm tra và reload ảnh sau khi upload thành công
        @if(session('success') && $user->cccd_image)
        document.addEventListener('DOMContentLoaded', function() {
            // Reload ảnh sau khi upload thành công
            const currentImg = document.getElementById('current_cccd_img');
            if (currentImg) {
                const newSrc = currentImg.src.split('?')[0] + '?t=' + new Date().getTime();
                currentImg.src = newSrc;
            }
        });
        @endif
        
        // Validate ảnh CCCD trong form validation
        function validateCccdImage() {
            const cccdImageInput = document.getElementById('cccd_image');
            const hasCurrentImage = @json($user->cccd_image ?? null) !== null;
            
            if (!cccdImageInput) return { valid: true };
            
            // Nếu chưa có ảnh và không chọn ảnh mới
            if (!hasCurrentImage && (!cccdImageInput.files || cccdImageInput.files.length === 0)) {
                return {
                    valid: false,
                    message: 'Vui lòng upload ảnh CCCD/CMND'
                };
            }
            
            // Nếu có chọn ảnh, kiểm tra định dạng và kích thước
            if (cccdImageInput.files && cccdImageInput.files.length > 0) {
                const file = cccdImageInput.files[0];
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                
                if (!allowedTypes.includes(file.type)) {
                    return {
                        valid: false,
                        message: 'Định dạng ảnh không hợp lệ. Chỉ chấp nhận JPG, PNG, WEBP'
                    };
                }
                
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    return {
                        valid: false,
                        message: 'Kích thước ảnh vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn'
                    };
                }
            }
            
            return { valid: true };
        }
        
        // Validate số CCCD
        function validateCccd(cccd) {
            const cccdTrimmed = cccd.trim();
            
            if (!cccdTrimmed) {
                return {
                    valid: false,
                    message: 'Vui lòng nhập số CCCD/CMND'
                };
            }
            
            if (cccdTrimmed.length < 9 || cccdTrimmed.length > 12) {
                return {
                    valid: false,
                    message: 'Số CCCD/CMND phải có từ 9 đến 12 ký tự'
                };
            }
            
            if (!/^\d+$/.test(cccdTrimmed)) {
                return {
                    valid: false,
                    message: 'Số CCCD/CMND chỉ được chứa số'
                };
            }
            
            return { valid: true };
        }
        
        // Khởi tạo khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            initProvinceSelect();
            initAddressAutoFill();
            initRealTimeValidation();
            initCccdImagePreview();
            
            // Validate số CCCD khi blur
            const cccdInput = document.getElementById('so_cccd');
            if (cccdInput) {
                cccdInput.addEventListener('blur', function() {
                    const validation = validateCccd(this.value);
                    if (!validation.valid) {
                        showFieldError('so_cccd', validation.message);
                    } else {
                        clearFieldError('so_cccd');
                    }
                });
            }
            
            // Gắn validation vào form submit
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', validateForm);
            }
        });
    </script>
</body>
</html>

