<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/25
 * Time: 14:51
 */

namespace app\exam\controller;


use app\exam\model\TitleLevelStatic;
use app\exam\model\TitlePointStatic;
use app\exam\model\TitleScopeStatic;
use app\exam\model\TitleTypeStatic;
use think\Controller;
use think\Db;
use think\Exception;

class Setting extends Controller
{


    /**
     * @param null $type
     * @param null $data
     * @return array
     */
    function set($TypeID=null , $Name=null){
        $db = null;
        if($TypeID ==1){
            $db = new TitleScopeStatic();
        }else if($TypeID ==2){
            $db = new TitlePointStatic();
        }else if($TypeID ==3){
            $db = new TitleLevelStatic();
        }else if($TypeID ==4){
            $db = new TitleTypeStatic();
        }

        $res = $db->insert([
            "Name"=>$Name
        ]);

        if($res == 1){
            return $this->trueMsg("添加成功");
        }

        return $this->trueMsg("添加失败");
    }

    /**
     * @param null $type
     * @param null $data
     * @return array
     */
    function del($TypeID=null , $ID=null){


        if(!$this->testParams([$TypeID , $ID])){
            return $this->falseMsg("请填写完整数据");
        }

        $db = null;
        if($TypeID ==1){
            $pk = 'ScopeID';
            $db = new TitleScopeStatic();
        }else if($TypeID ==2){
            $pk = 'PointID';
            $db = new TitlePointStatic();
        }else if($TypeID ==3){
            $pk = 'LevelID';
            $db = new TitleLevelStatic();
        }else if($TypeID ==4){
            $pk = 'TypeID';
            $db = new TitleTypeStatic();
        }
        if($db->save(['IsDel'=>1],[$pk=>$ID]) == 1){
            return $this->trueMsg("删除成功");
        }
        return $this->falseMsg("删除失败");

    }

    /**
     * @param null $type
     * @param null $data
     * @return array
     */
    function save($TypeID=null , $ID=null , $Name=null){

        if(!$this->testParams([$TypeID , $ID , $Name])){
            return $this->falseMsg("请填写完整数据");
        }

        $db = null;
        if($TypeID ==1){
            $pk = 'ScopeID';
            $db = new TitleScopeStatic();
        }else if($TypeID ==2){
            $pk = 'PointID';
            $db = new TitlePointStatic();
        }else if($TypeID ==3){
            $pk = 'LevelID';
            $db = new TitleLevelStatic();
        }else if($TypeID ==4){
            $pk = 'TypeID';
            $db = new TitleTypeStatic();
        }
        if($db->save(['Name'=>$Name],[$pk=>$ID]) == 1){
            return $this->trueMsg("修改成功");
        }
        return $this->falseMsg("修改失败");

    }

    /**
     *获取当前全部数据
     */
    function get(){
        try{

            $LevelList = Db::table('cnes_title_level_static')->alias('ls')->field('ls.LevelID,ls.Name as LevelName')->where(['IsDel'=>0])->select();
            $PointList = Db::table('cnes_title_point_static')->alias('ps')->field('ps.PointID,ps.Name as PointName')->where(['IsDel'=>0])->select();
            $ScopeList = Db::table('cnes_title_scope_static')->alias('ss')->field('ss.ScopeID,ss.Name as ScopeName')->where(['IsDel'=>0])->select();
            $TypeList = Db::table('cnes_title_type_static')->alias('ts')->field('ts.TypeID,ts.Name as TypeName')->where(['IsDel'=>0])->select();

            $L['LevelList'] = $LevelList;
            $L['PointList'] = $PointList;
            $L['ScopeList'] = $ScopeList;
            $L['TypeList'] = $TypeList;
        }catch (Exception $e){
            return $e->getMessage();
        }
        return $this->trueMsg($L);
    }
}