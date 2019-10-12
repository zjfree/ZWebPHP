<?php
namespace zphp;

/**
 * 系统缓存
 */
class Cache
{
	// 缓存
	private static $_cache = [];

	/**
	 * 获取值
	 */
	public static function get($key, $default = null)
	{
		$key = md5($key);

		if (isset(self::$_cache[$key]))
		{
			return self::$_cache[$key];
		}

		$file_path = ROOT_PATH . 'runtime/app_cache/cache_' . $key . '.dat';
		if (!file_exists($file_path))
		{
			return $default;
		}

		$val = unserialize(@file_get_contents($file_path));
		self::$_cache[$key] = $val;

		return $val;
	}
	
	/**
	 * 设置值
	 */
	public static function set($key, $val)
	{
		$key = md5($key);
		$file_path = ROOT_PATH . 'runtime/app_cache/cache_' . $key . '.dat';
		@file_put_contents($file_path, serialize($val));
		
		self::$_cache[$key] = $val;

		return true;
	}
	
	/**
	 * 删除
	 */
	public static function delete($key)
	{
		$key = md5($key);
		$file_path = ROOT_PATH . 'runtime/app_cache/cache_' . $key . '.dat';
		if (!file_exists($file_path))
		{
			unlink($file_path);
		}

		unset(self::$_cache[$key]);

		return true;
	}
	
	/**
	 * 递增
	 */
	public static function add($key, $val = 1)
	{
		$count = self::get($key, 0);
		$count = $count + $val;
		self::set($key, $count);
		
		return $count;
	}
}