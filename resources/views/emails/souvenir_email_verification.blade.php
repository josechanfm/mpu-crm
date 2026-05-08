<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification for Souvenir Registration</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Your verification code is: <strong>{{ $code }}</strong></p>
    <p>Please enter this code in the registration form to complete your registration.</p>
    <p>If you did not request this, please ignore this email.</p>
    <p>Best regards,<br>MPU Souvenir Team</p>
</body>
</html>