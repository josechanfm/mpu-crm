<!doctype html>
<html lang="en">
<head>
    <title>Macao Polytechnic University</title>
</head>

<style type="text/css">
    @font-face {
        font-family: SimHei;
        src: {{  public_path('\storage\fonts\simhei.ttf') }}; 
    }
</style>

<body style="font-family: SimHei;  margin: 0; padding: 20px;">
    
        <div>
            <img src="{{ public_path('images\mpu_banner.png') }}" alt="MPU Logo" style="display: block; margin: 0 auto 20px; height: 80px;" />
        </div>
        <h1 style="text-align: center; color: #2c3e50;">Official Receipt / 官方收據</h1>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white; font-family: SimHei; ">產品 / 產品</th>
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white; font-family: SimHei;">數量 / 數量</th>
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white; font-family: SimHei;">單價 / 單價</th>
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #3498db; color: white; font-family: SimHei;">金額 / 金額</th>
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