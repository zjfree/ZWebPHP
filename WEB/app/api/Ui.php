<?php
namespace app\api;

/**
 * UI类
 */
class Ui extends \zphp\ApiBase
{
	/**
	 * 首页
	 */
	public static function index($params)
	{
		return self::_success();
	}
	
	/**
	 * 未整理
	 */
	public static function tmp($params)
	{
		return self::_success();
	}

	/**
	 * 基础样式
	 */
	public static function base($params)
	{
		return self::_success();
	}

	/**
	 * 数据库操作
	 */
	public static function db($params)
	{
		return self::_success();
	}

	/**
	 * 工具类
	 */
	public static function tool($params)
	{
		return self::_success();
	}

	/**
	 * API文档
	 */
	public static function api($params)
	{
		return self::_success();
	}

	/**
	 * chart.js
	 */
	public static function chart_js($params)
	{
		return self::_success();
	}
	
	/**
	 * echart
	 */
	public static function echart($params)
	{
		return self::_success();
	}
	
	/**
	 * table1
	 * @param int page 第几页 [1]
	 * @param int user_type 用户类型 [0]
	 * @param date dt 日期 [today]
	 * @param date dt_begin
	 * @param date dt_end
	 */
	public static function table1($params)
	{
		$params['dt_begin']  = @$params['dt_begin'] ?: date('Y-m-01');
		$params['dt_end']    = @$params['dt_end'] ?: date('Y-m-d');

		return self::_success($params);
	}
	
	/**
	 * table2
	 */
	public static function table2($params)
	{
		return self::_success();
	}

	/**
	 * table3
	 */
	public static function table3($params)
	{
		return self::_success();
	}
	
	/**
	 * form1
	 */
	public static function form1($params)
	{
		return self::_success();
	}
	
	/**
	 * form2
	 */
	public static function form2($params)
	{
		return self::_success();
	}
	
	/**
	 * form3
	 */
	public static function form3($params)
	{
		return self::_success();
	}
	
	/**
	 * form4
	 */
	public static function form4($params)
	{
		return self::_success();
	}

	/**
	 * frame_form
	 */
	public static function form_frame($params)
	{
		return self::_success();
	}
	
	/**
	 * page_empty
	 */
	public static function page_empty($params)
	{
		return self::_success();
	}
	
	/**
	 * page_login
	 */
	public static function page_login($params)
	{
		return self::_success();
	}
	
	/**
	 * page_register
	 */
	public static function page_register($params)
	{
		return self::_success();
	}
}


