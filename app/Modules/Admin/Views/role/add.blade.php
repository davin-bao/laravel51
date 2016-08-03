@extends('Admin::vendor.master')

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
                <div class="filter-block pull-right tool-bar"></div>
            </header>
            <div class="main-box-body clearfix">
                <form id="save-form">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group col-md-6 col-lg-3 name-group">
                        <label for="name">角色英文标示</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ Input::old('name') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 display_name-group">
                        <label for="display_name">角色中文名称</label>
                        <input type="text" class="form-control" id="display_name" name="display_name" placeholder="" value="{{ Input::old('display_name') }}">
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
    <script type="text/javascript" src="{{ asset('js/admin/role_add.js') }}"></script>
@endsection
