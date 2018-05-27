<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/27
 * Time: 23:24
 */

namespace app\notice\controller;


use think\Controller;
use think\Db;
use think\Exception;

class StudentNotice extends Controller
{
    /**
     * 获取最新三个公告的详情
     */
    function getNewThree(){
        try{
            $L = Db::table('cnes_view_user_notice')->where(['IsDel'=>0])->order('Time')->limit(3)->select();
            foreach ($L as $Key => $Value){
                if(($a = mb_strlen($Value['Content'],'utf-8')) > 200){
                    $L[$Key]['Content'] = mb_substr ($L[$Key]['Content'],0,200,'utf-8');
                }
            }

            return $this->trueMsg(['L'=>$L]);

        }catch (Exception $e){
            return $this->falseMsg($e->getMessage());
        }
    }

    function ChgTitle($title)
    {
        $length = 46; //我们允许字符串显示的最大长度
        if (strlen($title) > $length) {
            $temp = 0;
            for ($i = 0; $i < $length; $i++)
                if (ord($title[$i]) > 128) $temp++;
            if ($temp % 2 == 0)
                $title = substr($title, 0, $length) . "...";
            else
                $title = substr($title, 0, $length + 1) . "...";
        }
        return $title;
    }

}