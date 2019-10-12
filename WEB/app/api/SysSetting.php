<?php
namespace app\api;

use zphp\ApiBase;
use zphp\Log;
use zphp\User;
use zphp\Config;
use zphp\Tool;
use zphp\DB;

/**
 * 系统配置
 */
class SysSetting extends ApiBase
{
	/**
	 * 基础配置
	 */
	public static function index($params = [])
	{
		$item = Config::getArr('sys_base');
		if (empty($item))
		{
			$item = [
				'install_time' => Tool::now(),
				'name' => '公司名称',
				'english' => 'company name',
				'print' => '打印信息',
				'tel'  => '',
				'memo' => '',
				'brand_list' => '',
				'notice' => '',
			];

			Config::setArr('sys_base', $item);
		}

		$data = [
			'item' => $item,
		];

		return self::_success($data);
	}

	/**
	 * 基础配置
	 * 
	 * @param string +type 类型标识
	 * @param any arr 数据项
	 */
	public static function save($params)
	{
		Config::setArr($params['type'], $params['arr']);

		return self::_success();
	}
}


