@extends('Admin::vendor.index')

@section('container')

    <div class="row">
        <div class="col-lg-12">
            {!! Breadcrumbs::render('admin-role-add') !!}
            <h1><i class="fa fa-home"></i> 角色 <small>编辑</small></h1>
        </div>
    </div>
    <div class="row">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2 class="pull-left">&nbsp;</h2>
                <div class="filter-block pull-right tool-bar">
                    <a href="#" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> 保存
                    </a>
                    <a class="btn btn-default">返回</a>
                </div>
            </header>
            <div class="main-box-body clearfix">
                <form role="form">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group col-md-6 col-lg-3">
                        <label for="name">角色英文标示</label>
                        <input type="text" class="form-control" id="name" placeholder="">
                    </div>
                    <div class="form-group col-md-6 col-lg-3">
                        <label for="display_name">角色中文名称</label>
                        <input type="text" class="form-control" id="display_name" placeholder="">
                    </div>
                    <div class="form-group col-md-12 col-lg-12">
                        <label for="description">描述</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10 tool-bar">
                            {{--<button type="submit" class="btn btn-success"><i class="fa fa-plus-circle fa-lg"></i>保存</button>--}}
                            {{--<a class="btn btn-default">返回</a>--}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('foot-scripts')
    @parent
    <script type="text/javascript" src="{{ asset('js/admin/role_add.js') }}"></script>
@endsection
