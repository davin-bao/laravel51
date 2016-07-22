@extends('vendor.master')
@section('head')
    {!! Html::headerLink() !!}
    @yield('stylesheets')
    @yield('head-scripts')
@endsection

@section('body')
    @yield('container')
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