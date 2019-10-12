<?php
namespace app\api;

use zphp\ApiBase;
use zphp\User;
use zphp\Tool;
use zphp\Log;
use zphp\DB;
use app\EnumConst;

/**
 * 用户管理
 */
class UserManage extends ApiBase
{
	/**
	 * 列表页
	 * 
	 * @param int user_type 用户类型 [0]
	 */
	public static function index($params = [])
	{
		$user_type_id = $params['user_type'];

		$cur_user = User::current();
		$user_type_list = self::getTypeList();

		$user_list = [];
		if ($cur_user['user_type_id'] == 1)
		{
			$user_list = DB::table('user')
				-> whereIf($user_type_id > 0, 'user_type_id', $user_type_id)
				-> where('status != 2')
				-> select();
		}
		else
		{
			$user_list = DB::table('user')
				-> field('user.*')
				-> join('LEFT JOIN user_type ON user_type.id = user.user_type_id')
				-> whereIf($user_type_id > 0, 'user.user_type_id', $user_type_id)
				-> where('user.status != 2')
				-> where('user_type.is_hide = 0')
				-> select();
		}

		foreach ($user_list as &$r)
		{
			unset($r['password']);
		}
		unset($r);
		
		return self::_success([
			'user_type_list' => $user_type_list,
			'user_type_id' => $user_type_id,
			'user_list' => $user_list,
		]);
	}

	private static function getTypeList()
	{
		$cur_user = User::current();
		$user_type_list = DB::table('user_type') 
			-> whereIf($cur_user['id'] != 1, 'is_hide', 0) 
			-> select();

		return $user_type_list;
	}

	/**
	 * 添加页
	 * 
	 * @param int user_type 用户类型 [0]
	 */
	public static function add($params)
	{
		$user_type_id = $params['user_type'];
		$user_type_list = self::getTypeList();

		if ($user_type_id == 0 && count($user_type_list) > 0)
		{
			$user_type_id = $user_type_list[0]['id'];
		}

		$item = [
			'id' => 0,
			'user_type_id' => $user_type_id,
		];

		return self::_success([
			'user_type_list' => $user_type_list,
			'item'           => $item,
		]);
	}

	/**
	 * 编辑页
	 * 
	 * @param id +id ID编号 user
	 */
	public static function edit($params)
	{
		$user_type_list = self::getTypeList();

		$item = DB::table('user') -> find($params['id']);

		return self::_success([
			'user_type_list' => $user_type_list,
			'item'           => $item,
		], ['view' => 'add']);
	}

	/**
	 * 保存
	 * 
	 * @param id id ID编号 [0] user
	 * @param int +user_type_id 用户类型ID
	 * @param string +name 名称
	 * @param string +account 账户
	 * @param string passwd 密码 当添加时必填
	 * @param string phone 手机号
	 * @param string email 电子邮箱
	 * @param string weixin 微信
	 * @param int +status 状态
	 * @param string memo 备注
	 */
	public static function save($params)
	{
		$id = $params['id'];

		if ($params['user_type_id'] == 0)
		{
			return self::_error('用户类型错误');
		}

		$count = DB::table('user')
			-> where('status', '<>', 2)
			-> where('id', '<>', $id)
			-> where('account', $params['account'])
			-> count();
		
		if ($count > 0)
		{
			return self::_error('账号已存在');
		}

		if ($id == 0)
		{
			// 添加
			if (empty($params['passwd']))
			{
				return self::_error('密码必须填写');
			}

			$item = [
				'guid'         => Tool::guid(),
				'user_type_id' => $params['user_type_id'],
				'name'         => $params['name'],
				'account'      => $params['account'],
				'status'       => $params['status'],
				'phone'        => $params['phone'],
				'email'        => $params['email'],
				'weixin'       => $params['weixin'],
				'memo'         => $params['memo'],
				'add_time'     => '::now()',
			];

			$id = DB::table('user') -> insert($item);

			// 更新密码
			DB::table('user') -> update([
				'id' => $id,
				'password' => User::getPassword($id, $params['passwd']),
			]);
			
			// 记录操作日志
			$str_log = '添加用户【' . $item['name'] . ' ' . $item['account'] . '】';
			Log::add($str_log, '添加用户');
		}
		else
		{
			if ($id == 1)
			{
				return self::_error('超级默认管理员不允许修改');
			}
			$old = DB::table('user') 
				-> where('id', $id) 
				-> where('status', '<>', 2) 
				-> find();
			if (empty($old))
			{
				return self::_error('用户不存在');
			}

			$item = [
				'id'           => $params['id'],
				'user_type_id' => $params['user_type_id'],
				'name'         => $params['name'],
				'account'      => $params['account'],
				'status'       => $params['status'],
				'phone'        => $params['phone'],
				'email'        => $params['email'],
				'weixin'       => $params['weixin'],
				'memo'         => $params['memo'],
				'update_time'  => '::now()',
			];

			$str_log = '编辑用户【' . $item['name'] . ' ' . $item['account'] . '】';
			if (!empty($params['passwd']))
			{
				$item['password'] = User::getPassword($params['id'], $params['passwd']);
				$str_log .= ' 修改密码';
			}

			DB::table('user') -> update($item);
			
			// 记录操作日志
			Log::add($str_log, '编辑用户');
		}

		return self::_success($id, ['reload' => true]);
	}
	
	/**
	 * 状态更新
	 * 
	 * @param id +id ID编号 user
	 * @param int +status 状态
	 */
	public static function update_status($params)
	{
		if ($params['id'] == 1)
		{
			return self::_error('默认超级管理员不允许修改');
		}

		$user = DB::table('user') -> find($params['id']);

		if (empty($user))
		{
			return self::_error('用户不存在');
		}

		$params['status'] = $params['status'] === 0 ? 0 : 1;

		DB::table('user') -> update([
			'id' => $params['id'],
			'status' => $params['status'],
		]);

		// 记录操作日志
		$str_log = '更新用户【' . $user['name'] . ' ' . $user['account'] . '】状态为';
		$str_log .= $params['status'] == 0 ? '禁用' : '启用';
		Log::add($str_log, '更新用户状态');

		return self::_success();
	}

	/**
	 * 删除
	 * 
	 * @param id +id ID编号 user
	 */
	public static function delete($params)
	{
		$id = $params['id'];
		
		if ($params['id'] == 1)
		{
			return self::_error('默认超级管理员不允许删除');
		}

		$user = DB::table('user')
			-> where('status', '<>', 2)
			-> where('id', $id)
			-> find();

		if (empty($user))
		{
			return self::_error('用户不存在');
		}

		DB::table('user') -> update([
			'id' => $id,
			'status' => 2,
		]);

		// 记录操作日志
		Log::add('删除用户【' . $user['name'] . ' ' . $user['account'] . '】', '删除用户');

		return self::_success(true, ['reload' => true]);
	}

	/**
	 * 重新加载
	 */
	public static function reload()
	{
		DB::table('user_login')
			-> where('online_status', 1)
			-> update([
				'reload' => 1,
			]);
		
		// 记录操作日志
		Log::add('重置所有登录用户', '重置用户');

		return self::_success(true);
	}
}


