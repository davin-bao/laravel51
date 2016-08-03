/**
 * 角色编辑脚本
 */
var page = {
    urlParam: Public.urlParam(),
    urlParamId: -1,
    hasLoaded: false,

    saveFormDom: $("#save-form"),
    idDom: $('#id'),
    nameDom: $('#name'),
    displayNameDom: $('#display_name'),
    descriptionDom: $('#description'),

    toolBarDom: $('.tool-bar'),

    init: function(data){
        this.initDom(data), this.initValidator(), this.addEvent();
    },
    initDom: function(data){
        var self = this;
        self.idDom.val(data.id);
        self.nameDom.val(data.name);
        self.displayNameDom.val(data.display_name);
        self.descriptionDom.val(data.description);

        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons.save(self, 'save', 'admin/role/update', '保存', function(){
                window.location = Public.ROOT_URL + 'admin/role';
            }) +
            Widgets.OperateButtons.back(self)
        );
    },
    initValidator: function(){
        var self = this;
        self.saveFormDom.validate({
            rules: {
                name: {
                    required: ["角色英文标示"],
                    rangelength: [6, 30]
                },
                display_name: {
                    required: ["角色中文名称"],
                    rangelength: [6, 30]
                }
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
            display_name: self.displayNameDom.val(),
            description: self.descriptionDom.val()
        };
    },
    getOrigData: function(){
        return {
            id: 0,
            name: '',
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
            Public.ajaxGet("admin/role/edit/", {'id': page.urlParamId}, function(result) {
                200 === result.code ? (data = result.data, page.init(data), page.hasLoaded = !0) : (Widgets.tips({
                    type: 'error',
                    message: result.msg
                }))
            });
        }
    } else page.init(data);
});