<?php

// 系统入口
date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

// 常量定义
define('SPHP', '1.0');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__FILE__) . DS);

// session_start();

header('Content-type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// 自动加载
function class_autoload($class_name)
{
    $class_path = ROOT_PATH . 'classes' . DS . "$class_name.php";
    if (is_file($class_path))
    {
        require_once $class_path;
    }
}

spl_autoload_register('class_autoload');

// config配置
Sys::$config = require ROOT_PATH . 'config.php';

// 获取登录用户
if (!Sys::_login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
{
    header('WWW-Authenticate: Basic realm="用户登录"');
    header('HTTP/1.0 401 Unauthorized');
    die("未登录");
}

// 是否ajax请求
$is_ajax = false;
if ($_GET['_ajax'] === '1' || strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH'] ?: '') == 'xmlhttprequest')
{
    $is_ajax = true;
}

// 执行命令
$cmd = @$_REQUEST['_'] ?: 'sys::index';
$cmd = str_replace(['.', '/', '\\'], '', $cmd);
$cmd_arr = explode('::', $cmd);
if (count($cmd_arr) == 1)
{
    $cmd_arr[] = 'index';
}
$controller = explode('_', $cmd_arr[0]);
$controller = array_map('ucfirst', $controller);
$controller = implode('', $controller);
$controller_class = $controller . 'Controller';

$view = $cmd_arr[1];

$controller_file_path = ROOT_PATH . 'controller' . DS . $controller . '.php';
$res = null;
if (is_file($controller_file_path))
{
    require $controller_file_path;
    if (method_exists($controller_class, $view))
    {
        $res = $controller_class::$view();
    }
    else
    {
        $res = Sys::_error('cmd[' . $cmd . '] not find!');
    }
}
else
{
    $res = Sys::_error('controller[' . $controller . '] not find!');
}

if ($is_ajax)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($res);
    exit;
}

$view_file_path = ROOT_PATH . 'view' . DS . $controller . DS . $view . '.html';

if (is_file($view_file_path))
{
    require $view_file_path;
}

exit;