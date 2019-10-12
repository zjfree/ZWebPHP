<?php

date_default_timezone_set("PRC");
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(30);

header('Content-type: application/json; charset=utf-8');

class ApiSDK
{
    const SERVER_URL = 'http://www.z14.com/api.php'; // 服务器地址
    const ACCOUNT = 'abc';
    const KEY = '6D4449C77EE12F71A0AC8F692D24CBBA';

    // 调用API
    public static function call($api, $params = [])
    {
        $params['_'] = $api;
        $params['_account'] = self::ACCOUNT;
        
        // 生成KEY
        $time = date('ymdHi');
		$key = $time . self::KEY;
		for ($i=0; $i<5; $i++)
		{
			$key = md5($key);
		}
        $params['_key'] = $time . substr($key, 0, 6);
        
        $res = self::postUrl(self::SERVER_URL, $params);
        
        return $res;
    }
    
    // post提交数据
    private static function postUrl($url, $params)
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $data = null;
        try
        {
            $html = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($html, true);
            if (empty($data))
            {
                $data = [
                    'result' => 0,
                    'error'  => '网络异常' . PHP_EOL . $html,
                ];
            }
        }
        catch (Exception $ex)
        {
            $data = [
                'result' => 0,
                'error'  => $ex -> getMessage(),
            ];
        }

        return $data;
    }
}

// 调用测试
$ms = round(microtime(true) * 1000);
$res = ApiSDK::call('sys::index');
$ms = round(microtime(true) * 1000) - $ms;

var_dump($res);

echo '用时：' . $ms . 'ms';