<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/7
 * Time: 11:13
 */

namespace app\common\controller;


class CreateCode
{
    /*
     * 创建10位密码，传入用户的ID,不重复
     */
    public static function CC($id)
    {

        $ps = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $ic = str_pad($id,5,'0',STR_PAD_LEFT );
        $icr = '';
        for ($i = 0; $i < 10; $i++ ){
            $icr .= substr($ps,rand(0,35),1).substr($ic,$i,1);
        }
        return $icr;
    }

    /**
     * 创建连接id
     */
    public static function cnes(){
        return str_replace('$',rand(0,9),crypt(time(),'$1$rasmusle$'));
    }

}