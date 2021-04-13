<?php 

date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

$ip = $_SERVER['REMOTE_ADDR'];

if (!in_array($ip, ['::1', '127.0.0.1', 'localhost']))
{
	// 用户列表
	$user_list = ['admin' => 'zjfree'];
	
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	
	if ((@$user_list[$user] ?: '') !== $pass)
	{
		header('WWW-Authenticate: Basic realm="用户登录"');
		header('HTTP/1.0 401 Unauthorized');
		die("未登录");
	}
}

header('Content-type: text/plain; charset=utf-8');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(dirname(dirname(__FILE__))) . DS);

// 自动加载
require ROOT_PATH . "vendor/autoload.php";

$ms = microtime(true);

// 生成 EnumConst.php 文件
$enum_list = require ROOT_PATH . "app/enum.php";
$enum_const = '<?php' . PHP_EOL;
$enum_const .= '// 自动生成于 ' . date('Y-m-d H:i:s') . PHP_EOL;
$enum_const .= 'namespace app;' . PHP_EOL . PHP_EOL;
$enum_const .= 'class EnumConst' . PHP_EOL;
$enum_const .= '{' . PHP_EOL;
foreach ($enum_list as $k => $r)
{
	$str_item = '';
	foreach ($r['list'] as $kk => $rr)
	{
		if (isset($rr['tag']))
		{
			$const_name = strtoupper($k . '_' . $rr['tag']);
			$const_value = is_string($kk) ? "'$kk'" : $kk;
			$str_item .= "\tconst $const_name = $const_value;\t// " . $rr['name'] . PHP_EOL;
		}
	}
	if (!empty($str_item))
	{
		$enum_const .= "\t// " . $r['name'] . PHP_EOL;
		$enum_const .= $str_item;
		$enum_const .= PHP_EOL;
	}
}
$enum_const .= '}';

file_put_contents(ROOT_PATH . 'app/EnumConst.php', $enum_const);

echo 'EnumConst.php 自动成功' . PHP_EOL;

echo '开始生成API文档' . PHP_EOL . PHP_EOL;

$path = ROOT_PATH . 'app/api';
$fn_count = 0;
$class_list = [];
foreach (glob($path . '/*.php') as $file)
{
	echo $file . PHP_EOL;
	$item = [];
	$item['code'] = basename($file, '.php');
	$item['file'] = $file;
	$class = new \ReflectionClass('app\\api\\' . $item['code']);
	$instance = $class->newInstanceArgs();
	
	$item['need_login'] = $instance::_getNeedLogin();
	$item['doc'] = parse_class_doc($class);
	$item['fn_list'] = [];

	$fn_list = $class -> getMethods(ReflectionMethod::IS_PUBLIC);
	foreach ($fn_list as $fn)
	{
		$fn_name = $fn -> getName();
		if (strpos($fn_name, '_') === 0)
		{
			continue;
		}

		echo '  ' . $fn_name . PHP_EOL;
		$fn_doc = parse_method_doc($fn);
		$fn_params = $fn_doc['params'];
		unset($fn_doc['params']);
		$item['fn_list'][] = [
			'code'   => $fn_name,
			'doc'    => $fn_doc,
			'params' => $fn_params,
		];

		$fn_count++;
	}
	
	$class_list[] = $item;
}

// 写入文件
$res = [
	'build_time' => date('Y-m-d H:i:s'),
	'class_list' => $class_list,
];
file_put_contents(ROOT_PATH . 'app/api.json', json_encode($res, 384));

$ms = round((microtime(true) - $ms) * 1000);
echo PHP_EOL . 'API文档生成完成；';
echo '共' . count($class_list) . '个API类 ' . $fn_count . '个函数，用时' . $ms . 'ms。';

