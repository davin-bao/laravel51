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
                    "title": "parent",
                    "orderable": true,
                },
                {
                    "data": "icon",
                    "title": "icon",
                    "orderable": true,
                },
                {
                    "data": "uri" ,
                    "title": "uri",
                    "orderable": false,
                },
                {
                    "data": "action" ,
                    "title": "action",
                    "orderable": false,
                },
                {
                    "data": "display_name",
                    "title": "display_name",
                    "orderable": false,
                },
                {
                    "data": "is_menu",
                    "title": "is_menu",
                    "orderable": true,
                },
                {
                    "data": "sort",
                    "title": "sort",
                    "orderable": false,
                },
                {
                    "data": "created_at",
                    "title": "created_at",
                    "orderable": true,
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
            self.listDataTable.search(data);
        });
    }
};

(function(){
    page.init();
})();