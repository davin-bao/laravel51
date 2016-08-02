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
                    "title": "姓名",
                    "orderable": true,
                },
                {
                    "data": "email",
                    "title": "Email",
                    "orderable": true,
                },
                {
                    "data": "username",
                    "title": "用户名",
                    "orderable": true,
                },
                {
                    "data": "mobile" ,
                    "title": "手机号",
                    "orderable": false,
                },
                {
                    "data": "confirmed_at" ,
                    "title": "是否已验证",
                    "orderable": false,
                },
                {
                    "data": "operate",
                    "title": "操作",
                    "orderable": false,
                },
            ],
            "columnDefs": [{
                "targets": 5,//index of column starting from 0
                "data": "confirmed_at", //this name should exist in your JSON response
                "render": function (data, type, full, meta) {
                    if(data == null) return '<span class="label label-danger">否</span>';
                    return '<span class="label label-success">是</span>';
                }
            }]
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