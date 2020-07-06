<?php
namespace zphp;

/**
 * 系统工具类
 */
class Tool
{
	// 加密用GUID
	const GUID = 'E0830964B89F516D05E94E3FC0CBF6E6';

    /**
     * JSON字符串
     */
	public static function json($data, $option = null)
	{
		if (empty($option))
		{
			$option = JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT;
		}
		
		$res = json_encode($data, $option);
		
		if ($res !== false)
		{
			$res .= "\r\n";
		}

		return $res;
	}

    /**
     * JSON解析
     */
	public static function jsonParse($str)
	{
		$str = trim($str);

		return json_decode($str, true);
	}
	
    /**
     * 当前时间
     */
	public static function now()
	{
		return Tool::date('Y-m-d H:i:s');
	}
	
    /**
     * 休眠
     */
	public static function sleep($ms)
	{
		usleep($ms * 1000);
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
     * URL安全BASE64
     */
	public static function urlsafe_b64encode($string)
	{
		$data = base64_encode($string);
		$data = str_replace(['+','/','='], ['-','_',''], $data);

		return $data;
	}
	
    /**
     * URL安全BASE64
     */
	public static function urlsafe_b64decode($string)
	{
		$data = str_replace(['-','_'], ['+','/'], $string);
		$mod4 = strlen($data) % 4;

		if ($mod4)
		{
			$data .= substr('====', $mod4);
		}

		return base64_decode($data);
	}

    /**
     * 获取GUID
     */
	public static function guid_short()
	{
		$s = md5(uniqid(mt_rand(), true), true);
		$s = self::urlsafe_b64encode($s);

		return $s;
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

    /**
     * 获取当前URL
     */
	public static function url() 
	{
		$pageURL = 'http';

		if ($_SERVER["HTTPS"] == "on") 
		{
			$pageURL .= "s";
		}

		$pageURL .= "://";

		if ($_SERVER["SERVER_PORT"] != "80") 
		{
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}

		return $pageURL;
	}

    /**
     * 获取客户端IP地址
     */
	public static function ip()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

    /**
     * 是否本地访问（局域网/本机）
     */
	public static function isLocal()
	{
		$ip = Tool::ip();
		if ($ip == '127.0.0.1' || $ip == '::1')
		{
			return true;
		}

		$server_ip = $_SERVER['SERVER_ADDR'];
		if ($ip == $server_ip)
		{
			return true;
		}

		foreach (['127.', '10.', '192.'] as $r)
		{
			if (strpos($ip, $r) === 0)
			{
				return true;
			}
		}
		
		return false;
	}
	
    /**
     * 循环检测并创建文件夹
     */
	public static function createDir($path)
	{
		mkdir($path, 0777, true);
	}
	
    /**
     * 循环删除文件夹
     */
	public static function delDir($src)
	{
		 if (!file_exists($src))
		 {
			 return;
		 }
		 
		 $dir = opendir($src);
		 while (false !== ($file = readdir($dir)))
		 {
			if (($file != '.') && ($file != '..'))
			{
				$full = $src . '/' . $file;
				if (is_dir($full))
				{
					self::delDir($full);
				}
				else
				{
					@unlink($full);
				}
			}
		}
		closedir($dir);
		@rmdir($src);
	 }

	/**
	 * 时长格式化
	 */
	public static function timespanFormat($seconds)
	{
		$duration = '';

		$seconds  = (int) $seconds;
		if ($seconds <= 0) {
			return $duration;
		}

		list($day, $hour, $minute, $second) = explode(' ', gmstrftime('%j %H %M %S', $seconds));

		$day -= 1;
		if ($day > 0) {
			$duration .= (int) $day.'天';
		}
		if ($hour > 0) {
			$duration .= (int) $hour.'小时';
		}
		if ($minute > 0 && $day == 0) {
			$duration .= (int) $minute.'分钟';
		}
		if ($second > 0 && $day == 0 && $hour == 0) {
			$duration .= (int) $second.'秒';
		}

		return $duration;
	}

	/**
	 * 字节长度格式化输出
	 */
	public static function byteFormat($byte)
	{
		if (empty($byte))
		{
			return '0';
		}
		$type_list = ["B", "K", "M", "G", "T"];
		$counter = 0;
		while($byte >= 1024)
		{
			$byte /= 1024.0;
			$counter++;
		}

		if ($byte > 10)
		{
			$byte = floor($byte);
		}
		else
		{
			$byte = round($byte, 2);
		}
		
		return $byte . $type_list[$counter];
	}

	/**
	 * 获取所有磁盘
	 */
	public static function getDisks()
	{ 
		$disks = [];
		if (php_uname('s') == 'Windows NT')
		{ 
			// windows 
			$disks = `fsutil fsinfo drives`; 
			$disks = str_word_count($disks, 1);

			$disks = array_map(function($v){
				return $v.':/';
			}, $disks);
		}
		else
		{
			$disks[] = './';
		}

		return $disks; 
	} 
	
	/**
	 * 获取所有磁盘大小
	 */
	public static function getDiskSize()
	{ 
		$disk_list = [];
		
		$disks = self::getDisks();
		foreach ($disks as $disk)
		{
			$total = disk_total_space($disk);
			$free = disk_free_space($disk);
			$disk_list[] = [
				'name'    => $disk,
				'total'   => $total,
				'free'    => $free,
				'use'     => $total - $free,
				'precent' => $total == 0 ? 0 : round(($total - $free) / $total * 100, 2),
			];
		}

		return $disk_list; 
	} 

	/**
	 * 追踪代码位置
	 */
	public static function backtrace()
	{
		$list = debug_backtrace();

		$str = '[Stack trace]:' . PHP_EOL;
		$i = 0;
		foreach ($list as $r)
		{
			$str .= '#' . $i . ' ';

			$str .= empty($r['file']) ? '[internal function]: ' : ($r['file'] . '(' . $r['line'] . '): ');
			$str .= $r['class'] . $r['type'] . $r['function'] . '(';
			foreach ($r['args'] as &$rr)
			{
				if (is_string($rr))
				{
					if (mb_strlen($rr) > 10)
					{
						$rr = "'" . \substr($rr, 0, 10) . "...'";
					}
					else
					{
						$rr = "'" . $rr . "'";
					}
				}
				else if (is_array($rr))
				{
					$rr = '[Array]';
				}
				else if (is_object($rr))
				{
					$rr = '[Object]';
				}
			}
			unset($rr);

			$str .= implode(', ', $r['args']) . ')' . PHP_EOL;

			$i++;
		}
		$str .= '#' . $i . ' {main}' . PHP_EOL;
		
		return $str;
	}

	/**
	 * 日期加减
	 */
	public static function addDay($dt, $span)
	{
		$dt = Tool::strtotime($dt);

		$dt += $span * 3600 * 24;

		return Tool::date('Y-m-d', $dt);
	}

	/**
	 * 替换系统 strtotime, Y2K38问题
	 */
	public static function strtotime($dt = null, $modify = '')
	{
		$d = null;
		if (empty($dt))
		{
			$d = new \DateTime();
		}
		else if (\is_numeric($dt))
		{
			$d = new \DateTime('@' . $dt);
		}
		else
		{
			$d = new \DateTime($dt);
		}

		$d -> setTimeZone(new \DateTimeZone('PRC'));
		if ($modify != '')
		{
			$d -> modify($modify);
		}

		return $d -> format('U');
	}

	/**
	 * 替换系统 date, Y2K38问题
	 */
	public static function date($format = 'Y-m-d H:i:s', $dt = null)
	{
		$d = new \DateTime('@' . Tool::strtotime($dt));
		$d -> setTimeZone(new \DateTimeZone('PRC'));

		return $d -> format($format);
	}

	/**
	 * 解析编码（表名，字段，变量）
	 */
	public static function parseCode($code)
	{
		$arr = explode('.', $code);
		if (count($arr) != 2)
		{
			return false;
		}

		if ($code != self::getCode($arr[0]))
		{
			return false;
		}

		$code = $arr[0];

		$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890_';

		$bo = true;
		for ($i=0; $i<strlen($code); $i++)
		{
			if (strpos($str, $code[$i]) === false)
			{
				$bo = false;
				break;
			}
		}

		if (!$bo)
		{
			return false;
		}

		return $code;
	}

	/**
	 * 生成验证CODE
	 */
	public static function getCode($code)
	{
		$str = $code . '.' . self::GUID;
		$str = md5(md5($str));

		$code = $code . '.' . \substr($str, 0, 8);

		return $code;
	}

	/**
	 * 生成验证ID
	 */
	public static function getId($id, $table)
	{
		$str = $id . '.' . $table . '.' . self::GUID;
		$str = md5(md5($str));

		$code = $id . '.' . $table . '.' . \substr($str, 0, 8);

		return $code;
	}

	/**
	 * 解析ID
	 */
	public static function parseId($str, $table)
	{
		if ($str == '0' || empty($str))
		{
			return 0;
		}
		$arr = explode('.', $str);
		if (count($arr) != 3)
		{
			return false;
		}

		if ($str != self::getId($arr[0], $arr[1]))
		{
			return false;
		}

		if ($arr[1] != $table)
		{
			return false;
		}

		return intval($arr[0]);
	}
	
	/**
	 * post提交数据
	 */
    public static function postUrl($url, $params = [])
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $html = null;
        try
        {
            $html = curl_exec($ch);
            curl_close($ch);
        }
        catch (\Exception $ex)
        {
			$html = null;
        }

        return $html;
    }
}