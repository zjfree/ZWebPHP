<?php

error_reporting(E_ALL & ~E_NOTICE);
define('ROOT_PATH', dirname(dirname(__FILE__)) . '/');

$now = date('Y-m-d H:i:s');
$host = '_null';
$url = @$_SERVER['HTTP_REFERER'] ?: '';
if (!empty($url))
{
    $arr = parse_url($url);
    $host = $arr['host'];
}

if (!empty($_GET['host']))
{
    $host = trim($_GET['host']);
    $host = str_replace(['.', '/', '\\', ' '], '', $host);
}

$file = ROOT_PATH . '/stat/' . $host . '/stat.log';
$str = @file_get_contents($file);
$arr = [];
if (empty($str))
{
    mkdir(ROOT_PATH . '/stat/' . $host);
	$arr = [0,0,$now];
}
else
{
	$arr = explode(',', $str);
}

$arr[0]++;
$arr[1] = substr($arr[2], 0, 10) < date('Y-m-d') ? 0 : ($arr[1] + 1);
$arr[2] = $now;

@file_put_contents($file, implode(',', $arr));

$data = [
    'time'       => $now,
    'ip'         => $_SERVER['REMOTE_ADDR'],
    'user_agent' => @$_SERVER['HTTP_USER_AGENT'] ?: '',
    'url'        => $url,
    'query'      => @$_SERVER['QUERY_STRING'] ?: '',
];

@file_put_contents(ROOT_PATH . '/stat/' . $host . '/' . date('Ymd') . '.log', json_encode($data) . PHP_EOL, LOCK_EX|FILE_APPEND);

if (!empty($_GET['null']))
{
    exit;
}

// 输出svg
$out = $arr[0] . '/' . $arr[1];
$w = strlen($out) * 7 + 5;
header('Content-Type:image/svg+xml'); 
echo <<<EOF
<svg version="1.1"
    width="$w"
    height="15"
    viewBox="0 0 $w 15"
    preserveAspectRatio="none"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
>
    <text x="2" y="11" style="font-size:12px">$out</text>
</svg>
EOF;

exit;