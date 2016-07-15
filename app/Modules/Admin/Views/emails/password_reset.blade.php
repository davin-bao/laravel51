<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<br/>
<br/>
{{ $username }}, 你好：
<br/>
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('admins.password_reset_message', ['minutes' => $minutes]) }}
<a href="{{ URL::to('admins/password-reset') }}?token={{ $token }}">{{ URL::to('admins/password-reset') }}
    ?token={{ $token }}</a>
<br/>
<br/>
<br/>
</body>
</html>