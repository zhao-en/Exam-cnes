<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
//解决跨域问题
header('Access-Control-Allow-Origin:*');//允许所有来源访问

header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

error_reporting(0);

/**
 * 进行权限判定
 */
require './permission.php';

//定义根目录
define('__ROOT__',dirname(__DIR__));
//照片目录
define('__IMG__',__DIR__.'/../view/img/');
//文件目录
define('__FILES__',__DIR__.'/../view/file/');
//配置文件目录
define('CONF_PATH', __DIR__ . '/../conf/');
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
