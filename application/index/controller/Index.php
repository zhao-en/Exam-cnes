<?php
namespace app\index\controller;


use think\Controller;
use think\Db;
use think\Exception;

class Index extends Controller
{

    /**
     * 后台主页
     */
    public function index()
    {

    }

    /**
     * 抽取试题进行批阅
     */
    function getTitle(){

        try{
            $L = Db::table('cnes_test_record')->alias('tr')
                ->join('cnes_paper_bank pb' , 'tr.PIID=pb.PIID and pb.TypeID=2' )
                ->where(['tr.OTScore'=>null,'TypeID'=>2])->order('rand()')->limit(1)
                ->field('tr.PIID , tr.MyAnswer , tr.STScore,tr.RecordID')
                ->select();
            $L = $L[0];
            if(empty($L)){
                return $this->falseMsg("请求失败");
            }
            $jdInfo = [];
            $L['MyAnswer'] = json_decode($L['MyAnswer'],true);
            foreach ($L['MyAnswer'] as $Key => $Value){
                if($Value['TypeID'] == 3){
                    $jdInfo[] = $Value;
                }
            }
            return $this->trueMsg(["L"=>$jdInfo,"STScore"=>$L['STScore'],"RecordID"=>$L['RecordID']]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * 提交批阅结果
     */
    function sumbitRes($OTScore = null , $STScore = null , $RecordID = null){
        if(!$this->testParams([$OTScore,$STScore,$RecordID])){
            return $this->falseMsg("填写完整信息");
        }

        try{
            $L = Db::table('cnes_test_record')->where(['RecordID'=>$RecordID])->update(['CTScore'=>($OTScore + $STScore), 'OTScore'=>$OTScore]);

            if($L == 1){
                return $this->trueMsg("成功");
            }
            return $this->falseMsg("");
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}
