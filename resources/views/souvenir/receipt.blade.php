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
            src: url('{{ public_path('fonts/Noto/NotoSansTC-Regular.ttf') }}') format('truetype');
        }

        body {
            margin: 30px;
            font-family: 'NotoSansTC', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
        }
        .theader {
            background-color: #3498db;
            color: white;
            font-size: 14px;
            text-align:center;
        }
        .tfooter{
            background-color: #ecf0f1;
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 12px;
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
        <div>Receipt No. / 收據編號: {{ $order->updated_at->format('y') }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div>Faculty/學院: {{ strtoupper($order->form_meta->faculty)  }}</div>
        <div>Degree/學位: {{ ucfirst($order->form_meta->degree)  }}</div>
        <div>Phone/電話: {{ $order->form_meta->phone }}</div>
        <div>Email/電郵: {{ $order->form_meta->email  }}</div>
        <div>Payment Date / 支付日期: {{ $order->created_at->format('Y-m-d') }}</div>
        <div>Payment Method/支付方式: GOVPAY online (BOC) / 線上政付通(BOC)</div>
        <p></p>
    </div>
    <table>
        <thead>
            <tr>
                <td class="theader">Product / 產品</td>
                <td class="theader" width="120px">Quantity / 數量</td>
                <td class="theader" width="120px">Unit Price / 單價</td>
                <td class="theader" width="120px">Amount / 金額</td>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="text-align:left;">{{ $item['name'] }}</td>
                <td style="text-align:center;">{{ $item['qty'] }}</td>
                <td style="text-align:center;">{{ $item['price'] }}</td>
                <td style="text-align:center">{{ $item['amount'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="tfooter">Total / 總計:</td>
                <td style="text-align:center">{{ $order->amount }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <table width="100%" style="border: none; border-collapse: collapse; margin: 0; padding: 0;">
            <tr>
                <td style="text-align: left; border: none; padding: 0;">Thank you for your purchase! <br> 感謝您的購買！</td>
                <td style="text-align: right; border: none; padding: 0;">For any inquiries, contact us at sao@mpu.edu.mo <br> 如有任何查詢，請聯繫我們 sao@mpu.edu.mo</td>
            </tr>
        </table>
    </div>
</body>
</html>