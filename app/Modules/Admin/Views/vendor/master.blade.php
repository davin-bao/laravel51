@extends('vendor.master')
@section('head')
    {!! Form::headerLink() !!}
    @yield('stylesheets')
    @yield('head-scripts')
@endsection

@section('body')
    @yield('container')

    {!! Form::footScript() !!}
    @yield('foot-scripts')
@endsection