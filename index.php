<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
//安装检查开始：如果您已安装过ThinkSNS，可以删除本段代码
if(is_dir('install') && !file_exists('install/install.lock')){
	header("Content-type: text/html; charset=utf-8");
	die ("<div style='border:2px solid green; background:#f1f1f1; width:800px;color:green;text-align:center;top: 40%;position: absolute;left: 50%;margin: -30px 0 0 -400px;'>"
		."<h1>您尚未安装tweibo系统，<a href='install/install.php'>请点击进入安装页面</a></h1>"
		."</div> <br /><br />");
}

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');
//定义项目名称和路径

define('ROOT', getcwd());
define('APP_NAME', 'tweibo2.0');

require_once '/Public/Paths/paths.php';
//error_reporting(0);
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';