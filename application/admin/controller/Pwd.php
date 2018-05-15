<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/7
 * Time: 11:48
 */

namespace app\admin\controller;

use app\common\controller\CreateCode;
use app\common\controller\Email;
use app\common\controller\Redisc;
use app\admin\model\User as UserModel;
use think\Controller;

class Pwd extends Controller
{

    protected static $findUrl = 'http://127.0.0.1/swustcnes/view/rp.html?Code=%s&Email=%s';

    /**
     * 找回密码
     * @throws
     */
    function findPWD($Email = null){
        if($Email == null){
            return $this->falseMsg("请填入完整信息");
        }

        $Email = trim($Email);
        if($Email == ''){
            return $this->falseMsg("邮箱不能为空");
        }

        //查看邮箱是否存在
        $userModel = new UserModel();
        $res = $userModel->where(['Email'=>$Email])->find();
        if(empty($res) || $res == false){
            return $this->falseMsg("此邮箱暂未注册");
        }

        //发送邮件
        $userInfo = $res->toArray();
        $code = CreateCode::CC($userInfo['UserID']);
        //将当前code存储在redis服务器中
        if(Redisc::set($Email,$code) != true){
            return $this->falseMsg("失败");
        }
        $url = sprintf(Pwd::$findUrl,$code,$Email);
        $res = Email::sendMail('swust cnes find account', '点击链接，重置密码:&nbsp;&nbsp;'.$url, $Email);
        if($res['res'] == true){
            return $this->trueMsg("重置链接已经发送，请到邮箱查看");
        }
        return $this->falseMsg("失败");
    }

    /**
     * 登陆后邮箱重置密码
     */
    function findPWD2(){
        if($this->isLogin()){
            $userInfo = Redisc::hGetAll(cookie('cnes'));
            $code = CreateCode::CC($userInfo['UserID']);
            //将当前code存储在redis服务器中
            if(Redisc::set($userInfo['Email'],$code) != true){
                return $this->falseMsg("失败");
            }
            $url = sprintf(Pwd::$findUrl,$code,$userInfo['Email']);
            $res = Email::sendMail('swust cnes find account', '点击链接，重置密码:&nbsp;&nbsp;'.$url, $userInfo['Email']);
            if($res['res'] == true){
                return $this->trueMsg("重置链接已经发送，请到邮箱查看");
            }
            return $this->falseMsg("失败");
        }else{
            return $this->falseMsg("无权限");
        }
    }


    /**
     * 邮箱重置密码
     * @throws
     */
    function resetPWD($Email=null , $PWD=null , $Code=null){

        if($Email == null || $Code == null ||  $PWD == null){
            return $this->falseMsg("请填入完整信息");
        }

        $Email = trim($Email);
        $PWD = trim($PWD);
        $Code = trim($Code);

        if(Redisc::get($Email) != $Code){
            return $this->falseMsg('邮件重置链失效');
        }

        //验证邮箱是否存在
        $userModel = new UserModel();
        $res = $userModel->where(['Email'=>$Email])->find();

        if(empty($res)){
            return $this->falseMsg('邮件重置链失效');
        }
        //密码重置

        $res = $userModel->save(['PWD'=>password_hash($PWD,PASSWORD_DEFAULT)],['Email'=>$Email]);
        if($res == false || $res == 0){
            return $this->falseMsg('密码重置失败');
        }

        Redisc::set($Email,'');
        return $this->trueMsg("重置成功");
    }

    /**
     * 登陆后输入密码进行重置
     * @throws
     */
    function resetPWD2($OldPWD , $NewPWD){

        if(!$this->testParams([$OldPWD,$NewPWD])){
            return $this->falseMsg("请填写完整信息");
        }

        if(!$this->isLogin()){
            return $this->falseMsg("无权限");
        }

        //获取用户信息
        $rInfo = Redisc::hGetAll(cookie('cnes'));
        //判断旧密码
        $userModel = new UserModel();
        $res = $userModel->where(['UserID'=>$rInfo['UserID']])->find();
        if(empty($res) || $res == false){
            return $this->falseMsg("修改失败");
        }

        $res = $res->toArray();
        if(!password_verify($OldPWD , $res['PWD'])){
            return $this->falseMsg("密码验证失败");
        }

        if($userModel->save(['PWD'=>$NewPWD],['UserID'=>$rInfo['UserID']])){
            return $this->trueMsg('修改成功');
        }

        return $this->falseMsg("修改失败");


    }




}