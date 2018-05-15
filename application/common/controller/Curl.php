<?php
/**
 * Created by zhaozhen.
 * Desc:
 * Date: 2018/4/4
 * Time: 16:43
 */

namespace app\common\controller;


class Curl
{
    /**
     * @param $url string 请求地址
     * @param $params array  请求数据
     * @param string $method string 请求数据
     * @param array $header 请求头部
     * @param bool $multi 其他
     * @return mixed res and info
     */
    public static function http($url, $params, $method = 'GET', $header = array(), $multi = false)
    {
        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $header
        );
        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                return array('res'=>false , 'info'=>'不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            return array('res'=>false , 'info'=>'请求发生错误：' . $error);
        }
        return array('res'=>true , 'info'=>$data);
    }
}