<?php

namespace App\Components;

use \Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

/**
 * MailHelper.
 *
 * 邮件帮助类
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class MailHelper
{
    /**
     * 给技术支持发送邮件
     *
     * @param 选择一个模板
     * @param 模板参数
     * @example
     * [
     *  'mail_to' => <recipient email address>,
     *  'recipient_name' => <recipient_name>,
     *  'mail_from' => <sender_address>,
     *  'sender_name' => <sender_name>,
     *  'subject' => <email_subject>
     * ]
     * @return bool 发送成功 | 发送失败
     */
    public static function noticeTechSupport(\Exception $exception)
    {
        $data = [
            'to_address'=> Config::get('mail.tech_support.address'),
            'to_name'=> Config::get('mail.tech_support.address'),
            'from_address'=> Config::get('mail.from.address'),
            'from_name'=> Config::get('mail.from.address'),
            'subject'=> Config::get('mail.tech_support.title'),

            'code'=> $exception->getCode(),
            'content'=> $exception->getMessage(),
            'trace'=> str_ireplace("\n", "<br/>", $exception->getTraceAsString())
        ];
//die;
        return self::send('Admin::emails.exception_notice', $data);
    }

    /**
     * 发送邮件
     * @param $template 使用的模板
     * @param $data 发送的地址 及 模板的内容
     * @return bool
     */
    public static function send($template, $data){

        // Send admin email
        Mail::send($template, $data, function($msg) use ($data) {
            $msg->to($data['to_address'], $data['to_name']);
            $msg->from($data['from_address'], $data['from_name']);
            $msg->subject($data['subject']);
        });

        // Check for email failures
        if (count(Mail::failures()) > 0) {
            return false;
        }

        return true;
    }
}