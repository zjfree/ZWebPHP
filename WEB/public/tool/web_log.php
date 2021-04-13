<?php

set_time_limit(1);

$file_path = '../../runtime/log/web_log_' . date('Ymd') . '.log';

$str = 'url:' . (@$_SERVER['HTTP_REFERER'] ?: '') . PHP_EOL;
$str .= 'user_agent:' . (@$_SERVER['HTTP_USER_AGENT'] ?: '') . PHP_EOL;
// $str .= @$_REQUEST['content'] ?: '';

$raw = file_get_contents("php://input");
if (!empty($raw))
{
	$str .= $raw;
}
else
{
	$str .= json_encode($_REQUEST);
}
$str = date('Y-m-d H:i:s ') . '[web_log] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL . $str . PHP_EOL . PHP_EOL;

file_put_contents($file_path, $str, FILE_APPEND | LOCK_EX);

//无视请求断开
ignore_user_abort();

header('HTTP/1.1 200 OK');
header('Content-Length:0');
header('Connection:Close');

flush();