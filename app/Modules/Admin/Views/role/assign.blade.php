@extends('Admin::vendor.master')

@section('container')

    <div class="row">
        <div class="col-lg-12">
            {!! Breadcrumbs::render('admin-role-assign') !!}
            <h1><i class="fa fa-home"></i> 权限 <small>分配</small></h1>
        </div>
    </div>
    <div class="row">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2 class="pull-left" id="role-name">&nbsp;</h2>
                <div class="filter-block pull-right tool-bar"></div>
            </header>
            <div class="main-box-body clearfix">
                <form id="save-form">
                    <input type="hidden" id="id" name="id">
                    <div id="permission-list">

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
    <script type="text/javascript" src="{{ asset('js/admin/role_assign.js') }}"></script>
@endsection
