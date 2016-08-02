/**
 * UI 小组件
 * 前端的UI组件封装
 *
 * @author davin.bao
 * @since 2016/7/25 10:34
 */
var Widgets = Widgets || {};

Widgets.init = function () {
    //
};

//<editor-fold desc="杂类">
Widgets.tips = function(options){
    var notification = new NotificationFx($.extend(true, {
        message : '<p>$message</p>',
        layout : 'growl',
        effect : 'genie',
        type : '$type', // notice, warning or error
        ttl: 2000,
        onClose : function() {
            //关闭
        }
    }, options));
    notification.show();
};
//</editor-fold>

//<editor-fold desc="按钮类">
Widgets.OperateButtons = Widgets.OperateButtons || {};
/**
 * 操作按钮基类
 * @param $_obj 当前页中 page 对象
 * @param id 按钮的ID
 * @param url 按钮动作地址
 * @param label 显示的名称
 * @param callback 回调
 * @param classes 按钮class样式
 * @returns {*}
 * @private
 */
Widgets.OperateButtons._button = function($_obj, id, url, label, callback, classes){
    label = typeof(label) === 'undefined' ? '保存' : label;
    classes = typeof(classes) === 'undefined' ? '' : classes;
    var formDom = $('#'+id+'-form');

    $_obj.toolBarDom.on('click', '#' + id, function(){
        var paramData = $_obj.getPostData();
        if(!paramData) return false;

        if (!formDom.valid()) {
            formDom.find("input.has-error").eq(0).focus();
            return false;
        }

        Public.ajaxPost(url, paramData, function(event) {
            switch(event.code){
                case 200:   //操作成功
                    Widgets.tips({
                        type: "success",
                        message: event.msg,
                        onClose : function() {
                            typeof(callback) !== 'undefined' && callback();
                        }
                    });
                    break;
                default:   //其他错误
                    Widgets.tips({
                        type: "error",
                        message: event.msg
                    });
                    break;
            }
        }, function (err) {
            switch(err.code){
                case 422:   //输入参数错误
                    Widgets.tips({
                        type: "error",
                        message: "CODE 422: 输入参数有误"
                    });
                    //解析输入参数并显示到前端
                    if(typeof(err.errData) != "object") break;

                    $.each(err.errData,function(n,value) {
                        if(value.length > 0 && $('#'+n).length > 0 ) {
                            formDom.validate().errorList.push({
                                message: value.pop(),
                                element: $('#' + n)[0],
                                method: 'server-error'
                            });
                        }
                    });
                    //显示错误信息
                    formDom.validate().defaultShowErrors();

                    break;
                default:   //其他错误
                    Widgets.tips({
                        type: "error",
                        message: err.msg
                    });
                    break;
            }
        });
    });

    return '<a id="'+id+'" class="btn '+classes+'"><i class="fa fa-'+id+'"></i> '+label+'</a>';
};

/**
 * 带有权限验证的按钮基类
 * @see Widgets.OperateButtons._button()
 */
Widgets.OperateButtons._powerButton = function($_obj, id, url, label, callback, classes){
    if(Public.power(url)){
        return Widgets.OperateButtons._button($_obj, id, url, label, callback, classes);
    }
    return '';
};
/**
 * 保存按钮
 * @see Widgets.OperateButtons._button()
 */
Widgets.OperateButtons.save = function($_obj, id, url, label, callback){
    return Widgets.OperateButtons._powerButton($_obj, id, url, label, callback, 'btn-success');
};
Widgets.OperateButtons.back = function($_obj, id, label){
    id = typeof(id) === 'undefined' ? 'back' : id;
    label = typeof(label) === 'undefined' ? '返回' : label;

    $_obj.toolBarDom.on('click', '#' + id, function () {
        window.history.go(-1);
    });

    return '<a id="'+id+'" class="btn btn-default"><i class="fa fa-backward"></i> '+label+'</a>';
};

//</editor-fold>

//<editor-fold desc="对话框类">
Widgets.Dialogs = Widgets.Dialogs || {};

Widgets.Dialogs._base = function(title, message, options){

    var dialog = BootstrapDialog.show($.extend(true, {
        title: title,
        message: message,
        type: BootstrapDialog.TYPE_DEFAULT,
        buttons: [{
            cssClass: 'btn-info',
            label: '确定',
            action: function (dialog) {
                dialog.close();
            }
        }, {
            label: '取消',
            action: function (dialog) {
                dialog.close();
            }
        }]
    }, options));
};
/**
 * 确认对话框
 * @param title
 * @param message
 * @param callback
 * @param okLabel
 */
