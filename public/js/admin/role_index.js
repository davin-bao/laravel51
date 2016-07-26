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