<?php
namespace zphp;

use app\EnumConst;

class User
{
	// 当前用户
	private static $cur_user = false;

	/**
	 * 获取当前用户
	 */
	public static function current()
	{
		if (self::$cur_user !== false)
		{
			return self::$cur_user;
		}

		self::$cur_user = @$_SESSION['current_user'] ?: null;

		if (empty(self::$cur_user))
		{
			return null;
		}

		// 检测是否过期
		$user_login = DB::table('user_login') -> find(self::$cur_user['login_id']);
		if (empty($user_login) || $user_login['online_status'] != 1)
		{
			$_SESSION['current_user'] = null;
			self::$cur_user = null;

			return null;
		}

		if ($user_login['reload'] == 1)
		{
			// 重新加载用户
			self::loadUser($user_login);
		}

		// 记录登录信息
		DB::table('user_login')
			-> where('id', self::$cur_user['login_id'])
			-> update([
				'last_time'  => '::now()',
				'view_count' => '::view_count+1',
				'reload'     => 0,
			]);
		
		return self::$cur_user;
	}

	/**
	 * 获取密码
	 */
	public static function getPassword($user_id, $passwd, $md5_count = 5)
	{
		$pwd = $passwd;
		for ($i=0; $i<$md5_count; $i++)
		{
			$pwd = md5($pwd . 'password_md5_salt');
		}
		
		return Tool::md5('user_' . $user_id . '_' . $pwd);
	}

	// 初始化超级管理员
	private static function initSuperAdmin()
	{
		// 创建系统超级管理员
		DB::table('user_type')
			-> insert([
				'id'          => 1,
				'code'        => 'super_admin',
				'name'        => '超级管理员',
				'power'       => '',
				'menu'        => '',
				'is_hide'     => 1,
				'add_time'    => '::now()',
				'update_time' => '::now()',
				'memo'        => '',
			]);
			
		DB::table('user')
			-> insert([
				'id'           => 1,
				'guid'         => Tool::guid(),
				'user_type_id' => 1,
				'name'         => '超级管理员',
				'account'      => 'super_admin',
				'password'     => self::getPassword(1, @$_SERVER['HTTP_HOST'] ?: 'super_admin'),
				'status'       => 1,
				'config_json'  => '',
				'add_time'     => '::now()',
				'update_time'  => '::now()',
				'memo'         => '',
			]);
	}

	// 加载用户
	private static function loadUser($user_login)
	{
		$user = DB::table('user') -> json() -> find($user_login['user_id']);

		// 删除密码
		unset($user['password']);

		// 解析权限
		$user['user_type'] = DB::table('user_type') -> find($user['user_type_id']);
		$user['power_list'] = empty($user['user_type']['power']) ? [] : explode(',', $user['user_type']['power']);

		// 解析菜单
		$menu_list = require ROOT_PATH . 'app/menu.php';
		if ($user['user_type_id'] == 1)
		{
			$user['menu_list'] = $menu_list;
		}
		else
		{
			$user['menu_list'] = [];
			$user_menu_list = empty($user['user_type']['menu']) ? [] : explode(',', $user['user_type']['menu']);
			foreach ($menu_list as $k => $r)
			{
				if (!in_array($k, $user_menu_list))
				{
					continue;
				}

				if (isset($r['list']))
				{
					$sub_list = [];
					foreach ($r['list'] as $kk => $rr)
					{
						if (in_array($k . '.' . $kk, $user_menu_list))
						{
							$sub_list[$kk] = $rr;
						}
					}
					if (!empty($sub_list))
					{
						$r['list'] = $sub_list;
						$user['menu_list'][$k] = $r;
					}
				}
				else
				{
					$user['menu_list'][$k] = $r;
				}
			}
		}
		
		$user['login_id']   = $user_login['id'];
		$user['login_guid'] = $user_login['guid'];
		$user['login_time'] = $user_login['add_time'];

		self::$cur_user = $user;
		$_SESSION['current_user'] = $user;

		return $user;
	}

	/**
	 * 登录
	 */
	public static function login($account, $password)
	{
		$user = DB::table('user')
			-> where('account', $account)
			-> where('status', '<>', 2)
			-> find();

		if (empty($user))
		{
			if ($account == 'super_admin')
			{
				self::initSuperAdmin();
				$user = DB::table('user') -> find(1);
			}
			else
			{
				return [null, '用户不存在'];
			}
		}

		$password = self::getPassword($user['id'], $password);
		if ($user['password'] != $password)
		{
			return [null, '密码不正确'];
		}

		if ($user['status'] != 1)
		{
			return [null, '账号被禁用'];
		}

		// 记录登录信息
		$now = Tool::now();
		$user_login = [
			'user_id'       => $user['id'],
			'guid'          => Tool::guid(),
			'ip'            => Tool::ip(),
			'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
			'online_status' => 1,
			'last_time'     => $now,
			'view_count'    => 1,
			'reload'        => 0,
			'add_time'      => $now,
		];

		$user_login['id'] = DB::table('user_login') -> insert($user_login);

		// 加载当前用户
		$user = self::loadUser($user_login);

		// 记录日志
		$str_log = '用户【' . $user['name'] . ' ' . $user['account'] . '】登录 ' . '(' . $user['login_id'] . ', ' . $user_login['guid'] . ')';
		Log::add($str_log, '用户登录');

		return [$user, null];
	}

	/**
	 * 退出
	 */
	public static function logout()
	{
		// 记录日志
		$user = self::current();
		if (!empty($user))
		{
			$timespan = Tool::timespanFormat(Tool::strtotime() - Tool::strtotime($user['login_time']));
			$str_log = '用户【' . $user['name'] . ' ' . $user['account'] . '】退出 在线时长 ' . $timespan;
			Log::add($str_log, '用户退出');

			DB::table('user_login')
				-> where('id', $user['login_id'])
				-> update([
					'online_status' => 2,
				]);
		}

		self::$cur_user = false;
		$_SESSION['current_user'] = null;
	}

	/**
	 * 判断权限
	 */
	public static function hasPower($power_list)
	{
		$res = self::checkPower($power_list);

		return $res === true;
	}
	
	/**
	 * 检测权限
	 */
	public static function checkPower($api)
	{
		$user = self::current();
		
		if (empty($user))
		{
			return [
				'result' => EnumConst::RESULT_OFFLINE,
				'error' => '未登录',
			];
		}

		if ($user['user_type_id'] == 1)
		{
			return true;
		}
	
		if (!in_array($api, $user['power_list']))
		{
			return [
				'result' => EnumConst::RESULT_POWER,
				'error' => '无[' . $api . ']权限',
			];
		}

		return true;
	}

	/**
	 * 设置当前用户配置
	 */
	public static function setConfig($key, $val)
	{
		$user = self::current();
		if (empty($user))
		{
			return false;
		}

		$config_json = DB::table('user') 
			-> where('id', $user['id'])
			-> value('config_json');

		$config = json_decode($config_json, true) ?: [];
		$config[$key] = $val;

		DB::table('user') -> update([
			'id' => $user['id'],
			'config_json' => json_encode($config),
		]);

		$user['config'] = $config;

		self::$cur_user = $user;
		$_SESSION['current_user'] = $user;

		return true;
	}
	
	/**
	 * API登录
	 */
	public static function apiLogin($account, $key)
	{
		$user = DB::table('user') 
			-> where('account', $account)
			-> where('status', 1)
			-> json() 
			-> find();

		if (empty($user))
		{
			return '用户无效';
		}

		// 删除密码
		unset($user['password']);

		// 验证KEY  1901011233ABCDEF
		$time_min = date('ymdHi', time() - 600);
		$time_max = date('ymdHi', time() + 600);
		$time = substr($key, 0, 10);
		if ($time < $time_min || $time > $time_max)
		{
			return 'key过期' . $time;
		}

		$key1 = $time . $user['guid'];
		for ($i=0; $i<5; $i++)
		{
			$key1 = md5($key1);
		}
		$key1 = \substr($key1, 0, 6);

		if (substr($key, 10) != $key1)
		{
			return 'key无效';
		}

		// 解析权限
		$user['user_type'] = DB::table('user_type') -> find($user['user_type_id']);
		$user['power_list'] = empty($user['user_type']['power']) ? [] : explode(',', $user['user_type']['power']);
		$user['login_time'] = Tool::now();

		self::$cur_user = $user;
		
		$str_log = '用户[' . $account . '] 在 ' . Tool::ip() . ' 登录';
		Log::add($str_log, 'API登录');

		return '';
	}
}