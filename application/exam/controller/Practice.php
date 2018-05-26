<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/19
 * Time: 22:35
 */

namespace app\exam\controller;


use app\common\controller\Redisc;
use think\Controller;
use app\exam\model\TitleBank as TitleBankModel;
use think\Db;
use think\Exception;

class Practice extends Controller
{
    /**
     * @param null $TypeID
     * @param null $ScopeID
     * @param null $LevelID
     * @param null $PointID
     * @param null $Content
     * @param null $Options 选项之间是 \r\n
     * @param null $CKAnswer
     * @param null $JXAnswer
     * @param null $IsMultiSelect
     * @return array
     */
    function add($TypeID = null , $ScopeID = null , $LevelID = null , $PointID = null , $Content = null , $Options = null , $CKAnswer = null , $JXAnswer = null , $IsMultiSelect = null){

        $NTime = time();

        if(!$this->testParams([$TypeID,$ScopeID,$LevelID,$PointID,$Content,$CKAnswer,$JXAnswer])){
            return $this->falseMsg("信息不完整");
        }

        if($TypeID == 1){
            if(!$this->testParams([$Options,$IsMultiSelect])){
                return $this->falseMsg("信息不完整");
            }
        }

        //判断是否有图片 存储图片数据
        $src = null;
        if(isset($_FILES['SrcUrl'])){
            $type = explode('.',$_FILES["SrcUrl"]["name"]);
            $type = $type[count($type) - 1];
            $src = 'exam_'.$NTime.'.'.$type;
            //存储图片
            move_uploaded_file($_FILES["SrcUrl"]["tmp_name"], __IMG__ ."examing/". $src);
        }

        //存储数据库
        $userInfo = Redisc::hGetAll(cookie('cnes'));
        $titleBankModel = new TitleBankModel();
        $res = $titleBankModel->insert(
            [
                "UserID"=>2,
                "ScopeID"=>$ScopeID,
                "LevelID"=>$LevelID,
                "PointID"=>$LevelID,
                "TypeID"=>$TypeID,
                "Content"=>$Content,
                "Options"=>$Options,
                "CKAnswer"=>$CKAnswer,
                "JXAnswer"=>$JXAnswer,
                "IsMultiSelect"=>$IsMultiSelect,
                "SrcUrl"=>$src,
                "CTime"=>$NTime

            ]);

        if($res == 1){
            return $this->trueMsg("上传成功");
        }
        return $this->falseMsg("上传失败");
    }

    /**
     * 获取数据
     * @param string $KeyWord
     * @param string $W
     * @param int $P
     * @param int $N
     * @return array|string
     */
    function get($KeyWord = "" , $W = "" , $P = 1 , $N = 10){
        try{
            //统计数目
            $T = Db::table('cnes_title_bank')->alias('t')
                ->where(['t.IsDel'=>0])->count();
            //数据查询
            $L = Db::table('cnes_title_bank')->alias('t')
                ->join('cnes_user u','u.UserID = t.UserID')
                ->join('cnes_title_scope_static tss','tss.ScopeID = t.ScopeID')
                ->join('cnes_title_level_static tls','tls.LevelID = t.LevelID')
                ->join('cnes_title_point_static tps','tps.PointID = t.PointID')
                ->join('cnes_title_type_static tys','tys.TypeID = t.TypeID')
                ->page($P,$N)
                ->field(
                    't.TitleID,t.UserID,t.ScopeID,tss.Name as ScopeName,
                    t.LevelID,tls.Name as LevelName,t.PointID,tps.Name as PointName,
                    t.TypeID,tys.Name as TypeName,u.Name as UserName,t.Content,
                    t.Options,t.CKAnswer,JXAnswer,t.IsMultiSelect')->where(['t.IsDel'=>0])->select();
            return $this->trueMsg(["L"=>$L,'P'=>intval($P),'N'=>$N,'T'=>$T]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /*
     * 删除数据
     */
    function del($TitleID=null){
        if(!$this->testParams([$TitleID])){
            return $this->falseMsg("参数不能为空");
        }

        $titleBankModel = new TitleBankModel();
        if($titleBankModel->save(['IsDel'=>1],['TitleID'=>$TitleID]) == 1){
            return $this->trueMsg("删除成功");
        }

        return $this->falseMsg("删除失败");
    }

    /**
     * 修改数据
     * @param null $Content
     * @param null $Options
     * @param null $CKAnswer
     * @param null $JXAnswer
     * @param null $TitleID
     * @param null $TypeID
     * @return array
     */
    function save($Content=null , $Options=null , $CKAnswer=null , $JXAnswer=null , $TitleID=null , $TypeID=null){
        if(!$this->testParams([$Content , $CKAnswer ,$JXAnswer ,$TitleID , $TypeID])){
            return $this->falseMsg("信息不能为空");
        }

        $d = [
            "Content"=>$Content,
            "CKAnswer"=>$CKAnswer,
            "JXAnswer"=>$JXAnswer
        ];

        if($TypeID == 1){
            $d['Options'] = $Options;
        }

        $titleBankModel = new TitleBankModel();
        $res = $titleBankModel->save($d,["TitleID"=>$TitleID]);

        if($res == 1){
            return $this->trueMsg("修改成功");
        }else if($res == 0){
            return $this->trueMsg("信息未发生变化");
        }
        return $this->falseMsg("修改失败");
    }


}