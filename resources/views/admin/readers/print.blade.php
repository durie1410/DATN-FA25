<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách độc giả</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 18px;
            margin: 10px 0 0 0;
            font-weight: normal;
        }
        
        .info {
            margin-bottom: 20px;
            text-align: right;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-suspended {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-expired {
            color: #dc3545;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Thư viện</h1>
        <h2>Danh sách độc giả</h2>
    </div>

    <div class="info">
        <p><strong>Ngày in:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Tổng số độc giả:</strong> {{ $readers->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th style="width: 12%;">Mã độc giả</th>
                <th style="width: 20%;">Họ tên</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 12%;">Số điện thoại</th>
                <th style="width: 8%;">Giới tính</th>
                <th style="width: 10%;">Ngày sinh</th>
                <th style="width: 8%;">Trạng thái</th>
                <th style="width: 10%;">Ngày hết hạn</th>
            </tr>
        </thead>
        <tbody>
            @foreach($readers as $index => $reader)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $reader->so_the_doc_gia }}</td>
                <td>{{ $reader->ho_ten }}</td>
                <td>{{ $reader->email }}</td>
                <td>{{ $reader->so_dien_thoai }}</td>
                <td class="text-center">{{ $reader->gioi_tinh }}</td>
                <td class="text-center">{{ $reader->ngay_sinh ? $reader->ngay_sinh->format('d/m/Y') : 'N/A' }}</td>
                <td class="text-center">
                    @if($reader->trang_thai == 'Hoat dong')
                        <span class="status-active">Hoạt động</span>
                    @elseif($reader->trang_thai == 'Tam khoa')
                        <span class="status-suspended">Tạm khóa</span>
                    @else
                        <span class="status-expired">Hết hạn</span>
                    @endif
                </td>
                <td class="text-center">{{ $reader->ngay_het_han ? $reader->ngay_het_han->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>--- Hết danh sách ---</p>
        <p>Báo cáo được tạo tự động bởi hệ thống quản lý thư viện</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            In danh sách
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Đóng
        </button>
    </div>
</body>
</html>

