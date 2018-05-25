<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/19
 * Time: 20:42
 */

namespace app\exam\controller;


use app\common\controller\Redisc;
use app\exam\model\PaperBank;
use think\Controller;

class Exam extends Controller
{
    function add($PaperInfo=null , $PaperName=null,$BetweenTime=null,$LengthTime=null){
        if(!$this->testParams([$PaperName , $PaperInfo , $BetweenTime , $LengthTime])){
            return $this->falseMsg("请填入完整数据");
        }

        $NTime = time();

        //存储试卷信息为json格式
        $CTInfo = [];
        $JTInfo = [];
        $QTInfo = [];
        foreach ($PaperInfo as $Key => $Value){
            switch ($Value['TypeID']){
                case "1":
                    $CTInfo[] = $this->filter($Value);
                    break;
                case "2":
                    $JTInfo[] = $this->filter($Value);
                    break;
                case "3":
                    $QTInfo[] = $this->filter($Value);
                    break;
            }
        }


        $CTInfo = json_encode($CTInfo);
        $QTInfo = json_encode($QTInfo);
        $JTInfo = json_encode($JTInfo);


        //格式化时间区间
        $date = explode(' - ' , $BetweenTime);
        $StartTime = strtotime($date[0]);
        $EndTime = strtotime($date[1]);
        $LengthTime = $LengthTime * 60;  //s

        $userInfo = Redisc::hGetAll(cookie('cnes'));

        $paperBank = new PaperBank();
        $res = $paperBank->insert([
            "CUID"=>2,//$userInfo['UserID'],
            "TypeID"=>"2",//考试
            "CTInfo"=>$CTInfo,
            "JTInfo"=>$JTInfo,
            "QTInfo"=>$QTInfo,
            "PName"=>$PaperName,
            "CTime"=>$NTime,
            "STime"=>$StartTime,
            "ETime"=>$EndTime,
            "LTime"=>$LengthTime
        ]);

        if($res == 1){
            return $this->trueMsg("创建成功");
        }
        return $this->falseMsg("创建失败");
    }


    private function filter($data){
        $res["TypeID"] = $data["TypeID"];
        $res["ScopeID"] = $data["ScopeID"];
        $res["PointID"] = $data["PointID"];
        $res["LevelID"] = $data["LevelID"];
        $res["Num"] = $data["Num"];

        return $res;
    }

    /**
     * 删除
     */
    /**
     * 删除试卷
     */
    function del($PIID = null){
        if(!$this->testParams([$PIID])){
            return $this->falseMsg("请填写完整数据");
        }

        $titleBankModel = new PaperBank();
        if($titleBankModel->save(['IsDel'=>1],['PIID'=>$PIID]) == 1){
            return $this->trueMsg("删除成功");
        }

        return $this->falseMsg("删除失败");
    }


}