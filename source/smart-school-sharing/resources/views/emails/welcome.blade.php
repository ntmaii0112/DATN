<!DOCTYPE html>
<html>
<head>
    <title>Chào mừng {{ $data['user']->name }}</title>
</head>
<body>
<h1>Welcome {{ $data['user']->name }} to Smart School Sharing!</h1>

<p>Thank you for registering an account at Smart School Sharing. Below is your account information:</p>

<ul>
    <li><strong>Full Name:</strong> {{ $data['user']->name }}</li>
    <li><strong>Email:</strong> {{ $data['user']->email }}</li>
    <li><strong>Phone:</strong> {{ $data['user']->phone }}</li>
    <li><strong>Address:</strong> {{ $data['user']->address }}</li>
</ul>

<p><a href="{{ $data['login_url'] }}">Đăng nhập ngay</a></p>

<p>Trân trọng,<br>Đội ngũ Smart School Sharing</p>
</body>
</html>
