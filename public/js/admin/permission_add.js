/**
 * 权限编辑脚本
 */
var page = {
    urlParam: Public.urlParam(),
    urlParamId: -1,
    hasLoaded: false,

    saveFormDom: $("#save-form"),
    idDom: $('#id'),
    parentDom: $('#parent'),
    iconDom: $('#icon'),
    uriDom: $('#uri'),
    actionDom: $('#action'),
    displayNameDom: $('#display_name'),
    isMenuDom: $('#is_menu'),
    sortDom: $('#sort'),
    descriptionDom: $('#description'),

    toolBarDom: $('.tool-bar'),

    init: function(data){
        this.initDom(data), this.initValidator(), this.addEvent();
    },
    initDom: function(data){
        var self = this;

        self.idDom.val(data.id);
        self.parentDom.val(data.parent);
        self.iconDom.val(data.icon);
        self.uriDom.val(data.uri);
        self.actionDom.val(data.action);
        self.isMenuDom.val(data.is_menu).selecte;
        self.sortDom.val(data.sort);
        self.displayNameDom.val(data.display_name);
        self.descriptionDom.val(data.description);

        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons.save(self, 'save', 'admin/permission/update', '保存', function(){
                window.location = Public.ROOT_URL + 'admin/permission';
            }) +
            Widgets.OperateButtons.back(self)
        );
    },
    initValidator: function(){
        var self = this;
        self.saveFormDom.validate({
            rules: {
                parent: {
                    rangelength: [0, 255]
                },
                icon: {
                    rangelength: [0, 50]
                },
                uri: {
                    required: ["路径"],
                    rangelength: [0, 255]
                },
                action: {
                    required: ["URL地址对接的接口地址"],
                    rangelength: [0, 255]
                },
                display_name: {
                    rangelength: [0, 30]
                },
                description: {
                    rangelength: [0, 255]
                },
                is_menu: {
                    required: ["是否作为菜单显示"],
                    pattern: "[0,1]"
                },
                sort: {
                    required: ["排序"],
                    rangelength: [0, 127]
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
            parent:self.parentDom.val(),
            icon:self.iconDom.val(),
            uri:self.uriDom.val(),
            action:self.actionDom.val(),
            is_menu:self.isMenuDom.val(),
            sort:self.sortDom.val(),
            display_name: self.displayNameDom.val(),
            description: self.descriptionDom.val()
        };
    },
    getOrigData: function(){
        return {
            id: 0,
            parent:'',
            icon:'',
            uri:'',
            action:'',
            is_menu:0,
            sort:0,
            display_name: '',
            description: ''
        };
    },
};

$(function() {
    var data = page.getOrigData();
    page.urlParamId = page.urlParam.id ? page.urlParam.id : -1;
    if (page.urlParamId != -1) {
        if (!page.hasLoaded) {
            Public.ajaxGet("admin/permission/edit/", {'id': page.urlParamId}, function(result) {
                200 === result.code ? (data = result.data, page.init(data), page.hasLoaded = !0) : (Widgets.tips({
                    type: 'error',
                    message: result.msg
                }))
            });
        }
    } else page.init(data);
});