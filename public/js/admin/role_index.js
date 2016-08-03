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
            "columns": [
                {
                    "data": "id" ,
                    "title": "id",
                    "orderable": true,
                },
                {
                    "data": "name" ,
                    "title": "角色名称",
                    "orderable": true,
                },
                {
                    "data": "display_name",
                    "title": "中文名称",
                    "orderable": true,
                },
                {
                    "data": "description" ,
                    "title": "描述",
                    "orderable": false,
                },
                {
                    "data": "created_at" ,
                    "title": "创建时间",
                    "orderable": false,
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