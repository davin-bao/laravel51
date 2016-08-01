
var page = {
    urlParamId: $('#user-id').val(),
    hasLoaded: false,

    saveFormDom: $("#save-form"),
    idDom: $('#user-id'),
    nameDom: $('#name'),
    emailDom: $('#email'),
    mobileDom: $('#mobile'),

    toolBarDom: $('.tool-bar'),

    init: function(data){
        this.initDom(data), this.initValidator(), this.addEvent();
    },
    initDom: function(data){
        var self = this;
        self.idDom.val(data.id);
        self.nameDom.val(data.name);
        self.emailDom.val(data.email);
        self.mobileDom.val(data.mobile);

        //添加操作按钮
        self.toolBarDom.html(
            Widgets.OperateButtons.save(self, 'save', 'admin/staff/update-info', '保存', function(){
                window.location = Public.ROOT_URL + 'admin/staff/profile';
            }) +
            Widgets.OperateButtons.back(self)
        );
    },
    initValidator: function(){
        var self = this;
        self.saveFormDom.validate({
            rules: {
                email: {
                    required: ["邮箱"],
                    email: [],
                    rangelength: [0, 30]
                },
                name: {
                    required: ["名字"],
                    rangelength: [0, 30]
                },
                mobile: {
                    required: ["手机号"],
                    number: [],
                    rangelength: [7, 20]
                },
            },
            messages: {
                mobile: {
                    number:'请输入正确手机号！',
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
            email: self.emailDom.val(),
            mobile: self.mobileDom.val()
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
    if (page.urlParamId != -1) {
        if (!page.hasLoaded) {
            Public.ajaxGet("admin/staff/edit/", {'id': page.urlParamId}, function(result) {
                200 === result.code ? (data = result.data, page.init(data), page.hasLoaded = !0) : (Widgets.tips({
                    type: 'error',
                    message: result.msg
                }))
            });
        }
    } else page.init(data);
});

$('.center-block').click(function () {
    var header = '无需保存即可设置';
    var title = '头像设置';
    var info = [
        '一拖到此处就可更换你的头像',
        '图片大小需小于1M'
    ];
    uploadAvatar(header, title, info, function () {}, '完成');
});

function uploadAvatar(header, title, info, callback, okLabel) {
    var dropZoneInfo = '<ul style="margin-top: 50px">';
    for (var i = 0; i < info.length; i++) {
        dropZoneInfo += '<li>' + info[i] + '</li>';
    }
    dropZoneInfo += '</ul>';

    Widgets.Dialogs.uploadImage(header, title, dropZoneInfo, callback, okLabel);
}