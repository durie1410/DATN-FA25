<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNPay Debug - Kiểm tra cấu hình</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .config-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .config-section h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .config-item:last-child {
            border-bottom: none;
        }
        
        .config-label {
            font-weight: 600;
            color: #555;
            flex: 0 0 200px;
        }
        
        .config-value {
            flex: 1;
            text-align: right;
            color: #333;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-ok {
            background: #d4edda;
            color: #155724;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .alert {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        
        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }
        
        .alert h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .steps {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .steps ol {
            margin-left: 20px;
        }
        
        .steps li {
            margin: 8px 0;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 VNPay Debug</h1>
        <p class="subtitle">Kiểm tra cấu hình thanh toán VNPay</p>
        
        @php
            $config = config('services.vnpay');
            $tmnCode = $config['tmn_code'] ?? '';
            $hashSecret = $config['hash_secret'] ?? '';
            $url = $config['url'] ?? '';
            $returnUrl = $config['return_url'] ?? '';
            
            $isConfigured = !empty($tmnCode) && !empty($hashSecret);
            $expectedTmnCode = 'E6I8Z7HX';
            $expectedHashSecret = 'LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ';
            
            $tmnCodeCorrect = $tmnCode === $expectedTmnCode;
            $hashSecretCorrect = $hashSecret === $expectedHashSecret;
        @endphp
        
        @if($isConfigured && $tmnCodeCorrect && $hashSecretCorrect)
            <div class="alert alert-success">
                <h3>✅ Cấu hình VNPay hoàn hảo!</h3>
                <p>Tất cả các thông tin đã được cấu hình đúng. Bạn có thể thử thanh toán ngay.</p>
            </div>
        @elseif($isConfigured)
            <div class="alert alert-warning">
                <h3>⚠️ Cấu hình có vấn đề</h3>
                <p>Thông tin đã được cấu hình nhưng có thể không khớp với sandbox VNPay.</p>
            </div>
        @else
            <div class="alert alert-error">
                <h3>❌ Chưa cấu hình VNPay</h3>
                <p>Vui lòng cấu hình thông tin VNPay trong file .env</p>
                <div class="steps">
                    <strong>Cách sửa nhanh:</strong>
                    <ol>
                        <li>Chạy file: <code>fix_vnpay_now.bat</code> ở thư mục gốc project</li>
                        <li>Hoặc chạy lệnh: <code>php check_and_fix_vnpay.php</code></li>
                        <li>Sau đó: <code>php artisan config:clear</code></li>
                        <li>Refresh lại trang này</li>
                    </ol>
                </div>
            </div>
        @endif
        
        <div class="config-section">
            <h2>📋 Cấu hình hiện tại</h2>
            
            <div class="config-item">
                <span class="config-label">TMN Code:</span>
                <span class="config-value">
                    @if(empty($tmnCode))
                        <span class="status-badge status-error">Chưa cấu hình</span>
                    @elseif($tmnCodeCorrect)
                        <span class="status-badge status-ok">{{ $tmnCode }}</span>
                    @else
                        <span class="status-badge status-warning">{{ $tmnCode }} (Không khớp)</span>
                    @endif
                </span>
            </div>
            
            <div class="config-item">
                <span class="config-label">Hash Secret:</span>
                <span class="config-value">
                    @if(empty($hashSecret))
                        <span class="status-badge status-error">Chưa cấu hình</span>
                    @elseif($hashSecretCorrect)
                        <span class="status-badge status-ok">✓ Đã cấu hình đúng ({{ strlen($hashSecret) }} ký tự)</span>
                    @else
                        <span class="status-badge status-warning">Đã cấu hình ({{ strlen($hashSecret) }} ký tự) nhưng không khớp</span>
                    @endif
                </span>
            </div>
            
            <div class="config-item">
                <span class="config-label">VNPay URL:</span>
                <span class="config-value">{{ $url ?: 'Chưa cấu hình' }}</span>
            </div>
            
            <div class="config-item">
                <span class="config-label">Return URL:</span>
                <span class="config-value">{{ $returnUrl ?: 'Chưa cấu hình' }}</span>
            </div>
            
            <div class="config-item">
                <span class="config-label">Version:</span>
                <span class="config-value">{{ $config['version'] ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="config-section">
            <h2>🎯 Giá trị mong đợi (VNPay Sandbox)</h2>
            
            <div class="config-item">
                <span class="config-label">TMN Code:</span>
                <span class="config-value">{{ $expectedTmnCode }}</span>
            </div>
            
            <div class="config-item">
                <span class="config-label">Hash Secret:</span>
                <span class="config-value">{{ substr($expectedHashSecret, 0, 10) }}...{{ substr($expectedHashSecret, -5) }}</span>
            </div>
            
            <div class="config-item">
                <span class="config-label">Môi trường:</span>
                <span class="config-value">Sandbox (Test)</span>
            </div>
        </div>
        
        @if(!$isConfigured || !$tmnCodeCorrect || !$hashSecretCorrect)
        <div class="config-section">
            <h2>🔧 Cách sửa lỗi</h2>
            <div class="steps">
                <p><strong>Chạy lệnh sau trong PowerShell hoặc CMD:</strong></p>
                <ol>
                    <li>Mở PowerShell/CMD tại thư mục project</li>
                    <li>Chạy: <code>fix_vnpay_now.bat</code></li>
                    <li>Hoặc thủ công: Sửa file <code>.env</code> và thêm/cập nhật:
                        <br><code>VNPAY_TMN_CODE={{ $expectedTmnCode }}</code>
                        <br><code>VNPAY_HASH_SECRET={{ $expectedHashSecret }}</code>
                    </li>
                    <li>Sau đó chạy: <code>php artisan config:clear</code></li>
                    <li>Refresh lại trang này để kiểm tra</li>
                </ol>
            </div>
        </div>
        @endif
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('home') }}" class="btn">← Về trang chủ</a>
            <a href="javascript:location.reload()" class="btn" style="margin-left: 10px;">🔄 Refresh</a>
        </div>
    </div>
</body>
</html>

