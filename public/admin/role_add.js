/**
 * 角色编辑脚本
 */
var page = {
    urlParam: Public.urlParam(),
    urlParamId: -1,
    hasLoaded: false,
    nameDom: $('#name'),
    displayNameDom: $('#display_name'),
    descriptionDom: $('#description'),

    toolBarDom: $('tool_bar'),

    init: function(data){
        this.initDom(data), this.addEvent();
    },
    initDom: function(data){
        var self = this;
        self.nameDom.val(data.name);
        self.displayNameDom.val(data.display_name);
        self.descriptionDom.val(data.description);
        //TODO 封装 Button
        self.toolBarDom.add(Public.saveButton('admin/role/edit', function(result){
            200 === result.code ? (parent.Public.tips({
                type: 'success',
                message: result.msg,
                onClose : function() {
                    //关闭
                    window.location = Public.ROOT_URL() + '/admin/role';
                }
            })) : (parent.Public.tips({
                type: 'error',
                message: result.msg
            }))
        }));
    },
    addEvent: function(){},
    getPostData: function(){
        var self = this;
        return {
            name: self.nameDom.val(),
            display_name: self.displayNameDom.val(),
            description: self.descriptionDom.val()
        };
    },
    getOrigData: function(){
        return {
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
            Public.ajaxGet("/admin/role/edit/", {'id': page.urlParamId}, function(result) {
                200 === result.code ? (data = result, page.init(data), page.hasLoaded = !0) : (parent.Public.tips({
                    type: 'error',
                    message: result.msg
                }))
            });
        }
    } else page.init(data);
});