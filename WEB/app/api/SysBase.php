<?php
namespace app\api;

use zphp\ApiBase;
use zphp\Log;
use zphp\User;
use zphp\Tool;
use zphp\DB;
use zphp\Config;

/**
 * 系统基础
 */
class SysBase extends ApiBase
{
	/**
	 * 调试页
	 */
	public static function debug($params = [])
	{
		$list = [
			'php_version'       => PHP_VERSION,
			'os_version'        => PHP_OS,
			'server_name'       => $_SERVER["SERVER_NAME"],
			'server_ip'         => gethostbyname($_SERVER["SERVER_NAME"]),
			'sql_connect_count' => count(DB::runSql('show processlist')),
			'sql_version'       => DB::runSql('select version()')[0]['version()'],
			'db_name'           => DB::runSql('select database()')[0]['database()'],
			'root_path'         => $_SERVER['DOCUMENT_ROOT'],
			'server_vserion'    => $_SERVER['SERVER_SOFTWARE'],
			'server_time'       => date('Y-m-d H:i:s'),
			'guid'              => Tool::guid(),
			'php_uname'         => php_uname(),
			'user_agent'        => $_SERVER['HTTP_USER_AGENT'],
		];

		// 获取磁盘空间
		$disk_list = Tool::getDiskSize();

		$data = [
			'list' => $list,
			'disk_list' => $disk_list,
		];

		return self::_success($data);
	}
	
	/**
	 * 调试信息显示
	 * 
	 * @param string md5
	 */
	public static function debug_info($params = [])
	{
		$file = ROOT_PATH . 'runtime/log/debug.log';

		$md5 = @md5_file($file);
		$content = $md5 == $params['md5'] ? '' : @file_get_contents($file);

		$data = [
			'content' => $content,
			'md5' => $md5,
			'now' => Tool::now(),
		];

		return self::_success($data);
	}

	/**
	 * 调试信息清空
	 */
	public static function debug_info_clear($params = [])
	{
		$file = ROOT_PATH . 'runtime/log/debug.log';

		@unlink($file);

		return self::_success();
	}

	/**
	 * php调试信息
	 */
	public static function phpinfo($params = [])
	{
		phpinfo();
		
		return self::_exit(false);
	}
	
	/**
	 * 清空文件日志
	 */
	public static function file_log_clear($params = [])
	{
		$dir_list = [
			ROOT_PATH . 'runtime/api_log',
			ROOT_PATH . 'runtime/log',
			ROOT_PATH . 'runtime/local_service',
			ROOT_PATH . 'runtime/smarty/cache',
			ROOT_PATH . 'runtime/smarty/templates_c',
		];
		
		foreach ($dir_list as $dir)
		{
			echo $dir . PHP_EOL;
			array_map('unlink', glob($dir . '/*'));
		}

		Log::add('清空文件日志', '清空日志');
		echo '清空文件日志';

		return self::_exit();
	}
	
	/**
	 * 清空用户操作日志
	 */
	public static function user_log_clear($params = [])
	{
		DB::table('sys_log') -> truncate();

		Log::add('清空所有用户操作日志', '清空日志');
		echo '清空所有用户操作日志';

		return self::_exit();
	}
	
	/**
	 * 清空上传文件
	 */
	public static function upload_clear($params = [])
	{
		Tool::delDir(ROOT_PATH . 'static/upload/');
		Tool::createDir(ROOT_PATH . 'static/upload/');
		
		Log::add('清空上传目录', '清空日志');
		echo '清空上传目录';

		return self::_exit();
	}
	
	/**
	 * 数据库查询
	 * 
	 * @param string sql [SHOW TABLE STATUS]
	 */
	public static function db_query($params = [])
	{
		$sql = $params['sql'];
		$err = '';

		Log::add($sql, '调试执行SQL');

		try
		{
			$list = DB::runSql($sql);
		}
		catch(\Exception $ex)
		{
			$err = $ex -> getMessage();
		}

		$data = [
			'sql'   => $sql,
			'list'  => $list,
			'error' => $err,
		];

		return self::_success($data);
	}

