<?php
namespace app\api;

use zphp\ApiBase;
use zphp\Log;

/**
 * 文档中心
 */
class Doc extends ApiBase
{
	/**
	 * 首页
	 */
	public static function index($params = [])
	{
		// 加载API列表
		$api_list = file_get_contents(ROOT_PATH . 'app' . DS . 'api.json');
		$api_list = json_decode($api_list, true);
		$build_time = $api_list['build_time'];
		$api_list = $api_list['class_list'];

		// 加载enum常量表
		$enum_list = require ROOT_PATH . 'app/enum.php';

		return self::_success([
			'build_time' => $build_time,
			'enum_list'  => $enum_list,
			'api_list'   => $api_list,
		]);
	}
}


