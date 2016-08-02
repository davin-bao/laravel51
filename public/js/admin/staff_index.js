/**
 * 管理员列表脚本
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
                "index": "admin/staff",
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
                    "data": "email",
                    "title": "email",
                    "orderable": true,
                },
                {
                    "data": "username",
                    "title": "username",
                    "orderable": true,
                },
                {
                    "data": "mobile" ,
                    "title": "mobile",
                    "orderable": false,
                },
                {
                    "data": "confirm_token" ,
                    "title": "confirm_token",
                    "orderable": false,
                },
                {
                    "data": "confirmed_at" ,
                    "title": "confirmed_at",
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