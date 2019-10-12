<?php
namespace app\api;

use zphp\ApiBase;
use zphp\Sys;
use zphp\Log;
use zphp\DB;

/**
 * 客户维护
 * 客户维护管理操作类
 * @author zjfree
 * @date 2019-03-12
 */
class Client extends ApiBase
{
	/**
	 * 列表页
	 * 列表查询页
	 * @author zjfree
	 * @date 2019-03-12
	 * @return 客户列表
	 * 
	 * @param int client_type 客户类型 [0]
	 * @param string query 查询关键词
	 * @param int sort 排序 [0]
	 */
	public static function index($params = [])
	{
		$client_type = $params['client_type'];
		$client_type_list = Sys::getEnum('client_type')['list'];

		// 获取模糊查询
		$query = trim($params['query']);
		$query_sql = '';

		if (!empty($query))
		{
			$query = str_replace('"', '', $query);
			$query = str_replace("'", '', $query);
			$query = str_replace("\\", '', $query);
			$query = str_replace("/", '', $query);
			$query_sql .= "(";
			$query_sql .= "code LIKE '%$query%' OR ";
			$query_sql .= "name LIKE '%$query%'";
			$query_sql .= ")";
		}

		// 排序
		$sort = $params['sort'];
		$sort_list = [
			['name' => 'ID编号' , 'code' => ''],
			['name' => '名称', 'code' => 'name'],
			['name' => '名称', 'code' => 'name DESC'],
			['name' => '添加时间', 'code' => 'add_time'],
			['name' => '添加时间', 'code' => 'add_time DESC'],
		];
		foreach ($sort_list as &$r)
		{
			$r['icon'] = strpos($r['code'], 'DESC') === false ? 'down' : 'up';
		}
		unset($r);
		$sort_code = @$sort_list[$sort]['code'] ?: '';

		$list = DB::table('client')
			-> where('status', '!=', 2)
			-> whereif(!empty($query), $query_sql)
			-> whereif($client_type > 0, 'client_type', $client_type)
			-> order($sort_code)
			-> select();
		
		foreach ($list as &$r)
		{
			$r['ext'] = \json_decode($r['config_json'], true);
		}
		unset($r);

		return self::_success([
			'list'              => $list,
			'client_type'       => $client_type,
			'client_type_list'  => $client_type_list,
			'query'             => $query,
			'sort'              => $sort,
			'sort_list'         => $sort_list,
		]);
	}

	/**
	 * 添加页
	 */
	public static function add($params)
	{
		$item = [
			'id' => 0,
			'client_type' => 1,
			'ext' => [],
		];

		return self::_success([
			'item'      => $item,
			'client_type_list' => Sys::getEnum('client_type')['list'],
		]);
	}

	/**
	 * 编辑页
	 * 
	 * @param id +id ID编号 client
	 */
	public static function edit($params)
	{
		$item = DB::table('client') -> find($params['id']);

		$item['ext'] = json_decode($item['config_json'], true);

		return self::_success([
			'item'      => $item,
			'client_type_list' => Sys::getEnum('client_type')['list'],
		], ['view' => 'add']);
	}

	/**
	 * 保存
	 * 
	 * @param id id ID编号 [0] client
	 * @param string +name 名称
	 * @param int +status 状态
	 * @param int +client_type 客户类型
	 * @param array ext 扩展属性
	 * @param string memo 备注
	 */
	public static function save($params)
	{
		$id = $params['id'];
		
		if ($id == 0)
		{
			// 添加
			$item = [
				'code'        => Sys::getNo('client'),
				'name'        => $params['name'],
				'client_type' => $params['client_type'],
				'memo'        => $params['memo'],
				'status'      => $params['status'],
				'config_json' => json_encode($params['ext']),
				'add_time'    => '::now()',
			];

			$id = DB::table('client') -> insert($item);

			// 记录操作日志
			$str_log = '添加客户【' . $item['name'] . '】';
			Log::add($str_log, '添加客户');
		}
		else
		{
			$old = DB::table('client') -> find($params['id']);
			if (empty($old))
			{
				return self::_error('修改数据项不存在');
			}

			$ext = empty($old['config_json']) ? [] : json_decode($old['config_json'], true);
			$ext = $ext ?: [];
			$ext = array_merge($ext, $params['ext']);

			$item = [
				'id'           => $params['id'],
				'name'         => $params['name'],
				'client_type'  => $params['client_type'],
				'memo'         => $params['memo'],
				'status'       => $params['status'],
				'config_json'   => json_encode($ext),
				'update_time'  => '::now()',
			];

			DB::table('client') -> update($item);
			
			// 记录操作日志
			$str_log = '编辑客户【' . $item['name'] . '】';
			Log::add($str_log, '编辑客户');
		}

		return self::_success($id, ['url' => 'index.php?_=client']);
	}
	
	/**
	 * 状态更新
	 * 
	 * @param id +id ID编号 client
	 * @param int +status 状态
	 */
	public static function update_status($params)
	{
		$item = DB::table('client') -> find($params['id']);

		if (empty($item))
		{
			return self::_error('客户不存在');
		}

		$params['status'] = $params['status'] === 0 ? 0 : 1;

		DB::table('client') -> update([
			'id' => $params['id'],
			'status' => $params['status'],
		]);

		// 记录操作日志
		$str_log = '更新客户【' . $item['name'] . '】状态为';
		$str_log .= $params['status'] == 0 ? '禁用' : '启用';
		Log::add($str_log, '更新客户状态');

		return self::_success();
	}

	/**
	 * 删除
	 * 
	 * @param id +id ID编号 client
	 */
	public static function delete($params)
	{
		$id = $params['id'];
		
		$item = DB::table('client') -> find($id);

		DB::table('client') -> update([
			'id' => $id,
			'status' => 2,
		]);

		// 记录操作日志
		Log::add('删除客户【' . $item['name'] . '】', '删除客户');

		return self::_success(true, ['reload' => true]);
	}
	
	/**
	 * 上传图片
	 * 
	 * @param id +id ID编号 client
	 * @param string img_url 图片网址
	 */
	public static function upload_img($params)
	{
		$id = $params['id'];
		
		$item = DB::table('client') -> find($id);

		DB::table('client') -> update([
			'id' => $id,
			'img_url' => $params['img_url'],
		]);

		// 记录操作日志
		Log::add('客户上传图片【' . $item['code'] . ' ' . $item['name'] . '】' . $params['img_url'], '客户上传图片');

		return self::_success(true, ['reload' => true]);
	}
}