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
/**
 * 确认对话框
 * @param title
 * @param message
 * @param callback
 * @param okLabel
 */
Widgets.Dialogs.confirm = function(title, message, callback, okLabel){

    okLabel = typeof(okLabel) == 'undefined' ? '确定' : okLabel;

    var dialog = BootstrapDialog.show({
        title: title,
        message: message,
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
}
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
            id: index,
            _method: "POST"
        };

        dialog.close();

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
}