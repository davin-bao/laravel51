
//<editor-fold desc="Public Functions">
//公共方法
var Public = Public || {};

Public.ROOT_URL = function() {
    return '<?php echo e(URL::to('/')); ?>';
}

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
            errCallback && errCallback(err);
        }
    });
};

//</editor-fold>