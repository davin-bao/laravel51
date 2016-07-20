@extends('Admin::vendor.index')

@section('container')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>系统设置</span></h2>
    </div>

    <div class="contentpanel panel-email">

        <div class="row">


            <div class="col-sm-9 col-lg-10">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <a class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                                <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除"><i
                                            class="glyphicon glyphicon-trash"></i></a>
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle mb5">角色列表</h5>
                        <div class="table-responsive col-md-12">
                            <table class="table mb30" id="list">
                                <thead>
                                <tr>
                                    <th>
                                        <span class="ckbox ckbox-primary">
                                            <input type="checkbox" id="selectall"/>
                                            <label for="selectall"></label>
                                        </span>
                                    </th>
                                    <th>角色名</th>
                                    <th>说明</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- panel -->

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>
@endsection

@section('foot-scripts')
    @parent
    <script type="text/javascript">
        (function(){

            var table = $('#list').dataTable({
                "serverSide": true,
                "ajax": {
                    "url": "/admin/role/list",
                    "dataSrc": "rows"
                },
                "columns": [
                    { "data": "id" },
                    {
                        "data": "display_name"
                    },
                    { "data": "description" },
                    { "data": "created_at" },
                    { "data": "operate" }
                ],
                "columnDefs": [ {
                    "targets": -1,
                    "data": 'operate',
                    "defaultContent": '<a class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>\
                        <a class="btn btn-info btn-xs role-permissions"><i class="fa fa-wrench"></i> 权限</a>\
                        <a class="btn btn-danger btn-xs" data-href=""><i class="fa fa-trash-o"></i> 删除</a>'
                } ]
            });

        })();

        $(".role-delete").click(function () {
            Rbac.ajax.delete({
                confirmTitle: '确定删除角色?',
                href: $(this).data('href'),
                successTitle: '角色删除成功'
            });
        });

        $(".deleteall").click(function () {
            Rbac.ajax.deleteAll({
                confirmTitle: '确定删除选中的角色?',
                href: $(this).data('href'),
                successTitle: '角色删除成功'
            });
        });
    </script>

@endsection
