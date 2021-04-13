<?php

date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

session_start();

header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$w = @$_GET['w'] ?: 100;
$h = @$_GET['h'] ?: 30;
$k = @$_GET['k'] ?: 'img';

$session_key = 'vcode_' . $k;

// 获取随机字符
$code_len = 4;
$chars = '0123456789ABCEFGHJKMNPQRSTUVWXYZ';
$code = '';

$len = strlen($chars) - 1;
for ($i = 0; $i < $code_len; $i++)
{  
    $code .= $chars[mt_rand(0, $len)];  
}

// 绘制背景
$img = imagecreatetruecolor($w, $h);
$color = imagecolorallocate($img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
imagefilledrectangle($img, 0, 0, $w, $h, $color);

// 干扰线
for ($i=0; $i<6; $i++) {
    $color = imagecolorallocate($img, mt_rand(0,156), mt_rand(0,156), mt_rand(0,156));
    imageline($img, mt_rand(0, $w), mt_rand(0, $h), mt_rand(0, $w), mt_rand(0, $h), $color);
}
for ($i=0; $i<100; $i++) {
    $color = imagecolorallocate($img, mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));
    imagestring($img, mt_rand(1,5), mt_rand(0, $w), mt_rand(0, $h), '*', $color);
}

$font = realpath('./Aregato.ttf');
$_x = ($w - 10) / $code_len;
for ($i=0; $i<$code_len; $i++)
{
    $font_color = imagecolorallocate($img, mt_rand(0,156), mt_rand(0,156), mt_rand(0,156));
    imagettftext($img, mt_rand(16, 24), mt_rand(-30,30), $_x * $i + mt_rand(1,5), $h / 1.2, $font_color, $font, $code[$i]);
}

// 保存SESSION
$_SESSION[$session_key] = $code;

// 输出图片
ob_clean();
header('Content-type:image/png');
imagepng($img);
imagedestroy($img);
exit;