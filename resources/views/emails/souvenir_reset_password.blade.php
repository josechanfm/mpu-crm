<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Souvenir Password Reset</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; color:#333;">
    <h2>【密碼重置】購買“澳理大小熊”｜【Reset Password】MPU Bear Order</h2>
    <p>Dear {{ $user->email }},</p>
    <p>
        閣下帳戶的密碼重置請求已收到，請隨以下步驟操作。<br>
        You password reset request has been received. Please click the link below to reset the password.<br>
        倘非本人操作，則無需理會。<br>
        If you did not request a password reset, no further action is required.<br>
    </p>
    <p>
       點擊以下按鈕密碼重置。<br>
       Click the button below to reset your password. 
    </p>
    <p>
        <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 20px;background-color:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;">Reset Password / 重置密碼</a>
    </p>
    <p>
        此重置連結將於 15分鐘後失效。請勿回覆此郵件。<br>
        This reset link will expire in 15 minutes. Please do not reply to this email.
    </p>
    <p>
        澳門理工大學<br>
        學生事務處　謹啟<br>
        Student Affairs Office<br>
        Macao Polytechnic University<br>
    </p>
</body>
</html>
