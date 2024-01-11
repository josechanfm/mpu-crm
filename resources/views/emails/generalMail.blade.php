<!DOCTYPE html>
<html>
<head>
    <title>Macao Polytechnic University</title>
</head>
<body>
    <p>Testing email</p>
    {{ $body}}
    p>To view your question, please follow this link:</p>
    <a href="{{ url("/enquiry/ticket/{{$id}}/{{$token}}") }}">還想繼續問</a>
</body>
</html>