	/**
	 * 系统错误测试页
	 */
	public static function err($params = [])
	{
		throw new \Exception('系统错误测试');
	}

	/**
	 * 系统操作日志
	 * 
	 * @param date dt 查询日期 [today]
	 * @param int page 分页 [1]
	 * @param int page_size 单页数量 [30]
	 * @param string type 类型
	 * @param string user 用户
	 */
	public static function log($params)
	{
		$dt = $params['dt'];
		$page = $params['page'];
		$page_size = $params['page_size'];
		$type = $params['type'];
		$user = $params['user'];

		$count = 0;
		$list = [];

		if (empty($user))
		{
			$dt_end = Tool::addDay($dt, 1);
	
			list($count, $list) = DB::table('sys_log')
				-> where('add_time', 'between', [$dt, $dt_end])
				-> whereIf($type != '', 'type', $type)
				-> order('id DESC')
				-> selectPage($page, $page_size);
		}
		else
		{
			$user_obj = DB::table('user')
				-> where('account', $user)
				-> whereOr('name', $user)
				-> find();
			if (!empty($user_obj))
			{
				list($count, $list) = DB::table('sys_log')
					-> where('user_id', $user_obj['id'])
					-> whereIf($type != '', 'type', $type)
					-> order('id DESC')
					-> selectPage($page, $page_size);
			}
		}

		$data = [
			'page'      => $page,
			'page_size' => $page_size,
			'dt'        => $dt,
			'count'     => $count,
			'list'      => $list,
			'type'      => $type,
			'user'      => $user,
		];

		return self::_success($data);
	}
	
	/**
	 * 用户登录日志
	 * 
	 * @param date dt 查询日期 [today]
	 * @param int page 分页 [1]
	 * @param int page_size 单页数量 [30]
	 * @param string user 用户
	 */
	public static function login_log($params)
	{
		$overtime = date('Y-m-d H:i:s', time() - 10 * 60);
		DB::table('user_login')
			-> where('last_time', '<', $overtime)
			-> where('online_status', 1)
			-> update('online_status', 3);

		$dt = $params['dt'];
		$page = $params['page'];
		$page_size = $params['page_size'];
		$user = $params['user'];

		$count = 0;
		$list = [];

		if (empty($user))
		{
			$dt_end = Tool::addDay($dt, 1);
	
			list($count, $list) = DB::table('user_login')
				-> where('add_time', 'between', [$dt, $dt_end])
				-> order('id DESC')
				-> selectPage($page, $page_size);
		}
		else
		{
			$user_obj = DB::table('user')
				-> where('account', $user)
				-> whereOr('name', $user)
				-> find();
			if (!empty($user_obj))
			{
				list($count, $list) = DB::table('user_login')
					-> where('user_id', $user_obj['id'])
					-> order('id DESC')
					-> selectPage($page, $page_size);
			}
		}

		foreach ($list as &$r)
		{
			$t1 = strtotime($r['add_time']);
			$t2 = strtotime($r['last_time']);
			$r['timespan'] = Tool::timespanFormat($t2 - $t1);
		}
		unset($r);
		
		$data = [
			'page'      => $page,
			'page_size' => $page_size,
			'dt'        => $dt,
			'count'     => $count,
			'list'      => $list,
		];

		return self::_success($data);
	}

	/**
	 * 更新文本字段
	 * 
	 * @param code +table 表名
	 * @param string +id ID编号
	 * @param code field 字段名 [name]
	 * @param string +val 更新值
	 */
	public static function update_text($params)
	{
		$table = $params['table'];
		$id    = $params['id'];
		$field = $params['field'];
		$val   = $params['val'];

		$id = Tool::parseId($id, $table);
		if ($id === false)
		{
			return self::_error('非法ID');
		}

		DB::table($table)
			-> update([
				'id' => $id,
				$field => $val,
			]);
		
		return self::_success();
	}
}


