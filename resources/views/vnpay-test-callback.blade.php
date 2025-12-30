<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test VNPay Callback</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #252526;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }
        h1 {
            color: #4ec9b0;
            font-size: 28px;
            margin-bottom: 20px;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #1e1e1e;
            border-radius: 6px;
            border-left: 4px solid #569cd6;
        }
        .section h2 {
            color: #569cd6;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .key-value {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 10px;
            margin: 5px 0;
            padding: 5px 0;
            border-bottom: 1px solid #333;
        }
        .key {
            color: #9cdcfe;
            font-weight: bold;
        }
        .value {
            color: #ce9178;
            word-break: break-all;
        }
        .status {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .status.success {
            background: #1e3a1e;
            border: 2px solid #4caf50;
            color: #4caf50;
        }
        .status.error {
            background: #3a1e1e;
            border: 2px solid #f44336;
            color: #f44336;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge.ok { background: #4caf50; color: white; }
        .badge.fail { background: #f44336; color: white; }
        .badge.warn { background: #ff9800; color: white; }
        .test-btn {
            background: #007acc;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 10px 5px;
            transition: all 0.3s;
        }
        .test-btn:hover {
            background: #005a9e;
            transform: translateY(-2px);
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            color: #d4d4d4;
            border: 1px solid #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 VNPay Callback Test & Debug</h1>

        <div class="section">
            <h2>📋 Current Configuration</h2>
            @php
                $config = config('services.vnpay');
                $tmnCode = $config['tmn_code'] ?? '';
                $hashSecret = $config['hash_secret'] ?? '';
                $tmnOk = $tmnCode === 'E6I8Z7HX';
                $hashOk = $hashSecret === 'LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ';
            @endphp
            
            <div class="key-value">
                <div class="key">TMN Code:</div>
                <div class="value">
                    {{ $tmnCode ?: '(empty)' }}
                    @if($tmnOk)
                        <span class="badge ok">✓ CORRECT</span>
                    @else
                        <span class="badge fail">✗ WRONG</span>
                    @endif
                </div>
            </div>
            
            <div class="key-value">
                <div class="key">Hash Secret Length:</div>
                <div class="value">
                    {{ strlen($hashSecret) }} chars
                    @if($hashOk)
                        <span class="badge ok">✓ CORRECT</span>
                    @else
                        <span class="badge fail">✗ WRONG</span>
                    @endif
                </div>
            </div>
            
            <div class="key-value">
                <div class="key">Hash Secret Preview:</div>
                <div class="value">{{ substr($hashSecret, 0, 10) }}...{{ substr($hashSecret, -5) }}</div>
            </div>
            
            <div class="key-value">
                <div class="key">VNPay URL:</div>
                <div class="value">{{ $config['url'] ?? '(empty)' }}</div>
            </div>
            
            <div class="key-value">
                <div class="key">Return URL:</div>
                <div class="value">{{ $config['return_url'] ?? '(empty)' }}</div>
            </div>
        </div>

        @if($tmnOk && $hashOk)
            <div class="status success">
                ✅ Configuration is CORRECT! Signature verification should work.
            </div>
        @else
            <div class="status error">
                ❌ Configuration is WRONG! This will cause signature verification to fail.
                <br><br>
                <a href="{{ route('vnpay.fix') }}" style="color: #ff9800; text-decoration: underline;">
                    → Click here to fix automatically
                </a>
            </div>
        @endif

        <div class="section">
            <h2>🔬 Test Actions</h2>
            <button class="test-btn" onclick="simulateCallback()">
                🧪 Simulate VNPay Callback
            </button>
            <button class="test-btn" onclick="clearSession()">
                🗑️ Clear Session
            </button>
            <button class="test-btn" onclick="location.href='{{ route('vnpay.fix') }}'">
                🔧 Fix Config
            </button>
            <button class="test-btn" onclick="location.reload()">
                🔄 Refresh
            </button>
        </div>

        <div class="section" id="result" style="display: none;">
            <h2>📤 Test Result</h2>
            <pre id="result-content"></pre>
        </div>

        <div class="section">
            <h2>💡 Troubleshooting</h2>
            <div style="line-height: 1.8;">
                <strong style="color: #4ec9b0;">If signature verification still fails:</strong><br>
                1. <strong>Clear ALL cache:</strong> Config, route, view, application<br>
                2. <strong>Restart web server:</strong> Stop and start Laragon/Apache/Nginx<br>
                3. <strong>Clear browser cache:</strong> Ctrl+Shift+R (hard refresh)<br>
                4. <strong>Clear session:</strong> Click "Clear Session" button above<br>
                5. <strong>Check .env file directly:</strong> Make sure no extra spaces<br>
                6. <strong>Rebuild config:</strong> php artisan config:cache<br>
            </div>
        </div>
    </div>

    <script>
        async function simulateCallback() {
            const result = document.getElementById('result');
            const content = document.getElementById('result-content');
            
            result.style.display = 'block';
            content.textContent = 'Testing...';
            
            try {
                const response = await fetch('/vnpay-test-signature', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                content.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                content.textContent = 'Error: ' + error.message;
            }
        }

        async function clearSession() {
            const result = document.getElementById('result');
            const content = document.getElementById('result-content');
            
            result.style.display = 'block';
            content.textContent = 'Clearing session...';
            
            try {
                const response = await fetch('/vnpay-clear-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                content.textContent = JSON.stringify(data, null, 2);
                
                if (data.success) {
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (error) {
                content.textContent = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>

