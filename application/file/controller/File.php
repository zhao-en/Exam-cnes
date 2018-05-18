<?php
/**
 * Created by PhpStorm.
 * User: en_zhao
 * Date: 2018/5/17
 * Time: 17:54
 */

namespace app\file\controller;


use think\Controller;

class File extends Controller
{
    /**
     * 上传文件
     * @return array
     */
    function add($Title=null, $Content=null){

        //获取当前时间
        $NTime = time();
        //参数检测
        if(!$this->testParams([$Title,$Content]) || empty($_FILES)){
            return $this->falseMsg("请填写完整信息");
        }

        //TODO 文件检测...

        //保存文件
        $type = explode('.',$_FILES["File"]["name"]);
        $type = $type[count($type) - 1];
        $src = 'file_'.$NTime.'.'.$type;
        //存储图片
        move_uploaded_file($_FILES["File"]["tmp_name"], __FILES__ . $src);


        return $this->trueMsg("上传成功");
    }

    /**
     * 获取资料列表 分页
     */
    function get(){

    }
}