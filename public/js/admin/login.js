/**
 * 登录脚本
 */
var page = {

    loginFormDom: $("#login-form"),
    usernameDom: $('#username'),
    passwordDom: $('#password'),
    rememberMeDom: $('#remember-me'),

    toolBarDom: $('.tool-bar'),

    init: function(){
        //初始化公共方法
        Public.init(),
        //初始化 UI 组件
        Widgets.init(),
        this.initDom(),
        this.initValidator(),
        this.addEvent();
    },
    initDom: function(){
        var self = this;

        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons._button(self, 'login', 'login',  'LOGIN', function(){
                window.location = Public.ROOT_URL + 'admin';
            }, 'btn-success col-xs-12')
        );
    },
    initValidator: function(){
        var self = this;
        self.loginFormDom.validate({
            rules: {
                username: {
                    required: ["用户名"],
                },
                password: {
                    required: ["密码"],
                    rangelength: [6, 30]
                }
            },
            errorPlacement : function(error, element) {
                element.parent().addClass('has-error');
                $('#'+element.attr('id')+'-error').html(error.text());
            },success: function( error, element){
                $(element).parent().removeClass('has-error');
                $('#'+$(element).attr('id')+'-error').html('');
            }
        });
    },
    addEvent: function(){
        var self = this;

        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                window.event.keyCode=0;
                self.toolBarDom.find('#login').click();
                return false;
            }
        }
    },
    getPostData: function(){
        var self = this;
        return {
            username: self.usernameDom.val(),
            password: self.passwordDom.val(),
            remember_me: self.rememberMeDom.val(),
        };
    }
};

$(function() {
    page.init();
});