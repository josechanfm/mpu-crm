<!DOCTYPE html>
<html>
<head>
    <title>Macao Polytechnic University</title>
</head>
<body>
    <p>您好！</p>
    <p></p>
    <p>謝謝來郵，現回覆您的查詢如下：</p>
    <p>{!! $body !!}</p>
    <p></p>
    <p>如 尚有查詢，請點擊連結：<a href="{{url('/enquiry/ticket/'.$id.'/'.$token)}}">{{url('/enquiry/ticket/'.$id.'/'.$token)}}</a></p>
    <p></p>
    <p>順頌 台安</p>
    <p></p>
    <p>澳門理工大學<br>
    招生註冊處</p>
    <p>&nbsp</p>
    <p>&nbsp</p>
    <p>&nbsp</p>
    <p>Dear sir or madam</p>
    <p></p>
    <p>Thank you for your enquiry.：</p>
    <p>{!! $body !!}</p>
    <p></p>
    <p>For enquiry：<a href="{{url('/enquiry/ticket/'.$id.'/'.$token)}}">{{url('/enquiry/ticket/'.$id.'/'.$token)}}</a></p>
    <p></p>
    Best regards, 
    <p></p>
    <p>Registry<br>
    Macao Polytechnic University</p>

</body>
</html>