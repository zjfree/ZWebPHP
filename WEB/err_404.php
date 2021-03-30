<?php

// 系统入口
// 禁止规则：1. 关键字禁止 2. 10秒内触发3个不同的404

date_default_timezone_set("PRC");
error_reporting(0);
set_time_limit(1);

header('Content-type: text/html; charset=utf-8');

$ip = $_SERVER['REMOTE_ADDR'];
$url = $_SERVER['REQUEST_URI'];

$bo = '';

// 判断页面关键字
$key_list = ['.rar', 'admin.php', '.sql', '.json', '.7z', '.xml', '.ini', '/../..'];
$url1 = strtolower($url);
foreach ($key_list as $k)
{
	if (strpos($url1, $k) !== false)
	{
		$bo = 'key[' . $k . ']';
		break;
	}
}

$time = date('Y-m-d H:i:s');
$time1 = time();

if ($bo == '')
{
	// 处理IP
	$url_md5 = md5($url);
	$ip_file = 'err_404/ip_' . date('Ymd') . '.dat';
	$ip_list = @file_get_contents($ip_file) ?: '';
	$ip_list = trim($ip_list);
	$ip_list = $ip_list == '' ? [] : explode("\n", $ip_list);
	$has = false;
	for ($i=0; $i<count($ip_list); $i++)
	{
		$arr = explode(',', $ip_list[$i]);
		
		if (count($arr) != 5)
		{
			continue;
		}

		if ($arr[1] == $ip)
		{
			$has = true;
			$ip_list[$i] = $time . ',' . $ip;
			if ($url_md5 != $arr[4] && $time - strtotime($arr[0]) < 10)
			{
				$ip_list[$i] .= ',' . ($arr[2] + 1);
				if ($arr[2] + 1 > 3)
				{
					$bo = 'limit';
				}
			}
			else
			{
				$ip_list[$i] .= ',1';
			}
			$ip_list[$i] .= ',' . ($arr[3] + 1);
			$ip_list[$i] .= ',' . $url_md5;
			break;
		}
	}

	if (!$has)
	{
		$ip_list[] = $time . ',' . $ip . ',1,1,' . $url_md5;
	}

	@file_put_contents($ip_file, implode("\n", $ip_list));
}

if ($bo)
{
	@file_put_contents('err_404/ip_list.dat', $time . ',' . $time1 . ",\t" . $ip . "\n", FILE_APPEND|LOCK_EX);
	@file_put_contents('err_404/log_' . date('Ymd') . '.dat_log', $time . ',' . $ip . ',' . $bo . "\n", FILE_APPEND);
}

$list = [
	'访问时间' => date('Y-m-d H:i:s'),
	'访问域名' => $_SERVER['HTTP_HOST'],
	'访问地址' => $url,
	'来源IP'   => $ip,
	'来源地址' => $_SERVER['HTTP_REFERER'],
	'浏览器'   => $_SERVER['HTTP_USER_AGENT'],
];

?>

<h1>404 访问的资源不存在</h1>
<table>
<?php foreach ($list as $k => $v){ ?>
<tr>
<th><?= $k ?></th>
<td><?= $v ?></td>
</tr>
<?php } ?>
<?php if ($bo) { ?>
<tr>
<th>访问限制</th>
<td>禁止访问</td>
</tr>
<?php } ?>
</table>

<style>
th{
	text-align: right;
}
</style>