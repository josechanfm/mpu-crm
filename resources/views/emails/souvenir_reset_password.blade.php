<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Souvenir Password Reset</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; color:#333;">
    <h2>Souvenir Password Reset / Souvenir 密碼重置</h2>
    <p>Dear {{ $user->name_en ?? $user->name_zh ?? $user->email }},</p>
    <p>
        You are receiving this email because we received a password reset request for your Souvenir account. / 我們收到您 Souvenir 帳戶的密碼重置請求，特此發送此郵件。
    </p>
    <p>
        Click the button below to reset your password: / 點擊以下按鈕重置您的密碼：
    </p>
    <p>
        <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 20px;background-color:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;">Reset Password / 重置密碼</a>
    </p>
    <p>
        If the button does not work, copy and paste the following URL into your browser: / 如果按鈕無法使用，請將以下網址複製並貼到瀏覽器中：
    </p>
    <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
    <p>
        This reset link will expire in 60 minutes. / 此重置連結將於 60 分鐘後失效。
    </p>
    <p>
        If you did not request a password reset, no further action is required. / 如果您未申請重置密碼，則無需採取任何操作。
    </p>
    <p>Thank you, / 謝謝您，<br />MPU Souvenir Team</p>
</body>
</html>
