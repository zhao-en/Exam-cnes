<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/6
 * Time: 16:42
 */

namespace app\common\controller;


class Redisc
{


    protected static $auth = "swustcnes";
    protected static $host = "127.0.0.1";
    protected static $port = "6379";

    protected static function connect(){
        $redis = new \Redis();
        $redis->connect(Redisc::$host,Redisc::$port);
        $redis->auth(Redisc::$auth);
        return $redis;
    }

    public static function set($key , $val , $expire = 600){

        return Redisc::connect()->setex($key,$expire,$val);
    }

    public static function get($key){
        return Redisc::connect()->get($key);
    }

    public static function del($key){
        return Redisc::connect()->del($key);
    }

    public static function expire($key , $expire = 600){
        return Redisc::connect()->expire($key,$expire);
    }

    public static function hMSet($key,$array,$expire = 600){
        $redis = Redisc::connect();
        return $redis->hMset($key,$array) && $redis->expire($key,$expire);

    }

    public static function Incr($key){
        $redis = Redisc::connect();
        return $redis->incr($key);
    }

    public static function hGetAll($key){
        $redis = Redisc::connect();
        return $redis->hGetAll($key);
    }

    public static function existKey($key){
        $redis = Redisc::connect();
        return $redis->exists($key);
    }

    public static function hExistKey($key){
        $redis = Redisc::connect();
        if($redis->hExists($key,'cnes')){
            return true;
        }
        return false;
    }

}