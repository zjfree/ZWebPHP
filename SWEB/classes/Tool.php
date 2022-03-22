<?php

class Tool
{
	// 加密用GUID
	const GUID = '01063BF82282BD7A13EB224A3E836D4D';

    /**
     * 写入日志
     */
    public static function addLog($str, $type = 'log')
    {
        if (!is_string($str))
        {
            $str = (is_array($str) ? PHP_EOL : '') . var_export($str, true) . PHP_EOL;
        }

		$file = ROOT_PATH . 'runtime/log/' . $type . '_' . date('Ymd') . '.log';
		$str = date('Y-m-d H:i:s ') . $str . PHP_EOL;

		@file_put_contents($file, $str, FILE_APPEND|LOCK_EX);
    }
    
    /**
     * 当前时间
     */
	public static function now()
	{
		return date('Y-m-d H:i:s');
	}
	
    /**
     * 毫秒数
     */
    public static function ms($ms_base = 0)
    {
        $ms = round(microtime(true) * 1000);

        return $ms - $ms_base;
	}
	
    /**
     * 获取GUID
     */
	public static function guid($split = '')
	{
		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
		if (empty($split))
		{
			return $charid;
		}

		$uuid = ''
			. substr($charid,  0,  8) . $split
			. substr($charid,  8,  4) . $split
			. substr($charid, 12,  4) . $split
			. substr($charid, 16,  4) . $split
			. substr($charid, 20, 12);

		$uuid = strtoupper($uuid);

		return $uuid;
	}
	
    /**
     * 获取客户端IP地址
     */
	public static function ip()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	
    /**
     * 循环检测并创建文件夹
     */
	public static function createDir($path)
	{
		mkdir($path, 0777, true);
	}

    /**
     * MD5 加密
     */
	public static function md5($str)
	{
		for ($i=0; $i<5; $i++)
		{
			$str = md5($str . $i . self::GUID);
		}

		$str = strtoupper($str);

		return $str;
	}

}