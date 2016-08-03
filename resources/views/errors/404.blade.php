@extends('vendor.master')
@section('title')
    404 Page not found
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('centaurus/css/libs/font-awesome.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('centaurus/css/libs/theme_styles.css') }}"/>
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div id="error-box">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="error-box-inner">
                            <img src="{{ asset('centaurus/img/error-404-v3.png') }}" alt="Have you seen this page?"/>
                        </div>
                        <h1>HTTP ERROR 404</h1>
                        <p>
                            你所访问页面不存在<br/>
                        </p>
                        <p>
                            Go back to <a href="/">homepage</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection