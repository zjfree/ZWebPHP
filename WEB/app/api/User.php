<?php
namespace app\api;

use zphp\ApiBase;
use zphp\User as SysUser;

/**
 * 用户
 */
class User extends ApiBase
{
	protected static $need_login = false;

	/**
	 * 登录
	 * @param string account 账号
	 * @param string passwd 密码
	 */
	public static function login($params = [])
	{
		if (!empty($params['account']))
		{
			// $params['passwd']
			list($user, $err) = SysUser::login($params['account'], $params['passwd']);
			if (!empty($user))
			{
				return self::_success(true, ['url' => 'location:/']);
			}

			return self::_success([
				'account' => $params['account'],
				'error'   => $err,
			]);
		}

		return self::_success();
	}
	
	/**
	 * 退出
	 */
	public static function logout($params = [])
	{
		SysUser::logout();

		return self::_success(null, ['url' => 'location:/']);
	}
	
	/**
	 * 注册
	 */
	public static function register($params = [])
	{
		return self::_success();
	}
}


