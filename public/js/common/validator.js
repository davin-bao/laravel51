/**
 * 验证器 全局配置及自定义验证方法
 *
 * @author davin.bao
 * @since 2016/7/25 10:34
 */
$(function() {
    /* Global configuration
     */
    if(typeof($.validator) == 'undefined') return;
    $.validator.addMethod("username", function(a) {
        return !a && a.length == 0 || /^\w{6,30}$/.test(a)
    });
    $.validator.addMethod("letters", function(a) {
        return !a && a.length == 0 || /^[a-z]+$/i.test(a)
    });
    $.validator.addMethod("time", function(a) {
        return !a && a.length == 0 || /^([01]\d|2[0-3])(:[0-5]\d){1,2}$/.test(a)
    });
    $.validator.addMethod("qq", function(a) {
        return !a && a.length == 0 || /^[1-9]\d{4,}$/.test(a)
    });
    $.validator.addMethod("IDcard", function(a) {
        return !a && a.length == 0 || /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/.test(a)
    });
    $.validator.addMethod("tel", function(a) {
        return !a && a.length == 0 || /^(?:(?:0\d{2,3}[\- ]?[1-9]\d{6,7})|(?:[48]00[\- ]?[1-9]\d{6}))$/.test(a)
    });
    $.validator.addMethod("mobile", function(a) {
        return !a && a.length == 0 || /^1[3-9]\d{9}$/.test(a)
    });
    $.validator.addMethod("mobileExt", function(a) {
        return !a && a.length == 0 || /^\(\d{3}\)\ 1[3-9]\d-\d{4}\ \x\d{4}$/.test(a)
    });
    $.validator.addMethod("zipcode", function(a) {
        return !a && a.length == 0 || /^\d{6}$/.test(a)
    });
    $.validator.addMethod("chinese", function(a) {
        return !a && a.length == 0 || /^[\u0391-\uFFE5]+$/.test(a)
    });
    $.validator.addMethod("password", function(a) {
        return !a && a.length == 0 || /^[\S]{6,16}$/.test(a)
    });
    $.validator.addMethod("equalTo", function (value, targetInput, checkParameters) {
        var sourceInputId = checkParameters;
        if(toString.apply(checkParameters) === '[object Array]'){
            sourceInputId = checkParameters[0];
        }
        var equalInput = $(sourceInputId);
        return this.settings.onfocusout && equalInput.unbind(".validate-equalTo").bind("blur.validate-equalTo", function () {
            $(targetInput).valid()
        }), value === equalInput.val()
    });
    $.validator.addMethod( "notEqualTo", function( value, element, param ) {
        return this.optional(element) || !$.validator.methods.equalTo.call( this, value, element, param );
    }, "请输入一个不同的值." );

    $.validator.addMethod("nowhitespace", function(value, element) {
        return this.optional(element) || /^\S+$/i.test(value);
    }, "不允许输入空格.");

    $.validator.addMethod("checked", function (value, target) {
        var min = (target.attributes['min-check'] !== undefined) ? parseInt(target.attributes['min-check'].value) : 0;
        var max = (target.attributes['max-check'] !== undefined) ? parseInt(target.attributes['max-check'].value) : 9999;
        var length = $('input[name^='+target.name.replace(/\[\d\]/,"")+']:checked').length;

        return length >= min && length <= max;
    });
    $.validator.addMethod("integer", function(value, element) {
        return this.optional(element) || /^-?\d+$/.test(value);
    }, "请输入一个正、负整数");

    $.validator.addMethod("ipv4", function(value, element) {
        return this.optional(element) || /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value);
    }, "请输入有效的 IP v4 地址.");

    $.validator.addMethod("ipv6", function(value, element) {
        return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
    }, "请输入有效的 IP v6 地址.");
    $.validator.addMethod("time", function(value, element) {
        return this.optional(element) || /^([01]\d|2[0-3]|[0-9])(:[0-5]\d){1,2}$/.test(value);
    }, "请输入有效的时间, 在 00:00 与 23:59 之间");

    $.validator.addMethod("time12h", function(value, element) {
        return this.optional(element) || /^((0?[1-9]|1[012])(:[0-5]\d){1,2}(\ ?[AP]M))$/i.test(value);
    }, "请输入有效的12小时制时间, 如 05:32 am/pm");
    /**
     * 自定义正则验证
     */
    $.validator.addMethod("pattern", function(value, element, param) {
        if (this.optional(element)) {
            return true;
        }
        if (typeof param === "string") {
            param = new RegExp("^(?:" + param + ")$");
        }
        return param.test(value);
    }, "格式无效.");

    $.validator.messages = {
        digits: "请输入数字",
        username: "请输入6-30位数字、字母、下划线",
        letters: "请输入字母",
        time: "请输入有效的时间，00:00到23:59之间",
        email: "请输入有效的邮箱",
        url: "请输入有效的网址",
        qq: "请输入有效的QQ号",
        IDcard: "请输入正确的身份证号码",
        tel: "请输入有效的电话号码",
        mobile: "请输入有效的手机号",
        mobileExt: "请输入有效的手机号,如 (086) 138-1234 x5678",
        zipcode: "请检查邮政编码格式",
        chinese: "请输入中文字符",
        password: "请输入6-16位字符，不能包含空格",
        fallback: "{0}格式不正确",
        loading: "正在验证...",
        error: "网络异常",
        timeout: "请求超时",
        required: "{0}不能为空",
        equalTo: "{1}与{2}不一致",
        remote: "{0}已被使用",
        rangelength: "长度必须在{0}与{1}之间",
        integer: {
            '*': "请填写整数",
            '+': "请填写正整数",
            '+0': "请填写正整数或0",
            '-': "请填写负整数",
            '-0': "请填写负整数或0"
        },
        match: {
            eq: "{0}与{1}不一致",
            neq: "{0}与{1}不能相同",
            lt: "{0}必须小于{1}",
            gt: "{0}必须大于{1}",
            lte: "{0}不能大于{1}",
            gte: "{0}不能小于{1}"
        },
        range: {
            rg: "请填写{1}到{2}的数",
            gte: "请填写不小于{1}的数",
            lte: "请填写最大{1}的数"
        },
        checked: {
            eq: "请选择{1}项",
            rg: "请选择{1}到{2}项",
            gte: "请至少选择{1}项",
            lte: "请最多选择{1}项"
        },
        length: {
            eq: "请填写{1}个字符",
            rg: "请填写{1}到{2}个字符",
            gte: "请至少填写{1}个字符",
            lte: "请最多填写{1}个字符",
            eq_2: "",
            rg_2: "",
            gte_2: "",
            lte_2: ""
        }
    };

});