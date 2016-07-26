//UI 小组件
var Widgets = Widgets || {};

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

Widgets.OperateButtons._button = function($_obj, id, url, label, callback){
    if(true){
        label = typeof(label) === 'undefined' ? '保存' : label;
        var formDom = $('#save-form');

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
                        var returnData = event.target.responseText;
                        if(returnData == null)  break;
                        var data = $.parseJSON(returnData);
                        if(typeof($.parseJSON(returnData)) != "object") break;
                        $.each(data,function(n,value) {
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

        return '<a id="'+id+'" class="btn btn-success">'+label+'</a>';
    }
    return '';
}
Widgets.OperateButtons.save = function($_obj, id, url, label, callback){
    return Widgets.OperateButtons._button($_obj, id, url, label, callback);
};
Widgets.OperateButtons.back = function($_obj, id, label){
    id = typeof(id) === 'undefined' ? 'back' : id;
    label = typeof(label) === 'undefined' ? '返回' : label;

    $_obj.toolBarDom.on('click', '#' + id, function () {
        window.history.go(-1);
    });

    return '<a id="'+id+'" class="btn btn-default">'+label+'</a>';
};

//</editor-fold>
