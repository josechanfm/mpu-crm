<!doctype html>
<html lang="en">
<head>
    <title>Macao Polytechnic University</title>
    <style type="text/css">
        @font-face {
            font-family: SimHei;
            src: url('/public/fonts/simhei.ttf') format('truetype');
        }

        body {
            font-family: SimHei, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            color: white;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        img {
            display: block;
            margin: 0 auto 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
            font-family: Arial, sans-serif;
        }

        tfoot td {
            font-weight: bold;
            background-color: #ecf0f1;
        }

        .qrcode {
            text-align: center;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .bilingual {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .bilingual div {
            width: 48%; /* Allow space for both languages */
        }
    </style>
</head>

<body>
    <div class="container">
        <div>
            <img src="{{ asset('/images/mpu_banner.png') }}" alt="MPU Logo" style="height: 80px;" />
        </div>
        <h1>Official Receipt / 官方收據</h1>
        
        <table>
            <thead>
                <tr>
                    <th>產品 / 產品</th>
                    <th>數量 / 數量</th>
                    <th>單價 / 單價</th>
                    <th>金額 / 金額</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                <tr>
                    <td>{{ $item['name'] }}</td> <!-- Assuming you have a Chinese name field -->
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>{{ $item['amount'] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">總計 / Total:</td>
                    <td>{{ $order->amount }}</td>
                </tr>
            </tfoot>
        </table>


        <div class="footer">
            <p>Thank you for your purchase! / 感謝您的購買！</p>
            <p>For any inquiries, contact us at support@mpu.edu.mo / 如有任何查詢，請聯繫我們 support@mpu.edu.mo</p>
        </div>
    </div>
</body>
</html>