Widgets.Dialogs.confirm = function(title, message, callback, okLabel){

    okLabel = typeof(okLabel) == 'undefined' ? '确定' : okLabel;

    return Widgets.Dialogs._base(title, message, {
        type: BootstrapDialog.TYPE_WARNING,
        buttons: [{
        cssClass: 'btn-danger',
        label: okLabel,
        action: function (dialog) {
            dialog.close();
            callback();
        }
        }, {
            label: '取消',
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
};
/**
 * 确认删除某条信息
 *
 * @param url 删除地址
 * @param id 删除数据ID
 * @param callback 删除成功的回调
 * @param message  消息内容
 * @returns {*}
 */
Widgets.Dialogs.deleteConfirm = function(url, id, callback, message){

    message = typeof(message) == 'undefined' ? '确认删除该信息？' : message;

    return Widgets.Dialogs.confirm('删除信息', message, function(){
        var postData = {
            id: id,
            _method: "POST"
        };


        Public.ajaxPost(url, postData, function(event) {
            if(200 == event.code){
                Widgets.tips({
                    message: "CODE " + event.code + ': ' + event.msg,
                    type: 'success'
                });
                callback();
            }else {
                Widgets.tips({
                    message: "CODE " + event.code + ': ' + event.msg,
                    type: 'error'
                });
            }
        }, function(event){
            Widgets.tips({
                message: "CODE " + event.code + ': ' + event.msg,
                type: 'error'
            });
        });
    }, '删除');
};
/**
 * 自定义模态框
 *
 * @param title 模态框头
 * @param message 模态框内容
 * @param callback 回调
 * @param okLabel 确立按钮名
 * @returns {*}
 */
Widgets.Dialogs.custom = function(title, message, callback, okLabel){

    okLabel = typeof(okLabel) == 'undefined' ? '确定' : okLabel;

    return Widgets.Dialogs._base(title, message, {
        type: BootstrapDialog.TYPE_DEFAULT,
        buttons: [{
            cssClass: 'btn-info',
            label: okLabel,
            action: function (dialog) {
                dialog.close();
                callback();
            }
        }, {
            label: '取消',
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
};
/**
 * 图片上传模态框
 *
 * @param header 模态框头部
 * @param title 上传标题
 * @param info 提示信息
 * @param callback 回调
 * @param okLabel
 * @returns {*}
 */
Widgets.Dialogs.uploadImage = function(header, title, info,callback, okLabel){
    var token = $('meta[name="csrf-token"]').attr('content');
    var dropzoneStyle = $('#dz-style').val();
    var dropzoneJs = $('#dz-js').val();
    var dropzoneConfig = $('#dz-config').val();
    var uploadUrl = $('#upload-url').val();
    var userid = $('#user-id').val();
    var dropZoneInfo = '<ul style="margin-top: 50px">';
    for (var i = 0; i < info.length; i++) {
        dropZoneInfo += '<li>' + info[i] + '</li>';
    }
    dropZoneInfo += '</ul>';
    var html = '<link id="bootstrap" media="all" type="text/css" rel="stylesheet" href="' + dropzoneStyle + '">\
    <div class="container">\
        <div class="row">\
            <div class="col-md-offset-1 col-md-10">\
                <div class="jumbotron how-to-create">\
                    <h3>' + title + '<span id="photoCounter"></span></h3>\
                    <br>\
                    <form method="POST" action="' + uploadUrl + '" accept-charset="UTF-8" class="dropzone dz-clickable" id="dropzone" enctype="multipart/form-data">\
                        <div class="dz-message">\
                        </div>\
                        <div class="dropzone-previews" id="dropzonePreview"></div>\
                        <h4 style="text-align: center;color:#428bca;">可直接将图片拖到此处  <span class="fa fa-hand-o-down"></span></h4>\
                        <input type="hidden" name="_token" value="' + token + '">\
                        \<input type="hidden" name="id" value="' + userid + '">\
                    </form>'
                    + dropZoneInfo + '\
                </div>\
            </div>\
        </div>\
        <div id="preview-template" style="display: none;">\
            <div class="dz-preview dz-file-preview">\
                <div class="dz-image"><img data-dz-thumbnail=""></div>\
                <div class="dz-details">\
                    <div class="dz-size"><span data-dz-size=""></span></div>\
                    <div class="dz-filename"><span data-dz-name=""></span></div>\
                </div>\
                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>\
                <div class="dz-error-message"><span data-dz-errormessage=""></span></div>\
                <div class="dz-success-mark">\
                    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\
                        <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->\
                        <title>Check</title>\
                        <desc>Created with Sketch.</desc>\
                        <defs></defs>\
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\
                            <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>\
                        </g>\
                    </svg>\
                </div>\
                <div class="dz-error-mark">\
                    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\
                        <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->\
                        <title>error</title>\
                        <desc>Created with Sketch.</desc>\
                        <defs></defs>\
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\
                            <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">\
                                <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>\
                            </g>\
                        </g>\
                    </svg>\
                </div>\
            </div>\
        </div>\
    </div>\
    <script id="dropzone-js" src="' + dropzoneJs + '"></script>\
        <script id="dropzone-config" src="' + dropzoneConfig + '"></script>';
    Widgets.Dialogs.custom(header, html, callback, okLabel);
}