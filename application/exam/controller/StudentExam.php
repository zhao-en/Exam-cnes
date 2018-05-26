<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/26
 * Time: 23:42
 */

namespace app\exam\controller;



use app\common\controller\CreateCode;
use app\common\controller\Redisc;
use app\exam\model\TestRecord;
use app\exam\model\TitleBank;
use think\Controller;
use think\Db;
use think\Exception;

class StudentExam extends Controller
{
    private static $NumLetterM = ["A","B","C","D","E","F","G","H","I","G","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
    private static $NumLetterS = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];
    /**
     * 获取测试记录
     */
    function get($KeyWord = "" , $W = [] , $P = 1 , $N = 10){
        try{
            //统计数目
            $userInfo = Redisc::hGetAll(cookie('cnes'));
            $T = Db::table('cnes_paper_bank')->alias('pb')->where(['TypeID'=>2])->count();
            //数据查询
            $L = Db::table('cnes_paper_bank')->alias('pb')
                ->join('cnes_user u','u.UserID=pb.CUID')
                ->page($P,$N)
                ->where(['TypeID'=>2])
                ->field('u.Name as UserName , pb.PName , pb.TotalScore , pb.PIID , pb.STime , pb.ETime , pb.LTime , pb.PIID')
                ->select();
//            $st['LevelList'] = Redisc::hGetAll('cnes_level_list');
//            $st['PointList'] = Redisc::hGetAll('cnes_point_list');
//            $st['ScopeList'] = Redisc::hGetAll('cnes_scope_list');
//            $st['TypeList'] = Redisc::hGetAll('cnes_type_list');

            foreach ($L as $Key => $Value){
//                $L[$Key]['MyAnswer'] = json_decode($Value['MyAnswer'],true);
//                $L[$Key]['TypeID'] = $L[$Key]['MyAnswer'][0]['TypeID'];
//                $L[$Key]['TypeName'] =  $st['TypeList'][$L[$Key]['MyAnswer'][0]['TypeID'] - 1];
//                $L[$Key]['LevelID'] = $L[$Key]['MyAnswer'][0]['LevelID'];
//                $L[$Key]['LevelName'] =  $st['LevelList'][$L[$Key]['MyAnswer'][0]['LevelID'] - 1];
//                $L[$Key]['PointID'] = $L[$Key]['MyAnswer'][0]['PointID'];
//                $L[$Key]['PointName'] =  $st['PointList'][$L[$Key]['MyAnswer'][0]['PointID'] - 1];
//                $L[$Key]['ScopeID'] = $L[$Key]['MyAnswer'][0]['ScopeID'];
//                $L[$Key]['ScopeName'] =  $st['ScopeList'][$L[$Key]['MyAnswer'][0]['ScopeID'] - 1];
//                unset($L[$Key]['MyAnswer']);
                $L[$Key]['LTime'] = $L[$Key]['LTime'] / 60;
            }
            return $this->trueMsg(["L"=>$L,'P'=>intval($P),'N'=>$N,'T'=>$T]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * 获取试卷信息
     */
    function getPaperInfo($PIID=null){
        try{
            //获取试卷条件信息 测试 PIID
            $L = Db::table('cnes_paper_bank')->alias('pr')->where(["PIID"=>$PIID])->select()[0];

            //解析条件
            $L['CTInfo'] =  json_decode($L['CTInfo'],true);
            $L['JTInfo'] =  json_decode($L['JTInfo'],true);
            $L['QTInfo'] =  json_decode($L['QTInfo'],true);
            //抽取题目
            $res = [];
            $titleBank = new TitleBank();
            if(is_array($L['CTInfo']) && !empty($L['CTInfo'])){
                //选择题目
                foreach ($L['CTInfo'] as $Key => $Value){
                    $temp = $titleBank->where([
                        'TypeID'=>$Value['TypeID'] ,
                        'ScopeID'=>$Value['ScopeID'] ,
                        'PointID'=>$Value['PointID'] ,
                        'LevelID'=>$Value['LevelID'] ,
                    ])->order('rand()')->limit($Value['Num'])->select();
                    if(is_array($temp) && !empty($temp)){
                        foreach ($temp as $key => $value){
                            $t = $value->toArray();
                            $t['Options'] = explode("\n",$t['Options']);
                            $t['Score'] = $Value['ScorePer'];
                            $res[] = $t;
                        }
                    }
                }
            }
            if(is_array($L['JTInfo']) && !empty($L['JTInfo'])){
                //判断题目
                foreach ($L['JTInfo'] as $Key => $Value){
                    $temp = $titleBank->where([
                        'TypeID'=>$Value['TypeID'] ,
                        'ScopeID'=>$Value['ScopeID'] ,
                        'PointID'=>$Value['PointID'] ,
                        'LevelID'=>$Value['LevelID'] ,
                    ])->order('rand()')->limit($Value['Num'])->select();
                    if(is_array($temp) && !empty($temp)){
                        foreach ($temp as $key => $value){
                            $t = $value->toArray();
                            $t['Score'] = $Value['ScorePer'];
                            $res[] = $t;
                        }
                    }
                }

            }
            if(is_array($L['QTInfo']) && !empty($L['QTInfo'])){
                //简答题目
                foreach ($L['QTInfo'] as $Key => $Value){
                    $temp = $titleBank->where([
                        'TypeID'=>$Value['TypeID'] ,
                        'ScopeID'=>$Value['ScopeID'] ,
                        'PointID'=>$Value['PointID'] ,
                        'LevelID'=>$Value['LevelID'] ,
                    ])->order('rand()')->limit($Value['Num'])->select();
                    if(is_array($temp) && !empty($temp)){
                        foreach ($temp as $key => $value){
                            $t = $value->toArray();
                            $t['Score'] = $Value['ScorePer'];
                            $res[] = $t;
                        }
                    }
                }
            }

            //存储数据
//            $r = Db::table('cnes_test_record')->insert([
//                'UserID'=>2,//从当前redis中存储
//                'PIID'=>$PIID,
//                'PContent'=>json_encode($res),
//                'CTScore'=>$L['TotalScore']
//            ]);

            //生成秘钥
            //$RecordID = Db::table('cnes_test_record')->getLastInsID();
            $Code = CreateCode::CC(rand(0,9999));
            $res['PIID'] = $L['PIID'];
            $res['TotalScore'] = $L['TotalScore'];

            //存储redis正确答案
            Redisc::set($Code,json_encode($res),60*60*100+60);

            unset($res['PIID']);
            unset($res['TotalScore']);
            //过滤答案信息
            foreach ($res as $Key => $Value){
                unset($res[$Key]['CKAnswer']);
                unset($res[$Key]['JXAnswer']);
            }



            return $this->trueMsg(['L'=>$res ,'Code'=>$Code]);
            //返回数据
        }catch (Exception $e){
            return $e->getMessage();
        }

    }

    function submit($Code=null , $Answer=null){
        if(!$this->testParams([$Code , $Answer])){
            return $this->falseMsg("请填写完整信息");
        }

        //判断是否已经交卷
        if(!Redisc::existKey($Code)){
            return $this->falseMsg("您已经提交，请到历史记录查看");
        }

        $testRecord = new TestRecord();

        $NTime = time();
        $Score = 0;

        //获取正确答案信息
        $RAnswer = json_decode(Redisc::get($Code),true);


        foreach ($RAnswer as $Key => $Value){
            if($Key == 'PIID') continue;
            if($Key == 'TotalScore') continue;
            $MyAnswer = '';
            $TypeID = $Value['TypeID'];
            switch ($TypeID){
                case 1:
                    //选择题
                    if(isset($Answer[$Key]) && is_array($Answer[$Key])){
                        foreach ($Answer[$Key] as $key => $value){
                            $MyAnswer .= StudentExam::$NumLetterM[$value - 1];
                        }
                    }
                    //$RAnswer[$Key]['Options'] = explode("\n",$Value['Options']);
                    break;
                case 2:
                    //判断题
                    if(isset($Answer[$Key]) && is_array($Answer[$Key])){
                        $MyAnswer .= $Answer[$Key + 1][0];
                    }
                    break;
                case 3:
                    $MyAnswer = $Answer[$Key][0];
                    break;
            }

            //答案判断
            if($MyAnswer != ''){
                $RAnswer[$Key]['MyAnswer'] = $MyAnswer;
                if(($TypeID == 1 || $TypeID == 2) &&trim($RAnswer[$Key]['CKAnswer']) == trim($MyAnswer)){
                    $Score ++;
                }
            }else{
                $RAnswer[$Key]['MyAnswer'] = "未填写答案";
            }
        }

        //存储答题记录
        $res = $testRecord->insert([
            "MyAnswer"=>json_encode($RAnswer),
            "STScore"=>$Score,
            "UpTime"=>$NTime,
            'UserID'=>2,//从当前redis中存储
            'PIID'=>$RAnswer['PIID'],
            'CTScore'=>$RAnswer['TotalScore'],

        ]);

        unset($RAnswer['PIID']);
        unset($RAnswer['TotalScore']);
        if($res == 1){
            Redisc::expire($Code,0);
            return $this->trueMsg(["L"=>$RAnswer,"Score"=>$Score]);
        }
        return $this->falseMsg("提交失败");
    }


    /**
     * 详情记录查看
     */
    function details($RecordID = null){

        try{
            $L = Db::table('cnes_test_record')->alias('tr')->where(['RecordID'=>$RecordID/*$userInfo["UserID"]*/])->select();

            if(empty($L)){
                return $this->falseMsg("请求失败");
            }
            $Score = $L[0]['STScore'];
            $L = json_decode($L[0]['MyAnswer'],true);
            unset($L['PIID']);
            unset($L['TotalScore']);

            return $this->trueMsg(["L"=>$L,"Score"=>$Score]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }


    /**
     * 查看历史记录
     */
    function getRecords($KeyWord = "" , $W = [] , $P = 1 , $N = 10){

        try{
            //统计数目 TODO
            $userInfo = Redisc::hGetAll(cookie('cnes'));
            $T = Db::table('cnes_test_record')->alias('tr')
                ->join('cnes_paper_bank pb','pb.PIID = tr.PIID')
                ->where(['UserID'=>2/*$userInfo["UserID"]*/,"pb.TypeID"=>2])->count();
            //数据查询
            $L = Db::table('cnes_test_record')->alias('tr')
                ->join('cnes_paper_bank pb','pb.PIID = tr.PIID')
                ->page($P,$N)
                ->where(['UserID'=>2/*$userInfo["UserID"]*/,"pb.TypeID"=>2])
                ->field('pb.PIID , pb.PName , tr.UpTime , tr.CTScore , tr.STScore , tr.RecordID')
                ->select();
//            $st['LevelList'] = Redisc::hGetAll('cnes_level_list');
//            $st['PointList'] = Redisc::hGetAll('cnes_point_list');
//            $st['ScopeList'] = Redisc::hGetAll('cnes_scope_list');
//            $st['TypeList'] = Redisc::hGetAll('cnes_type_list');
//
//            foreach ($L as $Key => $Value){
//                $L[$Key]['MyAnswer'] = json_decode($Value['MyAnswer'],true);
//                $L[$Key]['TypeID'] = $L[$Key]['MyAnswer'][0]['TypeID'];
//                $L[$Key]['TypeName'] =  $st['TypeList'][$L[$Key]['MyAnswer'][0]['TypeID'] - 1];
//                $L[$Key]['LevelID'] = $L[$Key]['MyAnswer'][0]['LevelID'];
//                $L[$Key]['LevelName'] =  $st['LevelList'][$L[$Key]['MyAnswer'][0]['LevelID'] - 1];
//                $L[$Key]['PointID'] = $L[$Key]['MyAnswer'][0]['PointID'];
//                $L[$Key]['PointName'] =  $st['PointList'][$L[$Key]['MyAnswer'][0]['PointID'] - 1];
//                $L[$Key]['ScopeID'] = $L[$Key]['MyAnswer'][0]['ScopeID'];
//                $L[$Key]['ScopeName'] =  $st['ScopeList'][$L[$Key]['MyAnswer'][0]['ScopeID'] - 1];
//                unset($L[$Key]['MyAnswer']);
//            }
            return $this->trueMsg(["L"=>$L,'P'=>intval($P),'N'=>$N,'T'=>$T]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

}