<!doctype html>
<html lang="en">
<head>
    <title>Macao Polytechnic University</title>
</head>
<style>
    @font-face {
        font-family: 'SimHei';
        src: url('fonts/simhei.ttf') format('truetype');
    }
    *{
        font-family: 'SimHei' ;
     }

</style>

<body style="margin: 0; padding: 20px;">
    <div>
        <img src="{{ public_path('images\mpu_banner.png') }}" alt="MPU Logo" style="display: block; margin: 0 auto 20px; height: 80px;" />
    </div>
    <p>{{ storage_path('app\public\fonts\simhei.ttf') }}</p>
    <p>{{ public_path('images\mpu_banner.png') }}</p>
    <p>{{  url('/storage/fonts/simhei.ttf') }}</p>
    <div class="title" style="text-align: center; color: #2c3e50;">Official Receipt / 官方收據</div>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr>
                <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white;">產品 / Product</th>
                <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white;">數量 / Quantity</th>
                <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white;">單價 / Unit Price</th>
                <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white;">金額 / Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">{{ $item['name'] }}</td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">{{ $item['qty'] }}</td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">{{ $item['price'] }}</td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">{{ $item['amount'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold; background-color: #ecf0f1; border: 1px solid #ccc; padding: 10px;">總計 / Total:</td>
                <td style="border: 1px solid #ccc; padding: 10px;">{{ $order->amount }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer" style="text-align: center; margin-top: 20px; font-size: 14px; color: #7f8c8d;">
        <p>Thank you for your purchase! / 感謝您的購買！</p>
        <p>For any inquiries, contact us at support@mpu.edu.mo / 如有任何查詢，請聯繫我們 support@mpu.edu.mo</p>
    </div>
</body>
</html>