/**
 * 选择器
 * @type {{}}
 */
var Selector = Selector || {};
/**
 * select2 自动填充下来菜单
 * @param $_obj select 控件
 * @param options 选项 { data: {}}
 * @returns {Selector}
 */
Selector.select2 = function ($_obj, options) {
    this._obj = $_obj;
    this._data = [];
    this.options = options;
    var self = this;
    if (options.dataType=='clientSide' && typeof(options.data)!== 'undefined') {
        this._data = options.data;

        var i = 1;
        for (var key in this._data) {
            if (isArray(this._data[key])) {
                $_obj.append('<optgroup label="' + key + '" id="zone-' + i + '"></optgroup>')
                for (var j = 0; j < this._data[key].length; j++) {
                    $('#zone-' + i).append('<option value="' + this._data[key][j] + '">' + this._data[key][j] + '</option>');
                }
                i++;
            }
        }

        // 设置时区select2插件
        $($_obj).select2({
            placeholder: '请选择时区',
            allowClear: true
        });
    }else {
        Public.ajaxGet(options.data, {async: false}, function (result) {
            if (200 === result.code) {
                self._data = result.data;
            } else {
                Widgets.tips({
                    type: 'error',
                    message: result.msg
                })
            }
        });

        for(var i = 0;i < this._data.length; i++){
            this._obj.append('<option value="' + this._data[i].id + '">' + this._data[i].name + '</option>');
        }

        // 设置管理员选择角色的select2插件
        $($_obj).select2({
            placeholder: '请选择角色',
            allowClear: true
        });
    }



    return this;
};
/**
 * 读写 select2 控件值
 * @param value
 * @returns {*}
 */
Selector.select2.prototype.val = function(value) {
    var chosenDom = this._obj.siblings('.select2-container').find('.select2-chosen');
    if((this.options.dataType == 'clientSide' && typeof(value) !== 'undefined')){
        return (typeof(chosenDom) !== 'undefined') && chosenDom.html(value) && this._obj.val(value);
    } else {
        var roleIds = []
        for(var i = 0;i < value.length; i++){
            roleIds.push(value[i].id);
        }

        return this._obj.val(roleIds).trigger('change');
    }
    return chosenDom.val();
};