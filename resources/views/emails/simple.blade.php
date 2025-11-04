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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 20px;
        }
        .content p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .highlight-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: 500;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header, .content, .footer {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ config('app.name', 'Thư viện') }}</h1>
        </div>
        
        <div class="content">
            <h2>{{ $subject }}</h2>
            
            <div class="highlight-box">
                {!! nl2br(e($content)) !!}
            </div>
            
            @if(isset($data['action_url']) && isset($data['action_text']))
                <div style="text-align: center;">
                    <a href="{{ $data['action_url'] }}" class="button">{{ $data['action_text'] }}</a>
                </div>
            @endif
            
            @if(isset($data['additional_info']))
                <div style="margin-top: 20px;">
                    {!! $data['additional_info'] !!}
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p><strong>{{ config('app.name', 'Thư viện') }}</strong></p>
            <p>Email này được gửi tự động từ hệ thống quản lý thư viện</p>
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Website</a> |
                <a href="#">Liên hệ</a>
            </div>
            <p><small>Nếu bạn không muốn nhận email này, vui lòng <a href="#" style="color: #3498db;">hủy đăng ký</a></small></p>
        </div>
    </div>
</body>
</html>