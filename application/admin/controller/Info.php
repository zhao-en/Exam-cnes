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
}