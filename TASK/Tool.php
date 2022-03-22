<?php

class Tool
{
    public static function show($str)
    {
        echo date('H:i:s ') . $str . PHP_EOL;
    }

    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    public static function addLog($str, $type = 'log')
    {
        self::show($str);

        $file = dirname(__FILE__) . '/log/' . $type . '_' . date('Y-m-d') . '.log';
        $log = date('Y-m-d H:i:s ') . $str . PHP_EOL;

        file_put_contents($file, $log, FILE_APPEND|LOCK_EX);
    }

    public static function cmd($cmd)
    {
        if (substr(php_uname(), 0, 7) == "Windows")
        {
            $str = "start /b " . $cmd . '>nul';
            popen($str, "r"); 
            //exec($str);
        }
        else
        {
            exec($cmd . " > /dev/null &");  
        }
    }

    public static function urlGet($url, $timeout = 10)
    {
        // 初始化
        $curl = curl_init();
        // 设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 超时时间
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        // 执行命令
        $data = curl_exec($curl);
        // 关闭URL请求
        curl_close($curl);

        // 显示获得的数据
        return $data;
    }

    public static function killTask($str)
    {
        for ($i=0; $i<10; $i++)
        {
            $str = str_replace('  ', ' ', $str);
        }

        $arr = explode(' ', $str);

        if (count($arr) < 2)
        {
            return;
        }

        exec('kill -9 ' . $arr[1]);
    }
}