@extends('vendor.master')
@section('title')
    500 Internal server error
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
                            <img src="{{ asset('centaurus/img/error-500-v1.png') }}" alt="Error 500"/>
                        </div>
                        <h1>HTTP ERROR 500</h1>
                        <p>
                            服务器内部错误
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