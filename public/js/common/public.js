/**
 * 公共方法
 * 前端的工具，重写或扩展的源生、JQuery等方法
 *
 * @author davin.bao
 * @since 2016/7/25 10:34
 */
var Public = Public || {};
Public.ROOT_URL = Public.ROOT_URL || (function(){ return location.href.substr(0, location.href.indexOf(location.pathname)) + '/'; })();

Public.isIE6 = !window.XMLHttpRequest;	//ie6

Public.init = function () {
    /*
     * 判断IE6，提示使用高级版本
     */
    if(Public.isIE6) {
        var Oldbrowser = {
            init: function(){
                this.addDom();
            },
            addDom: function() {
                var html = $('<div id="browser">您使用的浏览器版本过低，影响网页性能，建议您换用<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" target="_blank">谷歌</a>、<a href="http://download.microsoft.com/download/4/C/A/4CA9248C-C09D-43D3-B627-76B0F6EBCD5E/IE9-Windows7-x86-chs.exe" target="_blank">IE9</a>、或<a href=http://firefox.com.cn/" target="_blank">火狐浏览器</a>，以便更好的使用！<a id="bClose" title="关闭">x</a></div>').insertBefore('#container').slideDown(500);
                this._colse();
            },
            _colse: function() {
                $('#bClose').click(function(){
                    $('#browser').remove();
                });
            }
        };
        Oldbrowser.init();
    };

    //获取用户权限
    Cache.register('STAFF_PERMISSION_URL_LIST', 'admin/staff/permission-url-list');

    //AJAX 设定
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
};

//<editor-fold desc="URL参数值操作">
/**
 * 判断当前用户是否有权限
 * @param dataRight Url 地址
 * @returns {boolean}
 */
Public.power = function (dataRight) {
    var permissions = Cache.get('STAFF_PERMISSION_URL_LIST');
    if(isArray(permissions) && permissions.indexOf(dataRight) !== -1){
        return true;
    }
    return false;

};

/**
 * 数值转化金额
 * @param val
 * @param dec
 * @returns {*}
 */
Public.numToCurrency = function(val, dec) {
    val = parseFloat(val);
    dec = dec || 2;	//小数位
    if(val === 0 || isNaN(val)){
        return '0.00';
    }
    val = val.toFixed(dec).split('.');
    var reg = /(\d{1,3})(?=(\d{3})+(?:$|\D))/g;
    return val[0].replace(reg, "$1,") + '.' + val[1];
};
/**
 * 金额转化为数值
 * @param val
 * @returns {*}
 */
Public.currencyToNum = function(val){
    var val = String(val);
    if ($.trim(val) == '') {
        return '';
    }
    val = val.replace(/,/g, '');
    val = parseFloat(val);
    return isNaN(val) ? 0 : val;
};
/**
 * HTML 编码
 * @param s
 * @returns {*}
 */
Public.htmlEncode = function(s){
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(s));
    return div.innerHTML;
}
/**
 * HTML 解码
 * @param s
 * @returns {string}
 */
Public.htmlDecode = function(s){
    var div = document.createElement('div');
    div.innerHTML = s;
    return div.innerText || div.textContent;
}
/**
 * 浮点数加法运算
 * @param arg1
 * @param arg2
 * @returns {number}
 */
Public.floatAdd = function(arg1,arg2){
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2))
    return (arg1*m+arg2*m)/m
}
/**
 * 浮点数减法运算
 * @param arg1
 * @param arg2
 * @returns {string}
 */
Public.floatSub = function(arg1,arg2){
    var r1,r2,m,n;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2));
    //动态控制精度长度
    n=(r1>=r2)?r1:r2;
    return ((arg1*m-arg2*m)/m).toFixed(n);
}
/**
 * 浮点数乘法运算
 * @param arg1
 * @param arg2
 * @returns {number}
 */
Public.floatMul = function(arg1,arg2){
    var m=0,s1=arg1.toString(),s2=arg2.toString();
    try{m+=s1.split(".")[1].length}catch(e){}
    try{m+=s2.split(".")[1].length}catch(e){}
    return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
}
/**
 * 浮点数除法运算
 * @param arg1
 * @param arg2
 * @returns {number}
 */
Public.floatDiv = function(arg1,arg2){
    var t1=0,t2=0,r1,r2;
    try{t1=arg1.toString().split(".")[1].length}catch(e){}
    try{t2=arg2.toString().split(".")[1].length}catch(e){}
    with(Math){
        r1=Number(arg1.toString().replace(".",""))
        r2=Number(arg2.toString().replace(".",""))
        return (r1/r2)*pow(10,t2-t1);
    }
}
//</editor-fold>

