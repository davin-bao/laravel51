var photo_counter = 0;
var dropz = new Dropzone("#dropzone", {

    url: this.action,
    uploadMultiple: false,
    maxFiles: 1,
    parallelUploads: 1,
    maxFilesize: 1,
    previewsContainer: '#dropzonePreview',
    previewTemplate: document.querySelector('#preview-template').innerHTML,
    addRemoveLinks: true,
    dictRemoveFile: '删除',
    dictFileTooBig: '图片大小需小于1M！',
    dictMaxFilesExceeded: '一次只能上传一张头像！',

    // The setting up of the dropzone
    init:function() {
        dropzone = this;

        this.on("removedfile", function(file) {

            $.ajax({
                type: 'POST',
                url: 'admin/static/delete',
                data: {id: file.name, _token: $('#csrf-token').val()},
                dataType: 'html',
                success: function(data){
                    var rep = JSON.parse(data);
                    if(rep.code == 200)
                    {
                        photo_counter--;
                        $("#photoCounter").text( "(" + photo_counter + ")");
                    }

                }
            });

        } );
    },
    error: function(file, response) {
        if($.type(response) === "string")
            var message = response; //dropzone sends it's own error messages in string
        else
            var message = response.msg;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        return _results;
    },
    success: function(file,done) {
        $.ajax({
            type: 'GET',
            url: '/admin/static/avatar?id=' + $('#user-id').val(),
            success: function(data) {
                if (data.code === 200) {
                    $('#user-left-box img').attr('src', Public.ROOT_URL + data.msg);
                    $('.profile-dropdown img').attr('src', Public.ROOT_URL + data.msg);
                    $('.main-box-body img').attr('src', Public.ROOT_URL + data.msg);
                } else {
                    Widgets.Dialogs.custom('设置头像', '获取头像失败！', function() {});
                }
            },
            error: function() {
                Widgets.Dialogs.custom('设置头像', '获取头像失败！', function() {});
            }
        });
        photo_counter++;
        $("#photoCounter").text( "(" + photo_counter + ")");
    }
});



