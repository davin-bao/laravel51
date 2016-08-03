/**
 * 权限列表脚本
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
            "index": "admin/permission",
            "list": "/list",
            "columns": [
                {
                    "data": "id" ,
                    "title": "id",
                    "orderable": true,
                },
                {
                    "data": "parent" ,
                    "title": "父菜单",
                    "orderable": false,
                },
                {
                    "data": "icon",
                    "title": "图标",
                    "orderable": false,
                },
                {
                    "data": "uri" ,
                    "title": "路径",
                    "orderable": false,
                },
                {
                    "data": "action" ,
                    "title": "URL地址对接的接口地址",
                    "orderable": false,
                },
                {
                    "data": "display_name",
                    "title": "权限名称",
                    "orderable": false,
                },
                {
                    "data": "is_menu",
                    "title": "作为菜单显示",
                    "orderable": true,
                },
                {
                    "data": "sort",
                    "title": "排序",
                    "orderable": true,
                },
                {
                    "data": "created_at",
                    "title": "创建时间",
                    "orderable": true,
                },
                {
                    "data": "operate",
                    "title": "操作",
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
            self.listDataTable.search(data);
        });
    }
};

(function(){
    page.init();
})();