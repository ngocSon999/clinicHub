<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản mới</title>
</head>
<body>
<h1>Chào mừng bạn, {{ $user->name }}!</h1>

<p>Tài khoản của bạn đã được tạo thành công trên hệ thống.</p>

<p>Dưới đây là thông tin đăng nhập của bạn:</p>
<ul>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Mật khẩu:</strong> {{ $plainPassword }}</li>
</ul>

<p>Vui lòng đăng nhập và đổi mật khẩu sớm để đảm bảo bảo mật.</p>

<p>Trân trọng,<br>
    Đội ngũ hỗ trợ hệ thống.</p>
</body>
</html>
