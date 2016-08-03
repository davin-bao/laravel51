<?php
/**
 * 自定义全局方法
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */

/**
 * 记录操作日志
 * @param string $content
 */
function operateLog($content = ''){
    $admin = \Auth::admin()->get();
    $attributes = [
        'admin_id'  => ($admin ? $admin->id : 0),
        'name'      => ($admin ? $admin->name : ""),
        'ip'        => Request::getClientIp(),
        'log'       => $content,
        'created_at'    => date('Y-m-d H:i:s')
    ];

    \App\Models\Log::create($attributes);
}

/**
 * 格式化开始日期
 * @param $beginDate
 * @return string
 */
function formatBeginDate($beginDate){
    if(!($beginDate == '' || $beginDate === -1 || $beginDate === '-1'|| $beginDate == null || $beginDate === 'undefined')) {
        return strlen($beginDate)<=10 ? trim($beginDate) . ' 00:00:00' : $beginDate;
    }
    return date('0000-00-00 00:00:00');
}

/**
 * 格式化结束日期
 * @param $endDate
 * @return string
 */
function formatEndDate($endDate){
    if(!($endDate == '' || $endDate === -1 || $endDate === '-1'|| $endDate == null || $endDate === 'undefined')) {
        return strlen($endDate)<=10 ? trim($endDate) . ' 23:59:59' : $endDate;
    }
    return date('Y-m-d h:i:s',strtotime('+12 month'));
}

function adminAction($action){
    return action('\App\Modules\Admin\Controllers\\'.$action);
}

/**
 * unicode转utf8
 * @param $uniStr
 * @return string
 *
 * @author chuanhangyu
 * @since 2016/7/28/ 11:30
 */
function unicodeDecode($uniStr) {
    $pattern = '/([^\\\u]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $uniStr, $matches);
    if (!empty($matches)) {
        $deStr = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0) {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code) . chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $deStr .= $c;
            } else {
                $deStr .= $str;
            }
        }
    }
    return $deStr;
}