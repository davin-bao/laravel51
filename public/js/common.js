
//<editor-fold desc="Public Functions">
//公共方法
var Public = Public || {};
Public.ROOT_URL = Public.ROOT_URL || {};

$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});
var abortPendingRequests = {};
if( $.ajaxPrefilter ) {
    $.ajaxPrefilter( function( settings, _, xhr ) {
        var port = settings.url + settings.type;
        if( settings.mode === "abort" ) {
            if( abortPendingRequests[ port ] ) {
                xhr.abort();
            }
            abortPendingRequests[ port ] = xhr;
        }
    } );
}

var limitPendingRequests = {};
$.ajaxSetup( {
    beforeSend: function( xhr, settings ) {
        if( settings.mode !== 'limit' ) return true;
// ajax 请求限制
        var port = settings.url + settings.type;
        if( limitPendingRequests[ port ] ) return false;
        limitPendingRequests[ port ] = true;
        xhr.complete( function() {
            delete limitPendingRequests[ port ];
        } );
    }
} );
/*
 通用get请求，返回json
 url:请求地址， params：传递的参数{...}， callback：请求成功回调
 */
Public.ajaxGet = function(url, params, callback, errCallback){
    $.ajax({
        type: "GET",
        url: Public.ROOT_URL() + url,
        dataType: "json",
        data: params,
        mode: "limit",
        success: function(data, status){
            callback(data);
        },
        error: function(err){
            errCallback && errCallback(err);
        }
    });
};
/*
 通用post请求，返回json
 url:请求地址， params：传递的参数{...}， callback：请求成功回调
 */
Public.ajaxPost = function(url, params, callback, errCallback){
    $.ajax({
        type: "POST",
        url: Public.ROOT_URL() + url,
        data: params,
        dataType: "json",
        mode: "limit",
        success: function(data, status){
            callback(data);
        },
        error: function(err,msg){
            var data = {'code': 500, 'msg': '服务器内部错误'};
            if(err.status == 422){
                data = {'code': 422, 'msg': '输入参数有误'};
            }else if(err.status == 500 && typeof(err.responseJSON) !== "undefined"){
                data = err.responseJSON;;
            }
            errCallback && errCallback(data);
        }
    });
};
/*获取URL参数值*/
Public.getRequest = Public.urlParam = function() {
    var param, url = location.search, theRequest = {};
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0, len = strs.length; i < len; i ++) {
            param = strs[i].split("=");
            theRequest[param[0]]=decodeURIComponent(param[1]);
        }
    }
    return theRequest;
};
/*对象转URL参数字符串*/
Public.buildQuery = function(obj) {
    var param = '';
    for ( var p in obj ){ // 方法
        if ( typeof ( obj [ p ]) == " function " ){ obj [ p ]() ;
        } else { // p 为属性名称，obj[p]为对应属性的值
            param += p + "=" + encodeURIComponent(obj [ p ]) + "&" ;
        } } // 最后显示所有的属性
    return param.slice(0, param.length - 1);
}
/*提示*/
Public.tips = function(options){
    var notification = new NotificationFx($.extend(true, {
        message : '<p>$message</p>',
        layout : 'growl',
        effect : 'genie',
        type : '$type', // notice, warning or error
        onClose : function() {
            //关闭
        }
    }, options));
    notification.show();

};

$.fn.table = function( options ) {
    var self = this;

    options = $.extend(true, {
        "autoWidth":false,
        "bProcessing": true,
        "bPaginate": true,
        "index": "/admin/",
        "list": "/list",
        "edit": "/edit",
        "delete": "/delete",
        "delete_able": !0,
        "edit_able": !0,
        "bFilter": true,
        "searching": !1,
        "bLengthChange": !1,
        "aLengthMenu": [[50], [50]],
        "language": {
            "decimal":        "",
            "emptyTable":     "无记录",
            "info":           "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty":      "",
            "infoFiltered":   "共 _MAX_ 条记录",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Show _MENU_ entries",
            "loadingRecords": "Loading...",
            "processing":     "Processing...",
            "search":         "Search:",
            "zeroRecords":    "没有找到匹配的记录",
            "paginate": {
                "first":      "首页",
                "last":       "上一页",
                "next":       "下一页",
                "previous":   "前页"
            },
            "aria": {
                "sortAscending":  ": 正序",
                "sortDescending": ": 反序"
            }
        },
        "columnDefs": [ {
            "targets": -1,
            "data": 'operate',
            "render": function(data, type, full) {
                var operateHtml = '';
                options.edit_able && (operateHtml += '<a class="btn btn-success btn-xs edit-row-btn" data-id="'+full.id+'"><i class="fa fa-pencil"></i> 编辑</a>');
                options.delete_able && (operateHtml += '<a class="btn btn-danger btn-xs del-row-btn" data-id="'+full.id+'" data-href=""><i class="fa fa-trash-o"></i> 删除</a>');

                return operateHtml;
            }
        }]
    }, options);

    options = $.extend(true, options, {
        "serverSide": true,
        "ajax": {
            "dataSrc": "rows",
            "url": Public.ROOT_URL() + options.index + options.list
        },
        "columns": [
        ]
    });

    if(options.edit_able){
        self.on('click', '.edit-row-btn', function(){
            var index = $(this).data('id');
            window.location = Public.ROOT_URL() + options.index + options.edit + '/' + index;
        });
    }

    if(options.delete_able){
        self.on('click', '.del-row-btn', function() {
            var index = $(this).data('id');
            var url = options.index + options.delete;

            var dialog = BootstrapDialog.show({
                title: '删除信息',
                message: '确认删除该角色信息？',
                type: BootstrapDialog.TYPE_WARNING,
                buttons: [{
                    cssClass: 'btn-danger',
                    label: '删除',
                    action: function (dialog) {

                        var postData = {
                            id: index
                        };
                        if(index > 0){
                            postData["_method"] = "POST";
                        }

                        Public.ajaxPost(url, postData, function(event) {
                            if(200 == event.code){
                                Public.tips({
                                    message: "CODE " + event.code + ': ' + event.msg,
                                    type: 'success'
                                });
                                //删除行
                                self.find('.del-row-btn[data-id='+index+']').parents('tr').remove();

                                dialog.close();
                            }else {
                                Public.tips({
                                    message: "CODE " + event.code + ': ' + event.msg,
                                    type: 'error'
                                }), dialog.close();
                            }
                        }, function(event){
                            Public.tips({
                                message: "CODE " + event.code + ': ' + event.msg,
                                type: 'error'
                            }), dialog.close();
                        });
                    }
                }, {
                    label: '取消',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
        });
    }

    return self.DataTable(options);
};
$.fn.dataTable.Api.register( 'fnSearch()', function ( obj) {
    var url = this.ajax.url(),
        index = url.indexOf('?');
    (index > -1) && (url = url.slice(0, index));
    url = url + '?' + Public.buildQuery(obj);
    this.ajax.url( url ).load();
} );


//</editor-fold>