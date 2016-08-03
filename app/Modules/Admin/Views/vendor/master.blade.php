@extends('vendor.master')
@section('head')
    {!! Html::headerLink() !!}
    @yield('stylesheets')
    @yield('head-scripts')
@endsection

@section('body')
    <div id="theme-wrapper">
        @include('Admin::vendor.header')
        <div id="page-wrapper" class="container">
            <div class="row">
                @include('Admin::vendor.menu')
                <div id="content-wrapper">
                    @yield('container')
                    <footer id="footer-bar" class="row">
                        <p id="footer-copyright" class="col-xs-12">
                            &copy; 2016 <a href="http://www.xmisp.com/" target="_blank">xmisp</a>. Powered by XMISP.
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
    @include('Admin::vendor.layout_option')
    {!! Html::footerScript() !!}

    {{--初始化脚本--}}
    <script type="application/javascript">
        (function(){
            //初始化公共方法
            Public.init();
            //初始化 UI 组件
            Widgets.init();
        })();
    </script>
    @yield('foot-scripts')
@endsection