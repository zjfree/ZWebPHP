<?php
namespace app\api;

use zphp\ApiBase;
use zphp\DB;
use zphp\Tool;

/**
 * 本地服务
 */
class LocalService extends ApiBase
{
	protected static $need_login = false;

	// 运行用时
	private static $ms = null;

    /**
     * 初始化
     */
    public static function _init($api, $params = [])
    {
		self::$ms = Tool::ms();

		// 限制本机访问、超级管理员访问
		$ip = Tool::ip();
		$server_ip = $_SERVER['SERVER_ADDR'];
		$host_ip = gethostbyname($_SERVER['HTTP_HOST']);
		
		if (in_array($ip, ['127.0.0.1', '::1', $server_ip, $host_ip]))
		{
			return self::_success();
		}
		
		$user = User::current();
		if ($user != null && $user['id'] == 1)
		{
			return self::_success();
        }
		
		return self::_error('无权限 ' . $ip);
    }

	/**
	 * 测试
	 */
	public static function test($params)
	{
		// 处理

		echo '执行成功！' . Tool::ms(self::$ms) . 'ms';
		return self::_exit();
	}
}


