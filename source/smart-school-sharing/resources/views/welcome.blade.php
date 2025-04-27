<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
<h1>Welcome to {{ config('app.name') }}!</h1>
<p>Hello {{ $user->name }},</p>
<p>Thank you for registering with us. Here are your account details:</p>

<ul>
    <li>Email: {{ $user->email }}</li>
    <li>Registered at: {{ $user->created_at->format('d/m/Y H:i') }}</li>
</ul>

<p>If you didn't request this, please ignore this email.</p>

<footer>
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
</footer>
</body>
</html>
