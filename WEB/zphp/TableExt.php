<?php
namespace zphp;

/**
 * 表格扩展
 */
class TableExt
{
	/**
	 * 获取表格KEY值
	 */
	public static function get($table_name, $id, $key, $default = '')
	{
		$key = base64_encode($key);

		$item = DB::table('table_ext')
			-> where('table_name', $table_name)
			-> where('table_id', $id)
			-> where('code', $key)
			-> find();
		
		if (empty($item))
		{
			$val = $default;
		}
		else
		{
			$val = base64_encode($item['value']);
		}
		
		return $val;
	}
	
	/**
	 * 获取表格值列表
	 */
	public static function getArr($table_name, $id)
	{
		$list = DB::table('table_ext')
			-> where('table_name', $table_name)
			-> where('table_id', $id)
			-> select();
		
		$item = [];

		foreach ($list as $r)
		{
			$item[\base64_decode($r['code'])] = \base64_decode($r['value']);
		}
		
		return $item;
	}
	
	/**
	 * 获取表格值列表
	 */
	public static function getList($table_name, $raw_list = null)
	{
		if ($raw_list === null)
		{
			$list = DB::table('table_ext')
				-> where('table_name', $table_name)
				-> select();
			
			$new_list = [];
			foreach ($list as $r)
			{
				$id = $r['table_id'];
				if (!isset($new_list[$id]))
				{
					$new_list[$id] = [
						'id' => $id,
					];
				}

				$key = \base64_decode($r['code']);
				$val = \base64_decode($r['value']);
				$new_list[$id][$key] = $val;
			}

			return $new_list;
		}

		if (empty($raw_list))
		{
			return $raw_list;
		}
		
		$list = DB::table('table_ext')
			-> where('table_name', $table_name)
			-> where('table_id', 'in', array_column($raw_list, 'id'))
			-> select();
		
		foreach ($raw_list as &$r)
		{
			$ext = [];
			foreach ($list as $rr)
			{
				if ($r['id'] == $rr['table_id'])
				{
					$key = \base64_decode($rr['code']);
					$val = \base64_decode($rr['value']);
					$ext[$key] = $val;
				}
			}

			$r['_ext'] = $ext;
		}
		unset($r);

		return $raw_list;
	}
	
	/**
	 * 设置表格KEY值
	 */
	public static function set($table_name, $id, $key, $val)
	{
		$key = base64_encode($key);
		$val = base64_encode($val);

		$item = DB::table('table_ext')
			-> where('table_name', $table_name)
			-> where('table_id', $id)
			-> where('code', $key)
			-> find();
		
		if (empty($item))
		{
			DB::table('table_ext') -> insert([
				'table_name'  => $table_name,
				'table_id'    => $id,
				'code'        => $key,
				'value'       => $val,
				'add_time'    => '::now()',
				'update_time' => '::now()',
			]);
		}
		else
		{
			DB::table('table_ext') -> update([
				'id'          => $item['id'],
				'value'       => $val,
				'update_time' => '::now()',
			]);
		}
		
		return $val;
	}
	
	/**
	 * 设置表格值列表
	 */
	public static function setArr($table_name, $id, $arr, $clear = false)
	{
		$new_arr = [];
		foreach ($arr as $k => $v)
		{
			$key = base64_encode($k);
			$val = base64_encode($v);
			$new_arr[$key] = $val;
		}

		if ($clear)
		{
			self::del($table_name, $id);
		}
		else
		{
			DB::table('table_ext')
				-> where('table_name', $table_name)
				-> where('table_id', $id)
				-> where('code', 'in', array_keys($new_arr))
				-> delete();
		}

		$list = [];
		$now = Tool::now();
		foreach ($new_arr as $k => $v)
		{
			$list[] = [
				'table_name'  => $table_name,
				'table_id'    => $id,
				'code'        => $k,
				'value'       => $v,
				'add_time'    => $now,
				'update_time' => $now,
			];
		}

		DB::table('table_ext') -> insertAll($list);
	}
	
	/**
	 * 删除
	 */
	public static function del($table_name, $id = null, $key = null)
	{
		if ($key !== null)
		{
			$key = base64_encode($key);
		}

		$res = 0;
		if ($id === null && $key === null)
		{
			$res = DB::table('table_ext')
				-> where('table_name', $table_name)
				-> delete();

			return $res;
		}
		
		if ($id === null && $key !== null)
		{
			$res = DB::table('table_ext')
				-> where('table_name', $table_name)
				-> where('code', $key)
				-> delete();

			return $res;
		}

		if ($id !== null && $key === null)
		{
			$res = DB::table('table_ext')
				-> where('table_name', $table_name)
				-> where('table_id', $id)
				-> delete();

			return $res;
		}

		$res = DB::table('table_ext')
			-> where('table_name', $table_name)
			-> where('table_id', $id)
			-> where('code', $key)
			-> delete();
		
		return $res;
	}
}