// 解析class注释
function parse_class_doc($class)
{
	$doc = [
		'name'   => basename($class -> getName()),
		'memo'   => '',
		'author' => '',
		'date'   => '',
	];

	$str = $class -> getDocComment();
	if (empty($str))
	{
		return $doc;
	}

	$str = trim($str);
	$str = str_replace(['/**', '*/', ' *'], '', $str);
	$arr = explode("\n", $str);
	$arr = array_map('trim', $arr);
	$arr1 = [];
	foreach ($arr as $r)
	{
		$r = trim($r);
		if (empty($r))
		{
			continue;
		}
		if (strpos($r, '@author') === 0)
		{
			$doc['author'] = str_replace('@author', '', $r);
			$doc['author'] = trim($doc['author']);
			continue;
		}

		if (strpos($r, '@date') === 0)
		{
			$doc['date'] = str_replace('@date', '', $r);
			$doc['date'] = trim($doc['date']);
			continue;
		}
		$arr1[] = $r;
	}
	$arr = $arr1;

	$doc['name'] = trim($arr[0]);
	if (count($arr) > 1)
	{
		$doc['memo'] = trim(implode(' ', array_slice($arr, 1)));
	}

	return $doc;
}

// 解析方法注释
function parse_method_doc($fn)
{
	$doc = [
		'name'   => basename($fn -> getName()),
		'memo'   => '',
		'author' => '',
		'date'   => '',
		'params' => [],
		'return' => '',
	];

	$doc_key_list = array_keys($doc);

	$str = $fn -> getDocComment();
	if (empty($str))
	{
		return $doc;
	}

	$str = trim($str);
	$str = str_replace(['/**', '*/', ' *'], '', $str);
	$arr = explode("\n", $str);
	$arr = array_map('trim', $arr);
	$arr1 = [];
	foreach ($arr as $r)
	{
		$r = trim($r);
		if (empty($r))
		{
			continue;
		}

		if (strpos($r, '@param') === 0)
		{
			$param = str_replace('@param', '', $r);
			$doc['params'][] = trim($param);
			continue;
		}
		foreach ($doc_key_list as $k)
		{
			if (strpos($r, '@' . $k) === 0)
			{
				$doc[$k] = str_replace('@' . $k, '', $r);
				$doc[$k] = trim($doc[$k]);
				continue 2;
			}
		}

		$arr1[] = $r;
	}
	$arr = $arr1;

	$doc['name'] = trim($arr[0]);
	if (count($arr) > 1)
	{
		$doc['memo'] = trim(implode(' ', array_slice($arr, 1)));
	}

	// 解析参数
	$param_list = [];
	foreach ($doc['params'] as $r)
	{
		$param = parse_param($r, $err);
		if (empty($param))
		{
			echo '  ERROR:' . $err . ' (' . $r . ')' . PHP_EOL;
		}
		else
		{
			$param_list[] = $param;
		}
	}
	$doc['params'] = $param_list;

	return $doc;
}

// 解析参数
function parse_param($param, &$err)
{
	// 格式：int ×abc 参数名 [默认值] 参数说明

	// 获取默认值
	$reg_default_val = '/(?<=\[)[^\]]+/';
	preg_match($reg_default_val, $param, $result);
	$default_val = null;
	if (!empty($result))
	{
		$default_val = $result[0];
		$param = preg_replace($reg_default_val, '', $param);
		$param = str_replace("[]", '', $param);
	}
	
	// 去除干扰字符
	$param = str_replace(["\t", '  ', '　'], ' ', $param);
	$param = str_replace('  ', ' ', $param);
	$param = trim($param);
	
	$arr = explode(' ', $param);
	if (count($arr) < 2)
	{
		$err = '参数数量不足';
		return null;
	}

	$type_list = [
		'int', 
		'string', 
		'float', 
		'date', 
		'time', 
		'any',
		'json',
		'array',
		'array_int',
		'code',
		'id',
	];

	if (!in_array($arr[0], $type_list))
	{
		$err = '参数类型错误[' . $arr[0] . ']';
		return null;
	}
	
	$item = [
		'code'    => str_replace('+', '', $arr[1]),
		'name'    => $arr[2] ?: '',
		'type'    => $arr[0],
		'require' => strpos($arr[1], '+') === 0,
		'value'   => $default_val,
		'memo'    => trim(implode(' ', array_slice($arr, 3))),
	];

	$err = '';

	return $item;
}
