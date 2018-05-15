<?php
/**
 * Created by zhaozhen.
 * Desc:
 * Date: 2018/4/4
 * Time: 16:33
 */

namespace app\common\controller;


use think\Config;

class Email
{

    //邮件默认配置
    protected static $kk = 'sendEmail';
    protected static $username = 'swustcnes@163.com';
    protected static $password = 'zhaozhen999';
    protected static $host = 'smtp.163.com';
    protected static $port = '465';
    protected static $servername = 'swustcnes';
    protected static $url = 'http://email.dev.tansuyun.cn/smtp.php';

    /**
     * @param $object string 邮件标题
     * @param $content string 邮件内容
     * @param $address string 邮件收件人
     * @return mixed
     */
    public static function sendMail($object, $content, $address)
    {
        $post_params = array(
            'kk'=>Email::$kk,
            'username'=>Email::$username,
            'password'=>Email::$password,
            'host'=>Email::$host,
            'port'=>Email::$port,
            'servername'=>Email::$servername,
            'object'=>$object,
            'content'=>$content,
            'address'=>$address,
        );
        return Curl::http(Email::$url,$post_params,'post');
    }

}