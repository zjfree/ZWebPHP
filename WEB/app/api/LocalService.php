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
		$user = User::current();
		if ($ip != '127.0.0.1' && $ip != '::1' && $user['id'] != 1)
		{
			return self::_error('无权限 ' . $ip);
        }
        
        return self::_success();
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


