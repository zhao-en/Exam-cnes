<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/25
 * Time: 18:02
 */

namespace app\exam\controller;


use app\common\controller\CreateCode;
use app\common\controller\Redisc;
use app\exam\model\PracticeRecord;
use think\Controller;
use think\Db;
use think\Exception;

class StudentPractice extends Controller
{

    private static $NumLetterM = ["A","B","C","D","E","F","G","H","I","G","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
    private static $NumLetterS = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];



    /**
     * 根据条件抽取题目
     * 默认是 选择
     */
    function getTitle($ScopeID = null, $PointID = null, $LevelID = null, $TypeID = null, $Num = null)
    {

        if (!$this->testParams([$ScopeID, $PointID, $LevelID, $TypeID, $Num])) {
            return $this->falseMsg("请填写完整信息");
        }

        $Code = CreateCode::CC(rand(0, 9999));
        //数据库抽取题目
        $w = ["ScopeID" => $ScopeID, "PointID" => $PointID, "LevelID" => $LevelID, "TypeID" => $TypeID, "IsDel" => 0];
        try {
            $L = Db::table('cnes_title_bank')->alias('tb')->where($w)->order('rand()')->limit($Num)->select();
            //将数据缓存存储在redis 10分钟
            Redisc::set($Code, json_encode($L));

            switch ($TypeID){
                case 1:
                    //选择
                    foreach ($L as $Key => $Value) {
                        $L[$Key]['Options'] = explode("\n", $Value['Options']);
                        unset($L[$Key]['CKAnswer']);
                        unset($L[$Key]['JXAnswer']);
                    }
                    break;
                case 2:
                    //判断
                    foreach ($L as $Key => $Value) {
                        unset($L[$Key]['CKAnswer']);
                        unset($L[$Key]['JXAnswer']);
                    }
                    break;
                case 3:
                    //简答
                    foreach ($L as $Key => $Value) {
                        unset($L[$Key]['CKAnswer']);
                        unset($L[$Key]['JXAnswer']);
                    }
                    break;
            }
            return $this->trueMsg(["L" => $L, "Code" => $Code]);
        } catch (Exception $e) {
            return $e->getMessage();
        }



    }

    /**
     * 查看历史记录
     */
    function get(){

    }

    /**
     * 提交题目，进行题目判定,并返回参考答案
     * 默认是选择
     */
    function submit($Code=null , $Answer=null , $TypeID=null){

        if(!$this->testParams([$Code , $Answer])){
            return $this->falseMsg("请填写完整信息");
        }

        //判断是否已经交卷
        $practiceRecord = new PracticeRecord();
        $isE = $practiceRecord->where(['Code'=>$Code])->count();
        if($isE >= 1){
            return $this->falseMsg("您已经提交过，请前往历史记录查看");
        }

        $NTime = time();
        $Score = 0;

        //获取正确答案信息
        $RAnswer = json_decode(Redisc::get($Code),true);
        foreach ($RAnswer as $Key => $Value){
            $MyAnswer = '';

            switch ($TypeID){
                case 1:
                    //选择题
                    if(isset($Answer[$Key + 1]) && is_array($Answer[$Key + 1])){
                        foreach ($Answer[$Key + 1] as $key => $value){
                            $MyAnswer .= StudentPractice::$NumLetterM[$value - 1];
                        }
                    }
                    $RAnswer[$Key]['Options'] = explode("\n",$Value['Options']);
                    break;
                case 2:
                    //判断题
                    if(isset($Answer[$Key + 1]) && is_array($Answer[$Key + 1])){
                        $MyAnswer .= $Answer[$Key + 1][0];
                    }
                    break;
                case 3:
                    $MyAnswer = $Answer[$Key + 1][0];
                    break;
            }

            //答案判断
            if($MyAnswer != ''){
                $RAnswer[$Key]['MyAnswer'] = $MyAnswer;
                if(($TypeID == 1 || $TypeID == 2) &&trim($RAnswer[$Key]['CKAnswer']) == trim($MyAnswer)){
                    $Score ++;
                }else{
                    $Score = "主观题无自动评定";
                }
            }else{
                $RAnswer[$Key]['MyAnswer'] = "未填写答案";
            }
        }

        //存储答题记录
        $userInfo = Redisc::hGetAll(cookie('cnes'));
        $res = $practiceRecord->insert([
            "UserID"=>2,//$userInfo['UserID'],
            "MyAnswer"=>json_encode($RAnswer),
            "MyScore"=>$Score,
            "Time"=>$NTime,
            "Code"=>$Code
        ]);

        if($res == 1){
            return $this->trueMsg(["L"=>$RAnswer,"Score"=>$Score]);
        }
        return $this->falseMsg("提交失败");
    }


}