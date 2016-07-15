<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <table>
            <tr>
                <th>{{ trans('admins.username') }}: </th>
                <td>{{ $admin_mail_data['username'] }}</td>
            </tr>
            <tr>
                <th>{{ trans('admins.email') }}: </th>
                <td>{{ $admin_mail_data['email'] }}</td>
            </tr>
            <tr>
                <th>{{ trans('admins.registered_at') }}: </th>
                <td>{{ date('d/m/Y - h:i:s', strtotime($admin_mail_data['created_at'])) }}</td>
            </tr>
        </table>
    </body>
</html>