<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sửa lỗi VNPay - One Click Fix</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 700px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            text-align: center;
        }
        h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .status {
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            font-size: 18px;
        }
        .status.error {
            background: #fee;
            border: 2px solid #f44336;
            color: #c62828;
        }
        .status.success {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            color: #2e7d32;
        }
        .status.info {
            background: #e3f2fd;
            border: 2px solid #2196f3;
            color: #1565c0;
        }
        .fix-button {
            background: linear-gradient(135deg, #f44336, #e91e63);
            color: white;
            border: none;
            padding: 20px 50px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4);
        }
        .fix-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(244, 67, 54, 0.6);
        }
        .fix-button:disabled {
            background: #999;
            cursor: not-allowed;
        }
        .result {
            display: none;
            text-align: left;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 12px;
            margin-top: 20px;
        }
        .result.show { display: block; }
        .result pre {
            background: #263238;
            color: #aed581;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 14px;
        }
        .links {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .links a {
            padding: 12px 24px;
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .links a:hover {
            background: #667eea;
            color: white;
        }
        .loading {
            display: none;
            margin: 20px auto;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .loading.show { display: block; }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Sửa lỗi VNPay</h1>
        
        <div class="status error">
            <strong>⚠️ Vấn đề:</strong> Xác thực chữ ký thất bại<br>
            <strong>💡 Giải pháp:</strong> Click nút bên dưới để sửa tự động!
        </div>

        <button class="fix-button" onclick="fixVnpay()">
            🚀 SỬA NGAY
        </button>

        <div class="loading"></div>

        <div class="result"></div>

        <div class="links">
            <a href="{{ route('vnpay.debug') }}">📊 Kiểm tra config</a>
            <a href="{{ route('home') }}">🏠 Về trang chủ</a>
            <a href="javascript:location.reload()">🔄 Refresh</a>
        </div>
    </div>

    <script>
        async function fixVnpay() {
            const button = document.querySelector('.fix-button');
            const loading = document.querySelector('.loading');
            const result = document.querySelector('.result');
            const status = document.querySelector('.status');

            button.disabled = true;
            button.textContent = 'ĐANG SỬA...';
            loading.classList.add('show');
            result.classList.remove('show');

            try {
                const response = await fetch('/vnpay-fix-execute', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                loading.classList.remove('show');

                if (data.success) {
                    status.className = 'status success';
                    status.innerHTML = `
                        <strong>✅ Thành công!</strong><br>
                        Đã cập nhật cấu hình VNPay và xóa cache.<br>
                        Hãy thử thanh toán lại!
                    `;
                    button.textContent = '✅ ĐÃ SỬA XONG';
                    button.style.background = 'linear-gradient(135deg, #4caf50, #8bc34a)';
                } else {
                    status.className = 'status error';
                    status.innerHTML = `
                        <strong>❌ Lỗi:</strong> ${data.message || 'Không thể sửa tự động'}
                    `;
                    button.disabled = false;
                    button.textContent = '🚀 THỬ LẠI';
                }

                if (data.details) {
                    result.innerHTML = '<h3>Chi tiết:</h3><pre>' + data.details + '</pre>';
                    result.classList.add('show');
                }

                // Auto redirect sau 2 giây nếu thành công
                if (data.success) {
                    setTimeout(() => {
                        window.location.href = '{{ route("vnpay.debug") }}';
                    }, 2000);
                }

            } catch (error) {
                loading.classList.remove('show');
                status.className = 'status error';
                status.innerHTML = `
                    <strong>❌ Lỗi kết nối:</strong><br>
                    ${error.message}
                `;
                button.disabled = false;
                button.textContent = '🚀 THỬ LẠI';
            }
        }
    </script>
</body>
</html>

