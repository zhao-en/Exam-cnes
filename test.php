<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/6
 * Time: 17:54
 */

header('content-type:text/html;charset=utf-8');
try{
    //throw new Exception("哈哈哈哈");
    $redis = new redis();
    $redis->connect('32.36.236.214');
    echo '000';
}
catch (mysqli_sql_exception $e){
    $a = 0;
} catch (Exception $e){
    $err  = iconv('GBK','utf-8', $e->getMessage());
    echo $e->getMessage();
}
