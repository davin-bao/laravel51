@extends('vendor.master')
@section('head')
    {!! Html::headerLink() !!}
    @yield('stylesheets')
    @yield('head-scripts')
@endsection

@section('body')
    @yield('container')
    {!! Html::footerScript() !!}
    @yield('foot-scripts')
@endsection