<?php

// IP访问限制
function ip_limit_check()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip = str_replace([':', '.'], '_', $ip);
    
    $ip_path = '../runtime/ip/ip_' . $ip . '.dat';
    $content = @file_get_contents($ip_path);
    $content = trim($content);
    if ($content == 'pass')
    {
        return;
    }
    if ($content == 'limit')
    {
        exit('ERROR:ip limit');
    }
    
    $arr = empty($content) ? [0,0,0,date('Y-m-d H:i:s')] : explode(',', $content);
    
    $time = round(microtime(true) * 1000);
    $num = $arr[1] + 1 - ($time - $arr[0]) / 1000;
    $arr[0] = $time;
    $arr[1] = max($num, 0);
    $arr[2] = $arr[2] + 1;
    file_put_contents($ip_path, implode(',', $arr));
    
    if ($arr[1] > 300)
    {
        exit('ERROR:ip limit ' . $arr[1]);
    }

    return $arr;
}

var_dump(ip_limit_check());
exit;


