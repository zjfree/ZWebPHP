<?php

// 系统入口
date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__FILE__) . DS);

// 登录
if ($_SERVER['PHP_AUTH_USER'] != 'admin' || $_SERVER['PHP_AUTH_PW'] != md5(md5(md5($_SERVER['HTTP_HOST'] . date('Ymd') . 'zbase'))))
{
    header('WWW-Authenticate: Basic realm="用户登录"');
    header('HTTP/1.0 401 Unauthorized');
    die("未登录");
}

$cur_path = $_GET['path'] ?: dirname(__FILE__);
$cur_path = realpath($cur_path);
if (is_file($cur_path))
{
    header('Content-type: text/plain; charset=utf-8');
    echo file_get_contents($cur_path);
    exit;
}

$pre_path = dirname($cur_path);
$list = glob($cur_path . DS . '*');

header('Content-type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<head>
<title>文件管理</title>
</head>
<body style="font-family: consolas">
    <div><?=$cur_path?></div>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <td>TYPE</td>
            <td><a href='zfile.php?path=<?=urlencode($pre_path) ?>'>..</a></td>
            <td>SIZE</td>
        </tr>
        <?php foreach ($list as $r) { $t = filetype($r); ?>
            <tr>
                <td><?=$t?></td>
                <td><a target="<?=($t=='file'?'_blank':'')?>" href='zfile.php?path=<?=urlencode($cur_path) ?><?=DS.urlencode(basename($r))?>'><?=basename($r)?></a></td>
                <td><?=round(filesize($r)/1024)?>k</td>
            </tr>
        <?php } ?>
    </table>
</body>