<?php
namespace app\api;

use zphp\ApiBase;
use zphp\DB;
use zphp\Log;

/**
 * 服务
 */
class Service extends ApiBase
{
	protected static $need_login = false;

	/**
	 * 客户手机拍照上传
	 * 
	 * @param id +id ID编号 client
	 * @param string img_url 图片网址
	 */
	public static function client_upload_cam($params)
	{
		$id = $params['id'];
		
		$item = DB::table('client') -> find($id);

		if ($params['img_url'] != $item['img_url'])
		{
			DB::table('client') -> update([
				'id' => $id,
				'img_url' => $params['img_url'],
			]);

			// 记录操作日志
			Log::add('客户上传图片【' . $item['code'] . ' ' . $item['name'] . '】' . $params['img_url'], '客户上传图片');
		}

		$data = [
			'item' => $item,
			'img_url' => $params['img_url'],
		];

		return self::_success($data);
	}
}


