<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/27
 * Time: 22:57
 */

namespace app\file\controller;


use app\common\controller\Redisc;
use think\Controller;
use app\file\model\File as FileModel;
use think\Db;
use think\Exception;

class StudentFile extends Controller
{


    /**
     * 获取资料列表 分页
     */
    function get($KeyWord = "" , $W = "" , $P = 1 , $N = 10){
        try{
            //统计数目
            $T = Db::table('cnes_file')->alias('f')
                ->join('cnes_user u','u.UserID = f.UserID')->where(['f.IsDel'=>0])->count();
            //数据查询
            $L = Db::table('cnes_file')->alias('f')
                ->join('cnes_user u','u.UserID = f.UserID')->page($P,$N)
                ->field('u.UserID,u.SNO,u.Name as Name,f.Title ,f.FileUrl,f.Note,f.FileID,f.Time')->where(['f.IsDel'=>0])->select();
            return $this->trueMsg(["L"=>$L,'P'=>intval($P),'N'=>$N,'T'=>$T]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}