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
            move_uploaded_file($_FILES["SrcUrl"]["tmp_name"], __IMG__ ."examimg/". $src);
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
}