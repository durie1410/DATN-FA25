<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }
        .header .subtitle {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .main-content {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .feature-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 5px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .cta-section {
            text-align: center;
            margin: 35px 0;
            padding: 25px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .book-showcase {
            display: flex;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .book-item {
            flex: 1;
            min-width: 200px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .book-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .book-author {
            color: #7f8c8d;
            font-size: 14px;
        }
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: 30px 0;
            border-radius: 10px;
            text-align: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer-content {
            margin-bottom: 20px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }
        .unsubscribe {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #34495e;
            font-size: 12px;
            opacity: 0.8;
        }
        .unsubscribe a {
            color: #3498db;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header, .content, .footer {
                padding: 25px 20px;
            }
            .book-showcase {
                flex-direction: column;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ config('app.name', 'Thư viện') }}</h1>
            <p class="subtitle">Khám phá thế giới tri thức</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                @if(isset($data['reader_name']))
                    Xin chào <strong>{{ $data['reader_name'] }}</strong>,
                @else
                    Xin chào bạn,
                @endif
            </div>
            
            <div class="main-content">
                {!! nl2br(e($content)) !!}
            </div>
            
            @if(isset($data['featured_books']) && count($data['featured_books']) > 0)
                <div class="feature-box">
                    <h3 style="margin-top: 0; color: #2c3e50;">📚 Sách nổi bật tuần này</h3>
                    <div class="book-showcase">
                        @foreach($data['featured_books'] as $book)
                            <div class="book-item">
                                <div class="book-title">{{ $book['title'] }}</div>
                                <div class="book-author">{{ $book['author'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(isset($data['stats']))
                <div class="stats-section">
                    <h3 style="margin-top: 0;">📊 Thống kê thư viện</h3>
                    <div class="stats-grid">
                        @foreach($data['stats'] as $stat)
                            <div class="stat-item">
                                <div class="stat-number">{{ $stat['number'] }}</div>
                                <div class="stat-label">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(isset($data['action_url']) && isset($data['action_text']))
                <div class="cta-section">
                    <a href="{{ $data['action_url'] }}" class="cta-button">{{ $data['action_text'] }}</a>
                </div>
            @endif
            
            @if(isset($data['additional_info']))
                <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
                    {!! $data['additional_info'] !!}
                </div>
            @endif
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <p><strong>{{ config('app.name', 'Thư viện') }}</strong></p>
                <p>📧 Email: library@example.com | 📞 Hotline: 1900-xxxx</p>
                <p>🏢 Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
            </div>
            
            <div class="social-links">
                <a href="#">📘 Facebook</a>
                <a href="#">🌐 Website</a>
                <a href="#">📱 App</a>
                <a href="#">📧 Email</a>
            </div>
            
            <div class="unsubscribe">
                <p>Email này được gửi tự động từ hệ thống quản lý thư viện</p>
                <p>Nếu bạn không muốn nhận email này, vui lòng <a href="#">hủy đăng ký</a></p>
            </div>
        </div>
    </div>
</body>
</html>























