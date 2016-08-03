/**
 * 权限分配脚本
 */
var page = {
    urlParam: Public.urlParam(),
    urlParamId: -1,
    allPermission:[],
    hasLoaded: false,

    saveFormDom: $("#save-form"),
    permissionListDom: $('#permission-list'),
    idDom: $('#id'),
    roleNameDom: $('#role-name'),
    toolBarDom: $('.tool-bar'),

    init: function(data){
        this.initDom(data), this.addEvent();
    },
    initDom: function(data){
        var self = this;

        // 初始化标题-显示正在编辑的角色名
        self.roleNameDom.html(data.name);

        // 初始化所有权限列表
        self.initPermissionListDom(data);

        // 初始化id输入框的值
        self.idDom.val(data.id);

        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons.save(self, 'save', 'admin/role/permission-list', '保存', function(){
                window.location = Public.ROOT_URL + 'admin/role';
            }) +
            Widgets.OperateButtons.back(self)
        );

    },
    addEvent: function(){
        var self = this;

        // 为顶级权限最小化图标配置点击事件
        // 当图标的class中有fa-minus时，点击则最小化子权限，反之则最大化
        self.permissionListDom.on('click', '.display-sub-permission-toggle', function () {
            if ($(this).children('span').attr('class').indexOf('fa-minus') >= 0) {
                $(this).children('span').removeClass('fa-minus').addClass('fa-plus')
                    .parents('.top-permission').next('.sub-permissions').hide();
            } else {
                $(this).children('span').removeClass('fa-plus').addClass('fa-minus')
                    .parents('.top-permission').next('.sub-permissions').show();
            }
        });

        // 为顶级权限和子选项配置chang事件
        // 当勾选或取消顶级权限时，自动勾选或取消对应的子权限
        self.permissionListDom.on('change', '.top-permission-checkbox', function () {
            $(this).parents('.top-permission').next('.sub-permissions').find('input').prop('checked', $(this).prop('checked'));
        });
        // 当选择子权限时，自动勾选对于的顶级权限
        self.permissionListDom.on('change', '.sub-permission-checkbox', function () {
            if ($(this).prop('checked')) {
                $(this).parents('.sub-permissions').prev('.top-permission').find('.top-permission-checkbox').prop('checked', true);
            }
        });
    },
    getPostData: function(){
        var self = this;

        // 拼接勾选的所有权限id

        var permissions = '';
        // 得到所有权限DOM数组
        var permissionsDom = document.getElementsByName('permissions');
        // 循环得到所有勾选的权限的id值，并拼接成字符串
        for(var i = 0; i < permissionsDom.length; i++){
            if(permissionsDom[i].checked)
                permissions += permissionsDom[i].value + ',';
        }
        // 去掉最后一个多余的','
        permissions = permissions.substring(0, permissions.length - 1);

        return {
            id: self.idDom.val(),
            permissions: permissions,
        };
    },
    getOrigData: function(){
        return {
            permissions: '',
        };
    },

    // 初始化所有权限列表
    initPermissionListDom: function(data){
        var self = this;

        // 循环所有顶级权限，只取拥有display_name的权限
        for(var i = 0; i < self.allPermission.length; i++){
            if (self.allPermission[i].parent === null && self.allPermission[i].display_name !== null) {
                var topCheck = '';

                // 匹配当前角色已拥有的顶级权限
                for (var k = 0; k < data.permissions.length; k++) {
                    if (data.permissions[k].id === self.allPermission[i].id) {
                        topCheck = 'checked';
                        break;
                    }
                }

                // 拼装顶级权限html
                self.permissionListDom.append('<div class="top-permission col-md-12">\
                <a href="javascript:;" class="display-sub-permission-toggle">\
                <span class="fa fa-minus"></span>\
                </a>\
                <input type="checkbox" name="permissions" value="' + self.allPermission[i].id + '" class="top-permission-checkbox" ' + topCheck + '>\
                <label><h5>&nbsp;&nbsp;' + self.allPermission[i].display_name + '</h5></label></div>');

                // 如果该顶级权限拥有子权限，创建子权限html容器
                if (self.allPermission[i].sub_permission !== null && self.allPermission[i].sub_permission.length !== 0) {
                    self.permissionListDom.append('<div class="sub-permissions col-md-11 col-md-offset-1" id="sub-' + i + '"></div>');
                }

                // 循环子权限，只取拥有display_name的权限
                for (var j = 0; self.allPermission[i].sub_permission !== null && j < self.allPermission[i].sub_permission.length; j++) {
                    if (self.allPermission[i].sub_permission[j].display_name !== null && self.allPermission[i].sub_permission[j].display_name !== '') {
                        var subCheck = '';

                        // 匹配当前角色拥有的子权限
                        for (var k = 0; k < data.permissions.length; k++) {
                            if (data.permissions[k].id === self.allPermission[i].sub_permission[j].id) {
                                subCheck = 'checked';
                                break;
                            }
                        }

                        // 拼装子权限html到对应的子权限容器中
                        $('<div class="col-sm-3"><label><input type="checkbox" name="permissions" value="' + self.allPermission[i].sub_permission[j].id + '" class="sub-permission-checkbox" ' + subCheck + '>&nbsp;&nbsp;'
                            + self.allPermission[i].sub_permission[j].display_name +
                            '</label></div>').appendTo('#sub-' + i);
                    }
                }
            }
        }
    }
};
$(function() {
    var data = page.getOrigData();
    page.urlParamId = page.urlParam.id ? page.urlParam.id : -1;

    // 异步取得所有权限
    Public.ajaxGet("admin/permission/list/", {}, function(result) {
        if(200 === result.code){
            page.allPermission = result.data;

            if (page.urlParamId != -1) {
                if (!page.hasLoaded) {

                    // 异步取得当前角色所有权限
                    Public.ajaxGet("admin/role/permission-list/", {'id': page.urlParamId}, function(result) {

                        // 两次异步请求都成功时，初始化页面
                        200 === result.code ? (data = result.data, page.init(data), page.hasLoaded = !0) : (Widgets.tips({
                            type: 'error',
                            message: result.msg
                        }));
                    }, function (result) { // 当请求发生错误时

                        // 当发生错误的原因为无权限时
                        if (result.status == 403) {
                            Widgets.tips({
                                type: 'error',
                                message: '无此权限！'
                            });
                        } else {
                            Widgets.tips({
                                type: 'error',
                                message: '访问错误！'
                            });
                        }
                    });
                }
            } else page.init(data);

        }else{
            Widgets.tips({
                type: 'error',
                message: result.msg
            });
        }
    }, function (event) { // 当请求发生错误时
        Widgets.tips({
            type: "error",
            message: event.msg
        });
    });


});