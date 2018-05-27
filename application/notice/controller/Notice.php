<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/8
 * Time: 17:42
 */

namespace app\notice\controller;

use app\admin\model\User as UserModel;
use app\common\controller\Redisc;
use app\notice\model\Notice as NoticeModel;
use app\notice\model\ViewUserNotice;
use think\console\command\make\Model;
use think\Controller;
use think\Db;
use think\Exception;

class Notice extends Controller
{
    /**
     * 添加公告
     * 图片大小限制1M
     */
    function add($Title=null,$Content=null){

        //获取当前时间
        $NTime = time();
        //参数检测
        if(!$this->testParams([$Title,$Content])){
            return $this->falseMsg("请填写完整信息");
        }

        //图片检测
        $src = "";
        if(!empty($_FILES)){

            //TODO 图片规格检测 ....

            //保存图片
            $type = explode('.',$_FILES["Img"]["name"]);
            $type = $type[count($type) - 1];
            $src = 'notice_'.$NTime.'.'.$type;
            //存储图片
            move_uploaded_file($_FILES["Img"]["tmp_name"], __IMG__ . $src);
        }

        //存储数据库
        $userInfo = Redisc::hGetAll(cookie('cnes'));
        $noticeModel = new NoticeModel();
        $res = $noticeModel->insert(['UserID'=>$userInfo['UserID'],'Title'=>$Title,"Content"=>$Content,"SrcUrl"=>$src,"Time"=>$NTime]);
        if($res == 1){
            return $this->falseMsg("发布成功");
        }
        return $this->falseMsg("发布失败");







    }

    /**
     * 删除公告
     */
    function del($NoticeID = null){
        if(!$this->testParams([$NoticeID])){
            return $this->falseMsg("参数不能为空");
        }

        $noticeModel = new NoticeModel();
        if($noticeModel->save(['IsDel'=>1],['NoticeID'=>$NoticeID]) == 1){
            return $this->trueMsg("删除成功");
        }

        return $this->falseMsg("删除失败");

    }

    /**
     * 查询公告 分页进行
     * @throws
     */
    function get($KeyWord = "", $W = '', $P = 1, $N = 10)
    {
        if(empty($W)){
            $L = Db::table('cnes_view_user_notice')->where(['IsDel'=>0])->page($P,$N)->select();
            //时间date
            foreach ($L as $index => $value){
                $L[$index]['Time'] = date("Y-m-d H:i",$value['Time']);
            }
            //总共数量
            $T = Db::table('cnes_view_user_notice')->count();
            return [
                "res" => true,
                "msg" => [
                    "L" => $L,
                    'P' => intval($P),
                    'N' => $N,
                    'T' => $T,
                ]
            ];
        }

        $temp = explode('|',$W);
        foreach ($temp as $index => $value){
            $temp2 = explode('=',$value);
            $where[$temp2[0]] = $temp2[1];
        }
        //精准查询
        $L = Db::table('cnes_view_user_notice')->where($where)->find();
        $L['Content'] = str_replace("\n","<br>",$L['Content']);
        return [
            "res" => true,
            "msg" => [
                "L" => $L,
                'P' => intval($P),
                'N' => $N,
                'T' => null,
            ]
        ];
    }

    function getNewThree(){
        try{
            $L = Db::table('cnes_view_user_notice')->where(['IsDel'=>0])->order('Time')->limit(3)->select();
            $a = 0;

        }catch (Exception $e){

        }
    }

}