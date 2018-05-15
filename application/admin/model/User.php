<?php
/**
 * Created by PhpStorm.
 * UserModel: en_zhao
 * Date: 2018/5/6
 * Time: 14:52
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class User extends Model
{

    /**
     * 查找
     */
//    function search(){
//
//    }

    /**
     * 删除
     */
//    function del(){
//
//    }

    /**
     * 修改 继承父类放阿飞
     */
//    function save(){
//
//    }

    /**
     * 增加
     */
//    function add(){
//
//    }

    /**
     * 获取用户信息 存入redis
     * @param $UserID
     */
    function getUserInfo($UserID){
        $res = Db::query('select u.UserID , u.Name , u.Sex , u.Major , u.Tel , u.SNO , u.Email , a.Name as Actor , a.ActorID  from cnes_user as u , cnes_actor_user_link as aul , cnes_actor_static as a where u.UserID = ? and u.UserID = aul.UserID and aul.ActorID = a.ActorID',[$UserID]);
        return $res;

    }

}