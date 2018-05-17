<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/7
 * Time: 20:37
 */

namespace app\admin\controller;


use app\common\controller\Redisc;
use think\Controller;
use app\admin\model\User as UserModel;
use think\Db;
use think\Exception;

class Info extends Controller
{
    /**
     * 获取用户信息
     */
    function get(){
        if($info = Redisc::hGetAll(cookie('cnes'))){
            return $this->trueMsg($info);
        }else{
            return $this->falseMsg("无权限");
        }
    }


    /*
     * 用户修改信息
     *
     */
    function set($Name=null , $Sex=null , $Major=null , $Tel=null){

        if($Name == null || $Sex == null || $Major == null || $Tel == null){
            return $this->falseMsg("信息不完整");
        }

        $Name = trim($Name);
        $Sex = trim($Sex);
        $Major = trim($Major);
        $Tel = trim($Tel);

        if($Name == '' || $Sex == '' || $Major == '' || $Tel == ''){
            return $this->falseMsg("信息不能为空");
        }

        $d = [
            'Name'=>$Name,'Sex'=>$Sex,'Major'=>$Major,'Tel'=>$Tel
        ];

        if(!Redisc::hMSet(cookie('cnes'),$d)){
            return $this->falseMsg("更新失败");
        }
        $SNO = Redisc::hGetAll(cookie('cnes'))['SNO'];
        //更新数据库
        $userModel = new UserModel();
        $res = $userModel->save($d,['SNO'=>$SNO]);
        $e = error_get_last();
        if($res === false){ //save 当数据不变的时候 会返回0
            return $this->falseMsg("更新失败");
        }
        return $this->trueMsg("更新成功");
    }

    /**
     * 获取用户信息
     * return [姓名 专业 账户 电话 邮箱 角色
     */
    function getUserList($KeyWord = "", $W = '', $P = 1, $N = 10){

        try{
            //统计数目
            $T = Db::table('cnes_user')->alias('u')
                ->join('cnes_actor_user_link aul','u.UserID = aul.UserID')
                ->join('cnes_actor_static a','aul.ActorID = a.ActorID')->count();
            //数据查询
            $L = Db::table('cnes_user')->alias('u')
                ->join('cnes_actor_user_link aul','u.UserID = aul.UserID')
                ->join('cnes_actor_static a','aul.ActorID = a.ActorID')->page($P,$N)
                ->field('u.UserID,u.SNO,u.Name as Name,a.Name as Actor,u.Sex,u.Major,u.Tel,a.ActorID')->select();

            return $this->trueMsg(["L"=>$L,'P'=>intval($P),'N'=>$N,'T'=>$T]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * 修改用户信息
     */
    function saveUserInfo($info = ''){
        return $this->trueMsg("修改成功");
    }

    /**
     * 删除用户信息
     */
    function delUserInfo(){
        return $this->trueMsg("删除成功");
    }

}