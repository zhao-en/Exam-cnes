<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/8
 * Time: 20:13
 */

namespace app\admin\controller;


use app\common\controller\Redisc;
use think\Controller;

class Permission extends Controller
{
    /**
     * 页面检测 session 长连接
     */
    function htmlPermission()
    {
        if (!isset($_COOKIE['cnes'])) {
            return $this->falseMsg("");
        }
        $cnes = $_COOKIE['cnes'];
        if (!Redisc::hExistKey($cnes)) {
            return $this->falseMsg("");
        } else {
            Redisc::expire($cnes, 10 * 60);
        }
        return $this->trueMsg("");
    }

}