<?php
namespace App\Components;

use Illuminate\Http\Exception\HttpResponseException;
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

    const CURLHELPER_JSON_DATA = '0';
    const CURLHELPER_QUERY_STRING_DATA = '1';

    const CURLHELPER_BEFORE_LOG = 'Curl-Log-Request';
    const CURLHELPER_AFTER_LOG = 'Curl-Log-Response';

    const CURLHELPER_CODE_LENGTH = 10;

    /**
     * 日志记录器
     * @param $message
     * @param $header
     */
    private static function log($message, $header)
    {
        Log::info($header, $message);
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
            case self::CURLHELPER_JSON_DATA :
                $data = json_encode($data);
                break;
            case  self::CURLHELPER_QUERY_STRING_DATA :
                $data = http_build_query($data);
                break;
        }
        return $data;
    }

    /**
     * Unicode编码解码
     * @param $uniStr
     * @return string
     */
    public static function unicode_decode($uniStr)
    {
        $pattern = '/([\w"{:\ }\[\]]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $uniStr, $matches);
        if (!empty($matches))
        {
            $uniStr = '';
            for ($j = 0; $j < count($matches[0]); $j++)
            {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0)
                {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code).chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $uniStr .= $c;
                }
                else
                {
                    $uniStr .= $str;
                }
            }
        }
        return $uniStr;
    }

    /**
     * 发送post方式的curl
     * @param $url
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public static function postCurl($url, $data, $dataType, $headers = array())
    {
        // 写访问日志
        $code = Str::random(CurlHelper::CURLHELPER_CODE_LENGTH);
        $logMessage = ['code' => $code, 'msg' => $data];
        CurlHelper::log($logMessage, CurlHelper::CURLHELPER_BEFORE_LOG);

        $ch = curl_init();

        // 合并头部信息
        if ($dataType === self::CURLHELPER_JSON_DATA) {
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
        curl_close($ch);

        // 写响应日志
        $logMessage = ['code' => $code, 'msg' => self::unicode_decode($info)];
        CurlHelper::log($logMessage, CurlHelper::CURLHELPER_AFTER_LOG);
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
        $code = Str::random(CurlHelper::CURLHELPER_CODE_LENGTH);
        $logMessage = ['code' => $code, 'msg' => $data];
        CurlHelper::log($logMessage, CurlHelper::CURLHELPER_BEFORE_LOG);

        $ch = curl_init();

        // 合并头部信息
        $headers = array_merge(array('Content-Type:application/json; charset=utf-8', 'Accept:application/json'), $headers);

        // 拼接get方式的url
        $url .= '?';
        $url .= self::translateData($data, self::CURLHELPER_QUERY_STRING_DATA);

        // 设置相关参数
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $info = curl_exec($ch);
        curl_close($ch);

        // 写响应日志
        $logMessage = ['code' => $code, 'msg' => self::unicode_decode($info)];
        CurlHelper::log($logMessage, CurlHelper::CURLHELPER_AFTER_LOG);
        return $info;
    }
}

