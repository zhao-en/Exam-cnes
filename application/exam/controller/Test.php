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
use think\Db;
use think\Exception;

class Test extends Controller
{
    function add($PaperInfo=null , $PaperName=null){

        if(!$this->testParams([$PaperName , $PaperInfo])){
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

    /**
     * get
     */
    function get($KeyWord = "" , $W = "" , $P = 1 , $N = 10){
        try{

            //统计数目
            $T = Db::table('cnes_paper_bank')->alias('pb')
                ->where(['pb.IsDel'=>0])->count();
            //数据查询
            $L = Db::table('cnes_paper_bank')->alias('pb')
                ->join('cnes_user u','u.UserID = pb.CUID')->page($P,$N)
                ->field(
                    'pb.PIID , pb.TypeID , pb.CTInfo , pb.JTInfo , pb.QTInfo , pb.PName , pb.CTime , u.Name as UserName , u.UserID'
                )->where(['pb.IsDel'=>0])->select();
            //组装字典
            $PointList = Redisc::hGetAll('cnes_point_list');
            $LevelList = Redisc::hGetAll('cnes_level_list');
            $ScopeList = Redisc::hGetAll('cnes_scope_list');
            $TypeList = Redisc::hGetAll('cnes_type_list');

            foreach ($L as $Key =>  $Value){
                if($Value['CTInfo'] != null){
                    $L[$Key]['CTInfo'] = json_decode($Value['CTInfo'],true);
                    foreach ($L[$Key]['CTInfo'] as $key => $value){
                        foreach ($value as $K => $V ){
                            switch ($K){
                                case "TypeID":
                                    $L[$Key]['CTInfo'][$key]['TypeName'] = $TypeList[$V - 1];
                                    break;
                                case "PointID":
                                    $L[$Key]['CTInfo'][$key]['PointName'] = $PointList[$V - 1];
                                    break;
                                case "LevelID":
                                    $L[$Key]['CTInfo'][$key]['LevelName'] = $LevelList[$V - 1];
                                    break;
                                case "ScopeID":
                                    $L[$Key]['CTInfo'][$key]['ScopeName'] = $ScopeList[$V - 1];
                                    break;
                            }
                        }

                    }
                }

                if($Value['JTInfo'] != null){
                    $L[$Key]['JTInfo'] = json_decode($Value['JTInfo'],true);
                    foreach ($L[$Key]['JTInfo'] as $key => $value){
                        foreach ($value as $K => $V ){
                            switch ($K){
                                case "TypeID":
                                    $L[$Key]['JTInfo'][$key]['TypeName'] = $TypeList[$V - 1];
                                    break;
                                case "PointID":
                                    $L[$Key]['JTInfo'][$key]['PointName'] = $PointList[$V - 1];
                                    break;
                                case "LevelID":
                                    $L[$Key]['JTInfo'][$key]['LevelName'] = $LevelList[$V - 1];
                                    break;
                                case "ScopeID":
                                    $L[$Key]['JTInfo'][$key]['ScopeName'] = $ScopeList[$V - 1];
                                    break;
                            }
                        }

                    }
                }

                if($Value['QTInfo'] != null){
                    $L[$Key]['QTInfo'] = json_decode($Value['QTInfo'],true);
                    foreach ($L[$Key]['QTInfo'] as $key => $value){
                        foreach ($value as $K => $V ){
                            switch ($K){
                                case "TypeID":
                                    $L[$Key]['QTInfo'][$key]['TypeName'] = $TypeList[$V - 1];
                                    break;
                                case "PointID":
                                    $L[$Key]['QTInfo'][$key]['PointName'] = $PointList[$V - 1];
                                    break;
                                case "LevelID":
                                    $L[$Key]['QTInfo'][$key]['LevelName'] = $LevelList[$V - 1];
                                    break;
                                case "ScopeID":
                                    $L[$Key]['QTInfo'][$key]['ScopeName'] = $ScopeList[$V - 1];
                                    break;
                            }
                        }

                    }
                }
            }
            return $this->trueMsg(["L"=>$L,'P'=>intval($P),'N'=>$N,'T'=>$T]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

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