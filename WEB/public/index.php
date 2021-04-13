<?php

// 系统入口
date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

// 开始运行时间和内存使用
define('ZPHP', '2.0');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(dirname(__FILE__)) . DS);

// 自动加载
require ROOT_PATH . "vendor/autoload.php";

zphp\Sys::webStart();
exit;
