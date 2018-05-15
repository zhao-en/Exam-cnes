<?php
/**
 * Created by PhpStorm.
 * UserModel: en_zhao
 * Date: 2018/5/6
 * Time: 14:09
 * 用户管理
 */

namespace app\admin\controller;


use app\admin\model\User as UserModel;
use app\common\controller\CreateCode;
use app\common\controller\Redisc;
use think\console\command\make\Model;
use think\Controller;
use think\Request;

class User extends Controller
{

    function __construct(Request $request = null)
    {
        if(Redisc::get('black_'.$_SERVER['REMOTE_ADDR'])){
            //加入黑名单
        }else{
            if(Redisc::existKey($_SERVER['REMOTE_ADDR'])){
                $n = Redisc::Incr($_SERVER['REMOTE_ADDR']);
                if($n > 5000){
                    //加入黑名单 24小时
                    Redisc::set('black_'.$_SERVER['REMOTE_ADDR'],60*60*24);
                }
            }else{
                Redisc::set($_SERVER['REMOTE_ADDR'],1,60*10);
            }
        }

        parent::__construct($request);
    }

    /**
     * 用户注册
     *@throws
     */
    function register($SNO=null, $PWD=null, $Email=null){

        if($SNO == null || $PWD == null || $Email == null){
            return $this->falseMsg("请填入完整信息");
        }
        $SNO = trim($SNO);
        $PWD = trim($PWD);
        $Email = trim($Email);

        if($SNO == '' || $PWD == '' || $Email == ''){
            return $this->falseMsg("信息不能为空");
        }

        $userModel = new UserModel();
        $res = $userModel->whereOr(['SNO'=>$SNO,'Email'=>$Email])->find();

        if(!empty($res)){
            return $this->falseMsg("您已经注册，请尝试找回密码");
        }

        $res = $userModel->insert(['SNO'=>$SNO , 'PWD'=>password_hash($PWD,PASSWORD_DEFAULT) , 'Email'=>$Email]);

        if($res == 1){
            return $this->trueMsg("注册成功，请登录");
        }
        return $this->falseMsg("注册失败");
    }

    /**
     * 用户登录
     * @throws
     */
    function login($SNO = null, $PWD = null)
    {
        if($SNO == null || $PWD == null){
            return $this->falseMsg("请填入完整信息");
        }
        $SNO = trim($SNO);
        $PWD = trim($PWD);

        if($SNO == '' || $PWD == ''){
            return $this->falseMsg("账号密码不能为空");
        }

        $userModel = new UserModel();
        $res = $userModel->where(['SNO'=>$SNO])->find();
        if(empty($res)){
            return $this->falseMsg("账号密码错误");
        }
        $res = $res->toArray();
        if(password_verify($PWD , $res['PWD'])){
            $this->setUserInfoToRedis($res['UserID']);
            return $this->trueMsg("成功登陆");
        }
        return $this->falseMsg("账号密码错误");
    }

    /**
     * 用户登出
     */
    function loginOut(){
        if(Redisc::del(cookie('cnes')) >= 0){
            return $this->trueMsg("成功登出");
        }
    }

    /**
     * 存储用户的连接信息
     * key = cnes
     * value = [
     *  cnes:
     *  actor:
     * ]
     */
    protected function setUserInfoToRedis($UserID)
    {
        $userModel = new UserModel();
        $res = $userModel->getUserInfo($UserID);
        if($res == false){
            return $this->falseMsg("登录失败");
        }

        $i = array(
            'SNO'=>$res[0]['SNO'],
            'Email'=>$res[0]['Email'],
            'Actor'=>$res[0]['Actor'],
            'Name'=>$res[0]['Name'],
            'Sex'=>$res[0]['Sex'],
            'Major'=>$res[0]['Major'],
            'Tel'=>$res[0]['Tel'],
            'ActorID'=>$res[0]['ActorID'],
            'UserID'=>$res[0]['UserID'],

            'cnes'=>CreateCode::cnes()
        );
        if(Redisc::hMSet($i['cnes'],$i) == false){
            return $this->falseMsg("登录失败");
        }
        cookie('cnes',$i['cnes']);
    }


}