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

Widgets.OperateButtons._button = function($_obj, id, dataRight, label, callback){
    if(true){
        label = typeof(label) === 'undefined' ? '保存' : label;

        $_obj.toolBarDom.on('click', '#' + id, function(){
            var paramData = $_obj.getPostData(), url = '/' + dataRight;
            if(!paramData) return;

            Public.ajaxPost(url, paramData, function(event) {
                (200 == event.code) ? (Widgets.tips({
                    type: "success",
                    message: event.msg,
                    onClose : function() {
                        typeof(callback) !== 'undefined' && callback();
                    }
                })) : Widgets.tips({
                    type: "error",
                    message: event.msg
                });
            });
        });

        return '<a id="'+id+'" class="btn btn-success">'+label+'</a>';
    }
    return '';
}
Widgets.OperateButtons.save = function($_obj, id, dataRight, label, callback){
    return Widgets.OperateButtons._button($_obj, id, dataRight, label, callback);
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
