@extends('Admin::emails.master')

@section('content')
<table>
    <tr>
        <th>{{ trans('admins.username') }}: </th>
        <td>{{ $username }}</td>
    </tr>
    <tr>
        <th>{{ trans('admins.email') }}: </th>
        <td>{{ $user_email }}</td>
    </tr>
    <tr>
        <th>{{ trans('admins.registered_at') }}: </th>
        <td>{{ date('d/m/Y - h:i:s', strtotime($created_at)) }}</td>
    </tr>
    <tr>
        <th>{{ trans('admins.confirmed_at') }}: </th>
        <td>{{ date('d/m/Y - h:i:s', strtotime($confirmed_at)) }}</td>
    </tr>
</table>
@stop