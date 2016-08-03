<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="content-Type"content="text/html;charset=utf-8" />
        <meta http-equiv="Content-Language"content="zh-cn"/>
        <meta name="author"content="xmisp,root@xmisp.com" />
        <meta name="copyright" content="xmisp" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <meta name="keywords"content="@yield('keywords')" />
        <title>@yield('title')</title>
        @yield('head')
        <style type="text/css">
            .dataTables_filter {
                /*display: none;*/
            }
        </style>
    </head>
    <body>
    @yield('body')
    {!! Html::notification() !!}
    <div class="loading"></div>
    </body>
</html>
