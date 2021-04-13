<?php

//header("Access-Control-Allow-Origin:*");

$data = $_REQUEST;

$res = [
	'result' => 1,
	'data'   => $data,
	'reload' => false,
	'go_url' => '',
	'error'  => '',
];

if (rand(1, 100) < 20)
{
	$res = [
		'result' => -1,
		'error'  => '服务器处理错误',
	];
}

echo json_encode($res);