//<editor-fold desc="AJAX">
/**
 * 通用get请求，返回json
 * @param url 请求地址
 * @param params 传递的参数{...}
 * @param callback 请求成功回调
 * @param errCallback
 */
Public.ajaxGet = function(url, params, callback, errCallback){
    $('.loading').show();

    $.ajax({
        type: "GET",
        url: Public.ROOT_URL + url,
        dataType: "json",
        data: params,
        async: (typeof(params.async) == 'undefined') ? true : params.async,
        mode: "limit",
        complete: function(xMLHttpRequest, textStatus){
            $('.loading').hide();
        },
        success: function(data, status){
            callback(data);
        },
        error: function(err){
            $('.loading').hide();
            var errData = {'code': 500, 'msg': '服务器内部错误'};
            if(err.status == 422 && typeof(err.responseJSON) !== "undefined"){
                errData = $.extend(true, {'code': 422, 'msg': '输入参数有误', 'errData': err.responseJSON });
            }else if(err.status == 500 && typeof(err.responseJSON) !== "undefined"){
                errData = err.responseJSON;
            }else if(err.status == 403){
                errData = {'code': 403, 'msg': '禁止访问'};
            }
            errCallback && errCallback(errData);
        }
    });
};

/**
 * 通用post请求，返回json
 * @param url 请求地址
 * @param params 传递的参数{...}
 * @param callback 请求成功回调
 * @param errCallback
 */
Public.ajaxPost = function(url, params, callback, errCallback){
    $('.loading').show();
    $.ajax({
        type: "POST",
        url: Public.ROOT_URL + url,
        data: params,
        async: (typeof(params.async) == 'undefined') ? true : params.async,
        dataType: "json",
        mode: "limit",
        success: function(data, status){
            callback(data);
            setTimeout(function(){ $('.loading').hide() }, 2500);
        },
        error: function(err,msg){
            $('.loading').hide();
            var errData = {'code': 500, 'msg': '服务器内部错误'};
            if(err.status == 422 && typeof(err.responseJSON) !== "undefined"){
                errData = $.extend(true, {'code': 422, 'msg': '输入参数有误', 'errData': err.responseJSON });
            }else if(err.status == 500 && typeof(err.responseJSON) !== "undefined"){
                errData = err.responseJSON;
            }else if(err.status == 403){
                errData = $.extend(true, {'code': 403, 'msg': '禁止访问'});
            }
            errCallback && errCallback(errData);
        }
    });
};
//</editor-fold>

//<editor-fold desc="URL参数值操作">
/**
 * 获取 URL 参数
 * @type {Public.urlParam}
 */
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
/**
 * 对象转URL参数字符串
 * @param obj { key: value }
 * @returns {string}
 */
Public.buildQuery = function(obj) {
    var param = '';
    for ( var p in obj ){ // 方法
        if ( typeof ( obj [ p ]) == " function " ){ obj [ p ]() ;
        } else { // p 为属性名称，obj[p]为对应属性的值
            param += p + "=" + encodeURIComponent(obj [ p ]) + "&" ;
        } } // 最后显示所有的属性
    return param.slice(0, param.length - 1);
}
//</editor-fold>

//<editor-fold desc="原型方法扩展">

//判断是否为数组
var isArray = function(array){
    return Object.prototype.toString.call(array) === '[object Array]';
};
//IE9以前不支持 indexOf
if(!Array.indexOf){
    Array.prototype.indexOf = function(obj)
    {
        for(var i=0; i<this.length; i++)
        {
            if(this[i]==obj)
            {
                return i;
            }
        }
        return -1;
    }
}
//获取数组最大值
Array.prototype.max = function() {
    return Math.max.apply(Math, this);
};
//获取数组最小值
Array.prototype.min = function() {
    return Math.min.apply(Math, this);
};
//根据对象ID获取对象数组
Array.prototype.getById=function(id){
    return this.getByKey('id', id);
};
/**
 * 根据对象数组元素的某个属性及属性值获取该对象
 * @param searchKey 属性名称
 * @param searchValue 属性值
 * @returns {*} 对象
 */
