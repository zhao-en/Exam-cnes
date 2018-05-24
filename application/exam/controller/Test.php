<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/19
 * Time: 20:44
 */

namespace app\exam\controller;


use app\common\controller\Redisc;
use think\Controller;
use app\exam\model\PaperBank;

class Test extends Controller
{
    function add($PaperInfo=null , $PaperName=null){


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

        $userInfo = Redisc::hGetAll(cookie('cnes'));

        $paperBank = new PaperBank();
        $res = $paperBank->insert([
            "CUID"=>2,//$userInfo['UserID'],
            "TypeID"=>"1",//练习
            "CTInfo"=>$CTInfo,
            "JTInfo"=>$JTInfo,
            "QTInfo"=>$QTInfo,
            "PName"=>$PaperName,
            "CTime"=>$NTime
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

}