@extends('Admin::vendor.index')

@section('container')

    <div class="row">
        <div class="col-lg-12">
            {!! Breadcrumbs::render('admin-permission-index') !!}
            <h1><i class="fa fa-home"></i> 权限 <small>列表</small></h1>
        </div>
    </div>
    <div class="row">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2 class="pull-left">&nbsp;</h2>
                <div class="filter-block pull-right">
                    <div class="form-group pull-left">
                        <input type="text" class="form-control" id="matchCon" name="matchCon" placeholder="Search...">
                    </div>
                    <a class="btn btn-info pull-left" id="search-btn">
                        <i class="fa fa-search fa-lg"></i> 搜索
                    </a>

                    <a href="{{ adminAction('PermissionController@getAdd') }}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> 添加权限
                    </a>
                </div>
            </header>
            <div class="main-box-body clearfix">
                <div class="row panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive col-md-12">
                            <table class="table mb30" id="list">
                            </table>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- row panel -->
            </div>
        </div>
    </div>
@endsection

@section('foot-scripts')
    @parent
    <script type="text/javascript" src="{{ asset('js/admin/permission_index.js') }}"></script>
@endsection
