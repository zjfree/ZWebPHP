<?php

date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

if ($_FILES["img_file"]["type"] != "image/jpeg")
{
    echo json_encode([
        'result' => 0,
        'error' => '文件类型错误',
    ]);
    exit;
}

if ($_FILES["img_file"]["size"] > 1024 * 1024)
{
    echo json_encode([
        'result' => 0,
        'error' => '图片文件太大了',
    ]);
    exit;
}

if ($_FILES["img_file"]["error"] > 0)
{
    echo json_encode([
        'result' => 0,
        'error' => "Return Code: " . $_FILES["img_file"]["error"],
    ]);
    exit;
}

$file_path = $_SERVER['DOCUMENT_ROOT'];
$url_path = '/static/upload/' . date('Ymd');

@mkdir($file_path . $url_path, 0777, true);

$guid = strtoupper(md5(uniqid(mt_rand(), true)));
$url_path .= '/' . date('His_') . $guid . '.jpg';
$file_path .= $url_path;

move_uploaded_file($_FILES["img_file"]["tmp_name"], $file_path);

echo json_encode([
    'result'  => 1,
    'img_url' => $url_path,
]);
