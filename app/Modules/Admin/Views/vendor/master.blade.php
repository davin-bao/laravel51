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
                            &copy; 2014 <a href="http://www.adbee.sk/" target="_blank">Adbee digital</a>. Powered by Centaurus Theme.
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>

    <div id="config-tool" class="closed">
        <a id="config-tool-cog">
            <i class="fa fa-cog"></i>
        </a>
        <div id="config-tool-options">
            <h4>Layout Options</h4>
            <ul>
                <li>
                    <div class="checkbox-nice">
                        <input type="checkbox" id="config-fixed-header">
                        <label for="config-fixed-header">
                            Fixed Header
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox-nice">
                        <input type="checkbox" id="config-fixed-sidebar">
                        <label for="config-fixed-sidebar">
                            Fixed Left Menu
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox-nice">
                        <input type="checkbox" id="config-fixed-footer">
                        <label for="config-fixed-footer">
                            Fixed Footer
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox-nice">
                        <input type="checkbox" id="config-boxed-layout">
                        <label for="config-boxed-layout">
                            Boxed Layout
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox-nice">
                        <input type="checkbox" id="config-rtl-layout">
                        <label for="config-rtl-layout">
                            Right-to-Left
                        </label>
                    </div>
                </li>
            </ul>
            <br>
            <h4>Skin Color</h4>
            <ul id="skin-colors" class="clearfix">
                <li>
                    <a class="skin-changer" data-skin="" data-toggle="tooltip" title="" style="background-color: #34495e;" data-original-title="Default">
                    </a>
                </li>
                <li>
                    <a class="skin-changer" data-skin="theme-white" data-toggle="tooltip" title="" style="background-color: #2ecc71;" data-original-title="White/Green">
                    </a>
                </li>
                <li>
                    <a class="skin-changer blue-gradient active" data-skin="theme-blue-gradient" data-toggle="tooltip" title="" data-original-title="Gradient">
                    </a>
                </li>
                <li>
                    <a class="skin-changer" data-skin="theme-turquoise" data-toggle="tooltip" title="" style="background-color: #1abc9c;" data-original-title="Green Sea">
                    </a>
                </li>
                <li>
                    <a class="skin-changer" data-skin="theme-amethyst" data-toggle="tooltip" title="" style="background-color: #9b59b6;" data-original-title="Amethyst">
                    </a>
                </li>
                <li>
                    <a class="skin-changer" data-skin="theme-blue" data-toggle="tooltip" title="" style="background-color: #2980b9;" data-original-title="Blue">
                    </a>
                </li>
                <li>
                    <a class="skin-changer" data-skin="theme-red" data-toggle="tooltip" title="" style="background-color: #e74c3c;" data-original-title="Red">
                    </a>
                </li>
                <li>
                    <a class="skin-changer" data-skin="theme-whbl" data-toggle="tooltip" title="" style="background-color: #3498db;" data-original-title="White/Blue">
                    </a>
                </li>
            </ul>
        </div>
    </div>

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