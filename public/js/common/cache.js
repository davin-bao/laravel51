/**
 * 前端缓存
 * 如果浏览器不支持 localSotrage，则不会缓存，每次都从服务器中拉数据
 *
 * @author davin.bao
 * @since 2016/7/26 9:34
 */
var Cache = Cache || {};

var Cache = {
    item: [],
    getInstance: function(){
        return sessionStorage;  //会话存储
        //return localStorage;    //本地存储
    },
    /**
     * 判断是否支持localStorage
     *
     * @returns {boolean}
     */
    supportStorage: function() {
        try {
            if(window.localStorage){
                return true;
            }
        } catch (e) {
        }
        return false;
    },

    set: function(key, value){
        throw new Error('不允许使用， 不支持localStorage的浏览器会丢失数据');
    },

    get: function(key){
        var value = Cache._get(key);

        if (typeof(value) == "undefined" || value === null) {
            value = Cache.flush(key);
        }
        //尝试JSON parse 如果失败，则存储的数据为字符串
        try{
            return JSON.parse(value);
        } catch(e){}
        return (value === null) ? undefined : value;
    },

    _set: function(key, value){
        key = key.toUpperCase();
        if(Cache.supportStorage()){
            var cache = Cache.getInstance();
            cache.setItem(key, value);
        }else{
            Cache.item[key] = value;
        }
        return true;
    },

    _get: function(key){
        var value = undefined;
        key = key.toUpperCase();

        if(Cache.supportStorage()) {
            var cache = Cache.getInstance();
            value = cache.getItem(key);
        }else{
            value = Cache.item[key];
        }
        return value;
    },
    /**
     * 注册一个可主动获取远程数据的缓存
     * @param key
     * @param uri  地址
     * @param params  参数
     */
    register: function(key, uri, params){
        var sourceUriKey = 'SOURCE_URI_' + key;
        Cache._set(sourceUriKey, uri);
        (typeof(params) == "undefined") && (params = {});

        var sourceParamsKey = 'SOURCE_PARAMS_' + key;
        Cache._set(sourceParamsKey, params);

        Cache.forget(key);
    },

    flush: function(key){
        var sourceUriKey = 'SOURCE_URI_' + key, sourceParamsKey = 'SOURCE_PARAMS_' + key;
        var uri = Cache._get(sourceUriKey.toUpperCase()), sourceParams = Cache._get(sourceParamsKey.toUpperCase()), value = null;
        if(typeof(uri) == "undefined" ||uri === null) return undefined;

        $.ajax({
            type: "GET",
            url: Public.ROOT_URL + uri,
            dataType: "json",
            data: sourceParams,
            async: false,
            mode: "limit",
            success: function(data, status){
                value = JSON.stringify(data.data);
            },
            error: function(err){
                errCallback && errCallback(err);
            }
        });

        Cache._set(key, value);
        return value;
    },

    forget: function(key){
        key = key.toUpperCase();
        if(Cache.supportStorage()) {
            var cache = Cache.getInstance();
            cache.removeItem(key);
        }else{
            Cache.item[key] = undefined;
        }
        return true;
    }
};