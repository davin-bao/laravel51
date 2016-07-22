@extends('Admin::vendor.index')

@section('container')
    <div class="main-box clearfix">
        <header class="main-box-header clearfix">
            <h2 class="pull-left"><i class="fa fa-home"></i> 角色列表</h2>
            <div class="filter-block pull-right">
                <div class="form-group pull-left">
                    <input type="text" class="form-control" id="matchCon" name="matchCon" placeholder="Search...">
                </div>
                <a class="btn btn-info pull-left" id="search-btn">
                    <i class="fa fa-search fa-lg"></i> 搜索
                </a>

                <a href="#" class="btn btn-primary pull-right">
                    <i class="fa fa-plus-circle fa-lg"></i> 添加角色
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
@endsection

@section('foot-scripts')
    @parent
    <script type="text/javascript">
        var page = {
            urlParam: Public.urlParam(),
            matchConDom: $('#matchCon'),
            searchDom: $('#search-btn'),
            listDom: $('#list'),

            listDataTable: null,

            init: function(){
                this.initDom(), this.addEvent();
            },
            initDom: function(){
                var self = this;

                var options = {
                    "index": "/admin/role",
                    "list": "/list",
                    "columns": [
                        {
                            "data": "id" ,
                            "title": "id",
                            "orderable": true,
                        },
                        {
                            "data": "display_name",
                            "title": "display_name",
                            "orderable": true,
                        },
                        {
                            "data": "description" ,
                            "title": "description",
                            "orderable": false,
                        },
                        {
                            "data": "created_at" ,
                            "title": "created_at",
                            "orderable": false,
                        },
                        {
                            "data": "operate",
                            "title": "operate",
                            "orderable": false,
                        },
                    ],
                };
                self.listDataTable = self.listDom.table(options);
            },
            addEvent: function(){
                var self = this;
                self.searchDom.on('click', function(){
                    var data = {
                        'matchCon': self.matchConDom.val()
                    };
                    self.listDataTable.fnSearch(data);
                });
            }
        };

        (function(){
            page.init();
        })();
    </script>

@endsection
