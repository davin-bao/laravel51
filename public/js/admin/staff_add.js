/**
  * 管理员编辑脚本
  */
var page = {
        urlParam: Public.urlParam(),
        urlParamId: -1,
        allRoleList:[],
        hasLoaded: false,

        saveFormDom: $("#save-form"),
        idDom: $('#id'),
        nameDom: $('#name'),
        sel2MultiDom: $('#sel2Multi'),
        mobileDom: $('#mobile'),
        userNameDom: $('#username'),
        emailDom: $('#email'),
        passwordDom: $('#password'),
        toolBarDom: $('.tool-bar'),

        init: function(data){
            this.initDom(data), this.initValidator(), this.addEvent();
        },
    initDom: function(data){
        var self = this;

        self.mobileDom.mask("(999) 999-9999? x9999");

        self.sel2MultiDom.select2({
            placeholder: '请选择角色',
            allowClear: true
        });

        var select2 = new Selector.select2(self.sel2MultiDom, {dataType: 'serverSide', data: 'admin/role/list/'});
        select2.val(data.roles);

        self.idDom.val(data.id);
        self.nameDom.val(data.name);
        self.userNameDom.val(data.username);
        self.emailDom.val(data.email);
        self.mobileDom.val(data.mobile);
        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons.save(self, 'save', 'admin/staff/update', '保存', function(){
                window.location = Public.ROOT_URL + 'admin/staff';
            }) +
            Widgets.OperateButtons.back(self)
        );

        //判断是否为编辑场景
        if(data.id > 0){
            self.passwordDom.val("CantEditPwdHere");
            self.userNameDom.addClass('disabled');
            self.passwordDom.addClass('disabled');
        }

    },
    initValidator: function(){
        var self = this;
        self.saveFormDom.validate({
            rules: {
                name: {
                    required: ["用户名"],
                    rangelength: [1, 20]
                },
                username: {
                    required: ["用户账号"],
                    rangelength: [6, 30]
                },
                password: {
                    required: ["密码"],
                    rangelength: [6, 30]
                },
                mobile: {
                    mobileExt: ["手机"],
                    rangelength: [7, 20]
                },
                email: {
                    required: ["邮箱"],
                    rangelength: [6,30],
                    email: ['邮箱']
                },
                roles:{
                    required: ["角色"]
                },

            },
            errorClass: "has-error"
        });
    },
    addEvent: function(){},
    getPostData: function(){
        var self = this;
        return {
                id: self.idDom.val(),
                name: self.nameDom.val(),
                username: self.userNameDom.val(),
                email: self.emailDom.val(),
                password: self.passwordDom.val(),
                roles: self.sel2MultiDom.val(),
                mobile: self.mobileDom.val()
        };
    },
    getOrigData: function(){
        return {
                id: 0,
                name: '',
                username: '',
                email: '',
                password: '',
                roles: '',
                mobile:''
        };
    },
};
    $(function() {
        var data = page.getOrigData();
        page.urlParamId = page.urlParam.id ? page.urlParam.id : -1;

        if (page.urlParamId != -1) {
            if (!page.hasLoaded) {
                Public.ajaxGet("admin/staff/edit/", {'id': page.urlParamId}, function (result) {
                    200 === result.code ? (data = result.data, page.init(data), page.hasLoaded = !0) : (Widgets.tips({
                        type: 'error',
                        message: result.msg
                    }))
                });
            }
        } else page.init(data);
    });