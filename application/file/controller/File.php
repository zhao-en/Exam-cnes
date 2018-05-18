<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/17
 * Time: 17:54
 */

namespace app\file\controller;


use app\common\controller\Redisc;
use think\Controller;
use app\file\model\File as FileModel;
use think\Db;
use think\Exception;

class File extends Controller
{
    /**
     * 上传文件
     * @return array
     */
    function add($Title=null, $Note=null){

        //获取当前时间
        $NTime = time();
        //参数检测
        if(!$this->testParams([$Title,$Note]) || empty($_FILES)){
            return $this->falseMsg("请填写完整信息");
        }

        //TODO 文件检测...

        //保存文件
        $type = explode('.',$_FILES["File"]["name"]);
        $type = $type[count($type) - 1];
        $src = 'file_'.$NTime.'.'.$type;
        //存储图片
        move_uploaded_file($_FILES["File"]["tmp_name"], __FILES__ . $src);

        //存储数据库
        $userInfo = Redisc::hGetAll(cookie('cnes'));
        $fileModel = new FileModel();
        $res = $fileModel->insert(['UserID'=>2/*$userInfo['UserID']*/,'Title'=>$Title,"Note"=>$Note,"FileUrl"=>$src,"Time"=>$NTime]);

        if($res == 1){
            return $this->falseMsg("上传成功");
        }
        return $this->falseMsg("上传失败");
    }

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

    function del($FileID = null){
        if(!$this->testParams([$FileID])){
            return $this->falseMsg("参数不能为空");
        }

        $fileModel = new FileModel();

        if($fileModel->save(['IsDel'=>1],['FileID'=>$FileID]) == 1){
            return $this->trueMsg("删除成功");
        }

        return $this->falseMsg("删除失败");
    }

    function save($Title = null , $Note = null , $FileID = null){

        if(!$this->testParams([$FileID])){
            return $this->falseMsg("参数不能为空");
        }

        $fileModel = new FileModel();

        $res = $fileModel->save(['Title'=>$Title,'Note'=>$Note],['FileID'=>$FileID]);
        if($res == 1){
            return $this->trueMsg("修改成功");
        }else if($res == 0){
            return $this->trueMsg("信息未发生变化");
        }

        return $this->falseMsg("修改失败");
    }
}