/**
 * 角色列表脚本
 */
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
            "index": "admin/role",
            "list": "/list",
            "edit": "/add",
            "delete": "/delete",
            "assignPermissions": '/assign-permissions',
            "delete_able": !0,
            "edit_able": !0,
            "assign_able": !0,
            "columns": [
                {
                    "data": "id" ,
                    "title": "id",
                    "orderable": true,
                },
                {
                    "data": "name" ,
                    "title": "name",
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
            "columnDefs": [ {
                "targets": -1,
                "data": 'operate',
                "render": function(data, type, full) {
                    var operateHtml = '';
                    options.edit_able && Public.power(options.index + options.edit) && (operateHtml += '<a class="btn btn-success btn-xs edit-row-btn" data-id="'+full.id+'"><i class="fa fa-pencil"></i> 编辑</a>');
                    options.assign_able && Public.power(options.index + options.assignPermissions) && (operateHtml += '<a class="btn btn-default btn-xs assign-row-btn" data-id="'+full.id+'"><i class="fa fa-wrench"></i> 权限</a>');
                    options.delete_able && Public.power(options.index + options.delete) && (operateHtml += '<a class="btn btn-danger btn-xs del-row-btn" data-id="'+full.id+'" data-href=""><i class="fa fa-trash-o"></i> 删除</a>');

                    return operateHtml;
                }
            }],
        };
        self.listDataTable = self.listDom.table(options);
        if(options.assign_able){
            self.listDataTable.on('click', '.assign-row-btn', function(){
                var index = $(this).data('id');
                window.location = Public.ROOT_URL + options.index + options.assignPermissions + '?id=' + index;
            });
        }
    },
    addEvent: function(){
        var self = this;
        self.searchDom.on('click', function(){
            var data = {
                'matchCon': self.matchConDom.val()
            };
            self.listDataTable.search(data);
        });
    }
};

(function(){
    page.init();
})();