Array.prototype.getByKey=function(searchKey, searchValue){
    if(typeof(searchKey)== "undefined" || searchKey == '' || typeof(searchValue)== "undefined" || searchValue===''){return false;}

    for(var i=0;i<this.length;i++){
        for(key in this[i]){
            if (typeof(this[i][key]) == "undefined") continue;
            if(key == searchKey && this[i][key] == searchValue){
                return this[i];
            }
        }
    }
    return false;
};
//日期格式化
Date.prototype.format = function(format){
    if(!format){
        format = 'yyyy-MM-dd';//默认1997-01-01这样的格式
    }
    var o = {
        "M+" : this.getMonth()+1, //month
        "d+" : this.getDate(), //day
        "h+" : this.getHours() == 0 ? (new Date()).getHours() : this.getHours(), //hour
        "m+" : this.getMinutes() == 0 ? (new Date()).getMinutes() : this.getMinutes(), //minute
        "s+" : this.getSeconds() == 0 ? (new Date()).getSeconds() : this.getSeconds(), //second
        "q+" : Math.floor((this.getMonth()+3)/3), //quarter
        "S" : this.getMilliseconds() //millisecond
    }

    if(/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }

    for(var k in o) {
        if(new RegExp("("+ k +")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        }
    }
    return format;
};
//字符串截取
String.prototype.cutStr = function(len, end) {
    var str_length = 0, str_len = 0;
    var str_cut = new String();
    str_len = this.length;
    end = typeof(end) == 'undefined' ? '...' : end;
    len = typeof(len) == 'undefined' ? str_len : len;

    //如果给定字符串小于指定长度，则返回源字符串；
    if (str_len <= len) {
        return this.toString();
    }

    for (var i = 0; i < str_len; i++) {
        a = this.charAt(i);
        str_length++;
        if (escape(a).length > 4) {
            //中文字符的长度经编码之后大于4
            str_length++;
        }
        str_cut = str_cut.concat(a);
        if (str_length >= len) {
            str_cut = str_cut.concat(end);
            return str_cut;
        }
    }

    return this.toString();
}
//兼容IE8 的事件终止方法
$.Event.prototype.cancel = function() {
    if(this && this.preventDefault){
        this.preventDefault();
    }else{
        window.event.returnValue = false;//注意加window
    }
}
//兼容IE8 的事件终止方法
Event.prototype.cancel = function() {
    if(this && this.preventDefault){
        this.preventDefault();
    }else{
        window.event.returnValue = false;//注意加window
    }
}
/**
 * DataTable
 * @param options
 */
$.fn.table = function( options ) {
    var self = this;

    options = $.extend(true, {
        "autoWidth":false,
        "bProcessing": true,
        "bPaginate": true,
        "index": "admin",
        "list": "/list",
        "edit": "/add",
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
            "processing":     " ",
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
        }
    }, options);

    if((typeof(options.columnDefs) === 'undefined')){
        options.columnDefs = [];
    }

    if(options.edit_able || options.delete_able){
        options.columnDefs.push({
            "targets": -1,
            "data": 'operate',
            "render": function(data, type, full) {
                var operateHtml = '';
                options.edit_able && Public.power(options.index + options.edit) && (operateHtml += '<a class="btn btn-success btn-xs edit-row-btn" data-id="'+full.id+'"><i class="fa fa-pencil"></i> 编辑</a>');
                options.delete_able && Public.power(options.index + options.delete) && (operateHtml += '<a class="btn btn-danger btn-xs del-row-btn" data-id="'+full.id+'" data-href=""><i class="fa fa-trash-o"></i> 删除</a>');

                return operateHtml;
            }
        });
    }

    options = $.extend(true, options, {
        "serverSide": true,
        "ajax": {
            "dataSrc": "data",
            "url": Public.ROOT_URL + options.index + options.list
        },
        "columns": [
        ]
    });

    if(options.edit_able){
        self.on('click', '.edit-row-btn', function(){
            var index = $(this).data('id');
            window.location = Public.ROOT_URL + options.index + options.edit + '?id=' + index;
        });
    }

    if(options.delete_able){
        self.on('click', '.del-row-btn', function() {
            var index = $(this).data('id');
            var url = options.index + options.delete;
            //确认删除对话框
            Widgets.Dialogs.deleteConfirm(url, index, function(){
                //删除行
                self.find('.del-row-btn[data-id='+index+']').parents('tr').remove();
            });
        });
    }

    return self.DataTable(options);
};
typeof($.fn.dataTable) != 'undefined' && $.fn.dataTable.Api.register( 'search()', function ( obj) {
    var url = this.ajax.url(),
        index = url.indexOf('?');
    (index > -1) && (url = url.slice(0, index));
    url = url + '?' + Public.buildQuery(obj);
    this.ajax.url( url ).load();
} );
//</editor-fold>