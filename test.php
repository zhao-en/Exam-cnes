<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/6
 * Time: 17:54
 */


$a = password_hash("HelloWorld", PASSWORD_DEFAULT);

$t = time();

$c = md5($t);
$a = crypt(time(),'$1$swustnes$');
$l = strlen($a);



$id = crypt(md5($t),'$1$rasmusle$');
$id2 = md5($t);
$id = session_create_id('swustcnes');

try{
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    $redis->auth('swustcnes');
    $redis->hMset('zhaozhen',['age'=>20,'blood'=>'B','name'=>'赵震','actor'=>'计算机科学与技术' ]);
    $redis->expire('zhaozhen',60*10);
    $a = $redis->hGetAll('zhaozhen');
    $c = 0;

}catch (Exception $e){
    echo iconv('gbk','UTF-8',$e);
}
