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
                    <div class="row">
                        @yield('container')
                    </div>
                    <footer id="footer-bar" class="row">
                        <p id="footer-copyright" class="col-xs-12">
                            &copy; 2014 <a href="http://www.adbee.sk/" target="_blank">Adbee digital</a>. Powered by Centaurus Theme.
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>

    {!! Html::footerScript() !!}

    {{--初始化脚本--}}
    <script type="application/javascript">
        //提前声明
        Public.ROOT_URL = function() {
            return '<?php echo e(URL::to('/')); ?>';
        }

    </script>
    @yield('foot-scripts')
@endsection