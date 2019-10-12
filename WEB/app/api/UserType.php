<?php
namespace app\api;

use zphp\ApiBase;
use zphp\User;
use zphp\Tool;
use zphp\Log;
use zphp\DB;
use app\EnumConst;

/**
 * 用户类型管理
 */
class UserType extends ApiBase
{
	/**
	 * 列表页
	 */
	public static function index($params = [])
	{
		$list = DB::table('user_type') -> select();

		foreach ($list as &$r)
		{
			$r['power_count'] = empty($r['power']) ? 0 : count(explode(',', $r['power']));
			$r['menu_count'] = empty($r['menu']) ? 0 : count(explode(',', $r['menu']));
		}
		unset($r);

		return self::_success([
			'list' => $list,
		]);
	}

	/**
	 * 添加页
	 */
	public static function add($params)
	{
		$item = [
			'id' => 0,
		];

		// 获取菜单
		$menu_list = require ROOT_PATH . 'app/menu.php';

		// 获取API权限
		$api_list = file_get_contents(ROOT_PATH . 'app' . DS . 'api.json');
		$api_list = json_decode($api_list, true);
		$api_list = $api_list['class_list'];

		return self::_success([
			'item'      => $item,
			'menu_list' => $menu_list,
			'api_list'  => $api_list,
		]);
	}

	/**
	 * 编辑页
	 * 
	 * @param id +id ID编号 user_type
	 */
	public static function edit($params)
	{
		$item = DB::table('user_type') -> find($params['id']);

		// 获取菜单
		$menu_list = require ROOT_PATH . 'app/menu.php';

		// 获取API权限
		$api_list = file_get_contents(ROOT_PATH . 'app' . DS . 'api.json');
		$api_list = json_decode($api_list, true);
		$api_list = $api_list['class_list'];

		return self::_success([
			'item'      => $item,
			'menu_list' => $menu_list,
			'api_list'  => $api_list,
		], ['view' => 'add']);
	}

	/**
	 * 保存
	 * 
	 * @param id id ID编号 [0] user_type
	 * @param string +code 编号
	 * @param string +name 名称
	 * @param int +is_hide 是否隐藏
	 * @param array power 权限
	 * @param array menu 菜单
	 * @param string memo 备注
	 */
	public static function save($params)
	{
		$id = $params['id'];
		$power = @$params['power'] ?: [];
		$menu = @$params['menu'] ?: [];

		$count = DB::table('user_type')
			-> where('id', '<>', $id)
			-> where('code', $params['code'])
			-> count();
		
		if ($count > 0)
		{
			return self::_error('编号已存在');
		}

		if ($id == 0)
		{
			// 添加
			$item = [
				'code'     => $params['code'],
				'name'     => $params['name'],
				'memo'     => $params['memo'],
				'is_hide'  => $params['is_hide'],
				'power'    => implode(',', $power),
				'menu'     => implode(',', $menu),
				'add_time' => '::now()',
			];

			$id = DB::table('user_type') -> insert($item);

			// 记录操作日志
			$str_log = '添加用户类型【' . $item['name'] . '】';
			Log::add($str_log, '添加用户类型');
		}
		else
		{
			if ($id == 1)
			{
				return self::_error('超级默认管理员不允许修改');
			}
			
			$item = [
				'id'           => $params['id'],
				'code'         => $params['code'],
				'name'         => $params['name'],
				'is_hide'      => $params['is_hide'],
				'memo'         => $params['memo'],
				'power'        => implode(',', $power),
				'menu'         => implode(',', $menu),
				'update_time'  => '::now()',
			];

			DB::table('user_type') -> update($item);
			
			// 记录操作日志
			$str_log = '编辑用户类型【' . $item['name'] . '】';
			Log::add($str_log, '编辑用户类型');
		}

		return self::_success($id, ['url' => 'index.php?_=user_type']);
	}
	
	/**
	 * 删除
	 * 
	 * @param id +id ID编号 user_type
	 */
	public static function delete($params)
	{
		$id = $params['id'];
		
		if ($params['id'] == 1)
		{
			return self::_error('默认超级管理员不允许删除');
		}

		$item = DB::table('user_type') -> find($id);

		$user_count = DB::table('user')
			-> where('user_type_id', $id)
			-> where('status', '<>', 2)
			-> count();
		
		if ($user_count > 0)
		{
			return self::_error('用户类型下包含有用户，禁止删除');
		}

		DB::table('user_type') -> delete($id);

		// 记录操作日志
		Log::add('删除用户类型【' . $item['name'] . '】', '删除用户类型');

		return self::_success(true, ['reload' => true]);
	}
}