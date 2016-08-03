@extends('Admin::vendor.master')
@section('container')
    @section('stylesheets')
        <link rel="stylesheet" href="{{ asset('centaurus/css/libs/select2.css') }}" type="text/css"/>
    @endsection
    <div class="row">
        <div class="col-lg-12">
            {!! Breadcrumbs::render('admin-staff-add') !!}
            <h1><i class="fa fa-home"></i>用户 <small>编辑</small></h1>
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
                        <label for="username">用户账号</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder=""  value="{{ Input::old('username') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 name-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="" value="{{ Input::old('password') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 display_name-group">
                        <label for="name">用户名</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ Input::old('name') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 display_name-group">
                        <label for="email">邮箱</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="" value="{{ Input::old('email') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-3 display_name-group">
                        <label for="mobile">手机</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="" value="{{ Input::old('mobile') }}">
                    </div>
                    <div class="form-group form-group-select2">
                        <label>角色</label>
                        <select style="width:300px" id="sel2Multi" class="roles" name="roles" multiple value="{{ Input::old('roles') }}">
                        </select>
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
    <script src="{{ asset('centaurus/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/common/selector.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin/staff_add.js') }}"></script>
@endsection