@extends('emails.master')

@section('content')
{!! trans('admins.account_confirmed_successfully', ['username' => $username]) !!}
@stop