<?php
namespace app\api;

use zphp\ApiBase;
use zphp\Config;

/**
 * 系统
 */
class Sys extends ApiBase
{
	/**
	 * 系统首页
	 */
	public static function index($params = [])
	{
		$data = [
			'today'    => date('Y年m月d日 星期') . ['一','二','三','四','五','六','日'][date('N')-1],
			'notice'   => Config::get('sys_base', 'notice'),
		];

		return self::_success($data);
	}
	
	/**
	 * 手机首页
	 */
	public static function phone_index($params)
	{
		return self::_success();
	}
	
	/**
	 * 关于我们
	 */
	public static function about($params)
	{
		return self::_success();
	}
	
	/**
	 * 系统帮助
	 */
	public static function help($params)
	{
		return self::_success();
	}
}