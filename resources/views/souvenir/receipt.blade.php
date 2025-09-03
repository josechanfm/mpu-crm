<!doctype html>

<head>
    <meta charset="UTF-8">
    <title>Macao Polytechnic University</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        @font-face {
            font-family: 'NotoSansTC';
            font-style: normal;
            font-weight: 400;
            src: url('{{ storage_path('app/public/fonts/Noto/NotoSansTC-Regular.ttf') }}') format('truetype');
        }

        body {
            margin: 30px;
            font-family: 'NotoSansTC', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .theader {
            background-color: #3498db;
            color: white;
        }
        .tfooter{
            background-color: #ecf0f1;
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div>
        <img src="{{ public_path('/storage/images/mpu_banner.png') }}" alt="MPU Logo" style="display: block; margin: 0 auto 20px; height: 80px;" />
    </div>
    <div>
        <div style="font-size:20px; text-align: center; color: #2c3e50;">Official Receipt / 官方收據</div>
        <p></p>

        <div style="float:right">
            <div>Pickup Code / 取件碼</div>
            <img style="float:right" width="150" src="{{ $pickupQrcodePath }}" alt="QR Code" />
        </div>
        <div>Faculty/學院: {{ $order->form_meta->faculty  }}</div>
        <div>Degree/學位: {{ $order->form_meta->degree  }}</div>
        <div>Phone/電話: {{ $order->form_meta->phone }}</div>
        <div>Email/電郵: {{ $order->form_meta->email  }}</div>
        <div>Order ID / 訂單編號: {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div>Order Date / 訂單日期: {{ $order->created_at->format('Y-m-d H:i:s') }}</div>
        <p></p>
        <p></p>
    </div>
    <table>
        <thead>
            <tr>
                <td class="theader">Product / 產品</td>
                <td class="theader">Quantity / 數量</td>
                <td class="theader">Unit Price / 單價</td>
                <td class="theader">Amount / 金額</td>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>{{ $item['price'] }}</td>
                <td>{{ $item['amount'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="tfooter">Total / 總計:</td>
                <td>{{ $order->amount }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for your purchase! / 感謝您的購買！</p>
        <p>For any inquiries, contact us at support@mpu.edu.mo / 如有任何查詢，請聯繫我們 support@mpu.edu.mo</p>
    </div>
</body>
</html>