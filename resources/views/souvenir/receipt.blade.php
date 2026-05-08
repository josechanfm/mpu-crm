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
        @font-face {
            font-family: 'NotoSansTC';
            font-style: normal;
            font-weight: 700;  /* or 800 if you have that weight */
            src: url('{{ public_path('fonts/Noto/NotoSansTC-Bold.ttf') }}') format('truetype');
        }
        body {
            margin: 30px;
            font-family: 'NotoSansTC', sans-serif;
        }
        header {
            font-family: 'NotoSansTC';
            font-weight:800;
            font-style: bold;
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
    <?php
        $faculties = [
            'FCA' => 'Faculty of Applied Sciences / 應用科學學院',
            'FCSD' => 'Faculty of Health Sciences and Sports / 健康科學及體育學院',
            'FLT' => 'Faculty of Languages and Translation / 語言及翻譯學院',
            'FAD' => 'Faculty of Arts and Design / 藝術及設計學院',
            'FCHS' => 'Faculty of Humanities and Social Sciences / 人文及社會科學學院',
            'FCG' => 'Faculty of Business / 管理科學學院',
            'AE' => 'Peking University Health Science Center – Macao Polytechnic University Nursing Academy / 北京大學醫學部——澳門理工大學護理書院',
        ];
    ?>

    <div>
        <img src="{{ public_path('/storage/images/mpu_banner.png') }}" alt="MPU Logo" style="display: block; margin: 0 auto 20px; height: 80px;" />
    </div>
    <div>
        <div style="font-size:20px; text-align: center; color: #2c3e50;">Receipt / 收據</div>
        <p></p>

        <div style="float:right">
            <div>Pick-up Code / 取件碼</div>
            <img style="float:right" width="150" src="{{ $pickupQrcodePath }}" alt="QR Code" />
        </div>
        <div><span style="font-weight:bold">Número de Recebio / 收據編號:</span> {{ $order->updated_at->format('y') }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div><span style="font-weight:bold">Unidade académica / 學術單位: </span><br><span style="margin-left: 20px;">{{ $faculties[strtoupper($order->user->faculty_code)] }}<span></div>
        <div><span style="font-weight:bold">Grau / 學位:</span> {{ ucfirst($order->user->degree_code)  }}</div>
        <div><span style="font-weight:bold">Email / 電郵:</span> {{ $order->user->email  }}</div>
        <div><span style="font-weight:bold">Telephone / 電話:</span> {{ $order->user->phone }}</div>
        <div><span style="font-weight:bold">Data de Pagamento / 付款日期:</span> {{ $order->created_at->format('Y-m-d') }}</div>
        <div><span style="font-weight:bold">Forma de Pagamento / 付款方式:</span> GOVPAY online (BOC) / 線上政付通(BOC)</div>
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