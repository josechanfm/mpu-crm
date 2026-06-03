<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invitation Letter</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .content {
            padding: 30px;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            padding: 20px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .rsvp-link {
            word-break: break-all;
            background: #f3f4f6;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .placeholder-info {
            background: #fef3c7;
            padding: 10px;
            font-size: 12px;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <img src="file://{{ storage_path('app/public/images/mpu_banner.png') }}" alt="MPU Banner" style="width: 100%; height: auto;">
        </div>

        <div class="content">
            <p>Dear <strong>{{ $guest_name }}</strong>{{ $guest_organization ? ' (' . $guest_organization . ')' : '' }},</p>

            {!! $pdf_body !!}

            <div class="rsvp-link">
                <strong>RSVP Link:</strong><br>
                <a href="{{ $rsvp_link }}">{{ $rsvp_link }}</a>
            </div>

            @if($qr_code_image)
                <div class="qr-code">
                    {!! $qr_code_image !!}
                </div>
            @endif

            @if($is_test)
                <div class="placeholder-info">
                    ⚠️ TEST MODE – This PDF contains demo data.
                </div>
            @endif
        </div>

        <div class="footer">
            &copy; {{ $event_name }} – Official Invitation
        </div>
    </div>
</body>
</html>