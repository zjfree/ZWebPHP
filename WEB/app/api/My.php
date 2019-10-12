<?php
namespace app\api;

use zphp\ApiBase;
use zphp\Log;
use zphp\User;
use zphp\Tool;
use zphp\DB;

/**
 * 当前用户
 */
class My extends ApiBase
{
	/**
	 * 系统首页
	 */
	public static function index($params)
	{
		return self::_success();
	}
	
	/**
	 * 修改密码页
	 */
	public static function passwd($params)
	{
		return self::_success();
	}

	/**
	 * 修改密码保存
	 * 
	 * @param string +old_passwd 原密码
	 * @param string +new_passwd1 新密码
	 * @param string +new_passwd2 确认密码
	 */
	public static function passwd_save($params)
	{
		if (empty($params['new_passwd1']))
		{
			return self::_error('新密码必须填写');
		}
		
		if ($params['new_passwd1'] != $params['new_passwd2'])
		{
			return self::_error('新密码不一致');
		}
		
		if ($params['old_passwd'] == $params['new_passwd1'])
		{
			return self::_error('新密码与原密码相同');
		}

		$user = User::current();
		$user = DB::table('user') -> find($user['id']);
		$old_passwd = User::getPassword($user['id'], $params['old_passwd']);
		if ($user['password'] != $old_passwd)
		{
			return self::_error('原密码错误' . $old_passwd);
		}

		DB::table('user') -> update([
			'id' => $user['id'],
			'password' => User::getPassword($user['id'], $params['new_passwd1'])
		]);

		Log::add('修改密码', '修改密码');

		return self::_success();
	}
	
	/**
	 * 编辑页
	 */
	public static function edit($params)
	{
		return self::_success();
	}
	
	/**
	 * 保存
	 * 
	 * @param string +name
	 * @param string phone
	 * @param string email
	 * @param string weixin
	 * @param string +memo
	 */
	public static function save($params)
	{
		$user = User::current();

		DB::table('user') -> update([
			'id'     => $user['id'],
			'name'   => $params['name'],
			'phone'  => $params['phone'],
			'email'  => $params['email'],
			'weixin' => $params['weixin'],
			'memo'   => $params['memo'],
		]);

		DB::table('user_login') 
			-> where('online_status', 1)
			-> where('user_id', $user['id'])
			-> update(['reload' => 1]);

		Log::add('修改当前用户信息', '用户信息编辑');

		return self::_success();
	}
	
	/**
	 * 保存备忘信息
	 * 
	 * @param string memo 备忘信息
	 */
	public static function save_memo($params)
	{
		$user = User::current();

		DB::table('user') -> update([
			'id'       => $user['id'],
			'notebook' => $params['memo'],
		]);

		DB::table('user_login') 
			-> where('online_status', 1)
			-> where('user_id', $user['id'])
			-> update(['reload' => 1]);

		Log::add('用户更新自己的备忘信息', '用户修改备忘');

		return self::_success();
	}
	
}


