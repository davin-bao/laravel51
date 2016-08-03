@extends('Admin::vendor.master')

@section('container')

    <div class="row">
        <div class="col-lg-12">
            {!! Breadcrumbs::render('admin-permission-add') !!}
            <h1><i class="fa fa-home"></i> 权限 <small>编辑</small></h1>
        </div>
    </div>
    <div class="row">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2 class="pull-left">&nbsp;</h2>
                <div class="filter-block pull-right tool-bar"></div>
            </header>
            <div class="main-box-body clearfix">
                <form id="save-form">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group col-md-6 col-lg-3 parent-group">
                        <label for="parent">父菜单</label>
                        <input type="text" class="form-control" id="parent" name="parent" placeholder="" value="{{ Input::old('parent') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 icon-group">
                        <label for="icon">图标<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank"><i class="fa fa-info-circle"></i></a></label>
                        <input type="text"  data-toggle="tooltip" name="icon" id = "icon" data-trigger="hover" class="form-control tooltips" data-original-title="图标名称,去fa-前缀"  value="{{ Input::old('icon') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 uri-group">
                        <label for="uri">路径</label>
                        <input type="text" class="form-control" id="uri" name="uri" placeholder="" value="{{ Input::old('uri') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 action-group">
                        <label for="action">URL地址对接的接口地址</label>
                        <input type="text" class="form-control" id="action" name="action" placeholder="" value="{{ Input::old('action') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 display_name-group">
                        <label for="display_name">权限名称</label>
                        <input type="text" class="form-control" id="display_name" name="display_name" placeholder="" value="{{ Input::old('display_name') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 sort-group">
                        <label for="sort">排序</label>
                        <input type="text" class="form-control" id="sort" name="sort" rows="3" value="{{ Input::old('sort') }}" ></textarea>
                    </div>
                    <div class="form-group col-md-6 col-lg-3 is_menu-group">
                        <label for="is_menu">作为菜单显示</label>
                        <select class="form-control" id="is_menu" name="is_menu">
                            <option value = "0">关闭</option>
                            <option value = "1">开启</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 col-lg-12 description-group">
                        <label for="description">描述</label>
                        <textarea class="form-control" id="description" name="description" rows="3" value="{{ Input::old('description') }}" ></textarea>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10 tool-bar"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('foot-scripts')
    @parent
    <script type="text/javascript" src="{{ asset('js/admin/permission_add.js') }}"></script>
@endsection
