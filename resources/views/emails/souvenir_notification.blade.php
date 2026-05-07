<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <p>Dear {{ $user->name ?? $user->name_en ?? $user->name_zh ?? $user->email }},</p>

    <p>
        You are receiving this notification from the Souvenir system. Please click the registration link below to continue your Souvenir registration.
    </p>

    <p>
        您收到此 Souvenir 系統通知。請點擊以下註冊連結繼續您的 Souvenir 註冊。
    </p>
    <p>
        <a href="{{ $registrationUrl }}" style="display:inline-block;padding:12px 20px;background-color:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;">Register Account/ 註冊帳號</a>
    </p>

    <p>
        <a href="{{ $registrationUrl }}" target="_blank" style="color:#1f8ceb; text-decoration:none;">{{ $registrationUrl }}</a>
    </p>

    <p>
        If the link does not open automatically, copy and paste it into your browser.
    </p>

    <p>
        如果連結無法自動打開，請將其複製並粘貼到瀏覽器中。
    </p>

    <p>
        If you have any questions, please contact the MPU Souvenir support team.
    </p>

    <p>
        如果您有任何疑問，請聯繫澳門理工大學 Souvenir 支援團隊。
    </p>

    <p>Best regards,<br>
        MPU Souvenir Team / 澳門理工大學 Souvenir 團隊</p>
</div>
