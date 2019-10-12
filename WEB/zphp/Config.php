<?php
namespace zphp;

/**
 * 系统配置
 */
class Config
{
	// 缓存
	private static $_cache = [];

	/**
	 * 获取值
	 */
	public static function get($type, $key, $default = '')
	{
		$k = $type . '.' . $key;
		if (isset(self::$_cache[$k]))
		{
			return self::$_cache[$k];
		}

		$item = DB::table('sys_config')
			-> where('type', $type)
			-> where('code', $key)
			-> find();
		
		if (empty($item))
		{
			$val = $default;
		}
		else
		{
			$val = $item['value'];
		}
		
		self::$_cache[$k] = $val;

		return $val;
	}
	
	/**
	 * 设置值
	 */
	public static function set($type, $key, $val)
	{
		$k = $type . '.' . $key;
		self::$_cache[$k] = $val;

		$item = DB::table('sys_config')
			-> where('type', $type)
			-> where('code', $key)
			-> find();
		
		if (empty($item))
		{
			DB::table('sys_config') -> insert([
				'type' => $type,
				'code' => $key,
				'value' => is_array($val) ? json_encode($val) : $val,
				'add_time' => '::now()',
			]);
		}
		else
		{
			DB::table('sys_config')
				-> where('type', $type)
				-> where('code', $key)
				-> update([
					'value' => is_array($val) ? json_encode($val) : $val,
					'update_time' => '::now()',
				]);
		}

		return true;
	}
	
	/**
	 * 删除
	 */
	public static function delete($type, $key = '')
	{
		if (empty($key))
		{
			$key_list = [];
			foreach (self::$_cache as $k => $r)
			{
				if (explode('.', $k)[0] == $type)
				{
					$key_list[] = $k;
				}
			}

			foreach ($key_list as $k)
			{
				unset(self::$_cache[$k]);
			}

			DB::table('sys_config')
				-> where('type', $type)
				-> delete();
		}
		else
		{
			$k = $type . '.' . $key;
			unset(self::$_cache[$k]);
			DB::table('sys_config')
				-> where('type', $type)
				-> where('code', $key)
				-> delete();
		}

		return true;
	}
	
	/**
	 * 获取一组值
	 */
	public static function getArr($type)
	{
		$list = DB::table('sys_config')
			-> where('type', $type)
			-> select();
		
		$item = [];
		foreach ($list as $r)
		{
			$item[$r['code']] = $r['value'];
		}

		return $item;
	}
	
	/**
	 * 更新一组值
	 */
	public static function setArr($type, $arr)
	{
		$item = self::getArr($type);

		foreach ($arr as $k => $v)
		{
			if (isset($item[$k]))
			{
				DB::table('sys_config')
					-> where('type', $type)
					-> where('code', $k)
					-> update([
						'value' => is_array($v) ? json_encode($v) : $v,
						'update_time' => '::now()',
					]);
			}
			else
			{
				DB::table('sys_config') -> insert([
					'type' => $type,
					'code' => $k,
					'value' => is_array($v) ? json_encode($v) : $v,
					'add_time' => '::now()',
				]);
			}
				
			$kk = $type . '.' . $k;
			self::$_cache[$kk] = $v;
		}

		return $item;
	}
}