<?php
namespace App\Components;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


/**
 *CurlHelper
 *
 * Curl帮助类
 *
 * @package App\Components
 *
 * @author chuanhangyu
 * @since 2016/7/26 14:00
 */
class CurlHelper
{

    // 发送的数据格式
    const REQUEST_JSON_DATA = '0';
    const REQUEST_QUERY_STRING_DATA = '1';

    // 日志信息头部
    const LOG_TYPE_REQUEST = 'REQ';
    const LOG_TYPE_RESPONSE = 'RES';

    // code长度
    const CURLHELPER_CODE_LENGTH = 10;

    /**
     * 日志记录器
     * @param $info
     * @param $type
     * @param $logCode
     * @param $httpCode
     */
    private static function log($info, $type, $logCode, $httpCode = null)
    {
        // 得到基础类名
        $class = basename(get_called_class());

        // 公共头部信息
        $message = $class. ' '. $type. ' '. $logCode. ' ';

        // 写响应日志时
        if ($type === self::LOG_TYPE_RESPONSE) {

            // 添加响应的http code，和响应信息
            $message .= $httpCode. ' ';
            $message .= unicodeDecode($info);
        } else {

            // 写请求日志时
            $message .= json_encode($info, JSON_UNESCAPED_UNICODE);
        }
        Log::info($message);
    }


    /**
     * 转换输出数据
     * @param $data
     * @param $dataType
     * @return string
     */
    private static function translateData($data, $dataType)
    {
        switch ($dataType) {
            case self::REQUEST_JSON_DATA :
                $data = json_encode($data);
                break;
            case  self::REQUEST_QUERY_STRING_DATA :
                $data = http_build_query($data);
                break;
        }
        return $data;
    }

    /**
     * 发送post方式的curl
     * @param $url
     * @param $data
     * @param $dataType
     * @param array $headers
     * @return mixed
     */
    public static function postCurl($url, $data, $dataType, $headers = array())
    {
        // 写访问日志
        $logCode = Str::random(CurlHelper::CURLHELPER_CODE_LENGTH);
        CurlHelper::log($data, CurlHelper::LOG_TYPE_REQUEST, $logCode);

        $ch = curl_init();

        // 合并头部信息
        if ($dataType === self::REQUEST_JSON_DATA) {
            $headers = array_merge(array('Content-Type:application/json; charset=utf-8', 'Accept:application/json'), $headers);
        }

        // 设置post请求方式和相关参数
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);

        // 设置post数据
        $data = CurlHelper::translateData($data, $dataType);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $info = curl_exec($ch);

        // 得到响应的http code
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 写响应日志
        CurlHelper::log($info, CurlHelper::LOG_TYPE_RESPONSE, $logCode, $httpCode);
        return $info;
    }

    /**
     * 发送get方式的curl
     * @param $url
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public static function getCurl($url, $data, $headers = array())
    {
        // 写访问日志
        $logCode = Str::random(CurlHelper::CURLHELPER_CODE_LENGTH);
        CurlHelper::log($data, CurlHelper::LOG_TYPE_REQUEST, $logCode);

        $ch = curl_init();

        // 拼接get方式的url
        $url .= '?';
        $url .= self::translateData($data, self::REQUEST_QUERY_STRING_DATA);

        // 设置相关参数
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        // 得到响应的http code
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $info = curl_exec($ch);
        curl_close($ch);

        // 写响应日志
        CurlHelper::log($info, CurlHelper::LOG_TYPE_RESPONSE, $logCode, $httpCode);
        return $info;
    }
}

