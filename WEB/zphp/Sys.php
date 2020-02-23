<?php
namespace zphp;

use app\EnumConst;

/**
 * 系统核心处理类
 */
class Sys
{
	// CONFIG配置
	private static $config = null;

	// 枚举配置
    private static $enum = null;

    // 环境信息
    private static $env = null;

    /**
     * Web启动入口
     */
    public static function webStart()
    {
        session_start();
        
        header('Content-type: text/html; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        // 获取请求类型
        $type = @$_REQUEST['_out'] ?: 'web';
        if ($_GET['_ajax'] === '1' || strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH'] ?: '') == 'xmlhttprequest')
        {
            $type = 'json';
        }

        // 环境信息
        self::$env = [
            'type' => $type,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ];
                
        // 调用API
        $api_str = @$_REQUEST['_'] ?: 'sys::index';
        list($res, $api) = self::callApi($api_str, $_REQUEST);
        
		// 记录API访问记录
		self::apiLog($res, $api);

        // 强制退出
        if (!empty($res['exit']))
        {
            return;
        }

        // 返回JSON格式
        if (self::$env['type'] == 'json')
        {
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            return;
        }

        // 需要登录，跳转到登录页
        if ($res['result'] == EnumConst::RESULT_OFFLINE)
        {
            // 未登录
            header('Location:/index.php?_=User::login');
            return;
        }

        // 获取view
        $view = $api['method'];
        if (isset($res['view']))
        {
            $view = $res['view'];
        }
        $view = str_replace('.', '', $view);

        // 错误页
        $error_page = self::getConfig('error_page', 'zphp/view/error.html');

        // 简写系统VIEW类
        class_alias('zphp\View', 'V');

        $view_file_path = '';
        $data = null;
        if ($res['result'] !== EnumConst::RESULT_SUCCESS)
        {
            // 错误页
            $data = ['res' => $res];
            $view_file_path = $error_page;
        }
        else if ($res['url'] !== null && strpos($res['url'], 'location:') === 0)
        {
            header($res['url']);
            return;
        }
        else
        {
            $data = $res['data'];
            $view_file_path = 'app/view/' . $api['name'] . '/' . $view . '.html';
        }

        $view_file_path = ROOT_PATH . $view_file_path;
        $view_file_path = realpath($view_file_path);

        if (empty($view_file_path))
        {
            $data = ['res' => [
                'result' => EnumConst::RESULT_VIEW,
                'error'  => 'VIEW【' . $api_str . '】不存在',
            ]];

            $view_file_path = ROOT_PATH . $error_page;
            $view_file_path = realpath($view_file_path);
		}

        // smarty
        $smarty = new \Smarty(); //建立smarty实例对象
        $smarty->compile_dir = ROOT_PATH . 'runtime/smarty/templates_c/';; //设置编译目录 ——混编文件，自动生成
        $smarty->cache_dir = ROOT_PATH . 'runtime/smarty/cache/'; //缓存目录
        $smarty->cache_lifetime = 0; //缓存时间
        $smarty->caching = false; //缓存方式   smarty缓存会导致 cookie\request 互串！！！！！！！！！！！！！！！！！
        $smarty->left_delimiter = '{ ';
        $smarty->right_delimiter = ' }';
        
        if (is_array($data) && array_values($data) !== $data)
        {
            foreach ($data as $k => $v)
            {
                $smarty->assign($k, $v);
            }
        }
        else
        {
            $smarty->assign('data', $data);
        }

        $user = User::current();
        $smarty->assign('_user', $user);

        $sys = View::getSys();
        $smarty->assign('_sys', $sys);
        $smarty->assign('_api', $api);

        $smarty->registerPlugin('modifier', 'html', 'htmlspecialchars');
        $smarty->registerPlugin('modifier', 'json', 'json_encode');
        
		SmartyPlugin::bind($smarty);

        $smarty->display($view_file_path);
    }
    
    /**
     * API启动入口
     */
    public static function apiStart()
    {
        // 环境信息
        self::$env = [
            'type' => 'json',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
		];
		
		// 登录
		$account = @$_REQUEST['_account'] ?: '';
		$key = @$_REQUEST['_key'] ?: '';

		if ($account != '')
		{
			$err = User::apiLogin($account, $key);
			if ($err != '')
			{
				header('Content-type: application/json; charset=utf-8');
				echo json_encode([
					'result' => \app\EnumConst::RESULT_OFFLINE,
					'error'  => $err,
				], JSON_UNESCAPED_UNICODE);
				return;
			}
		}
        
        // 调用API
        $api_str = @$_REQUEST['_'] ?: 'sys::index';
        list($res, $api) = self::callApi($api_str, $_REQUEST);
        
		// 记录API访问记录
		self::apiLog($res, $api);

		header('Content-type: application/json; charset=utf-8');
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}
	
    /**
     * 获取配置
     */
    public static function getConfig($key, $default = null)
    {
        if (self::$config === null)
        {
            self::$config = require ROOT_PATH . 'app/config.php';
        }

		$key_list = explode('.', $key);
		$last = self::$config;
		foreach ($key_list as $k)
		{
			if (isset($last[$k]))
			{
				$last = $last[$k];
			}
			else
			{
				return $default;
			}
		}

		return $last;
    }

    /**
     * 获取枚举
     */
	public static function getEnum($key)
	{
        if (self::$enum === null)
        {
            self::$enum = require ROOT_PATH . 'app/enum.php';
        }

		if (empty(self::$enum[$key]))
		{
			return null;
		}

		$item = self::$enum[$key];
		$list = [];
		foreach ($item['list'] as $k => $v)
		{
			if (is_string($v))
			{
				$v = [
					'name' => $v,
				];
			}
			$v['id'] = $k;
			$list[$k] = $v;
		}
		$item['list'] = $list;

		return $item;
	}

    /**
     * 获取枚举项
     */
	public static function getEnumItem($key, $id)
	{
		$item = self::getEnum($key);
		if (empty($item))
		{
			return null;
		}

		if (empty($item['list'][$id]))
		{
			return null;
		}

		return $item['list'][$id];
	}
	
    /**
     * 获取枚举名称
     */
	public static function getEnumName($key, $id)
	{
		$item = self::getEnumItem($key, $id);
		if (empty($item))
		{
			return null;
		}

		if (is_string($item))
		{
			return $item;
		}

		return $item['name'];
	}
	
    /**
     * 获取枚举HTML
     */
	public static function getEnumHtml($key, $id)
	{
		$item = self::getEnumItem($key, $id);
		if (empty($item))
		{
			return null;
		}

		if (is_string($item))
		{
			$item = [
				'name' => $item,
			];
		}

		$html = '';
		if (empty($item['color']))
		{
			$html = '<span>';
		}
		else
		{
			$html = '<span style="color:' . $item['color'] . ';">';
		}
		if (!empty($item['icon']))
		{
			$html .= '<i class="' . $item['icon'] . '"></i>';
		}
		$html .= $item['name'] . '</span>';

		return $html;
	}
	
	/**
	 * 调用API
	 */
	public static function callApi($base, $params = [])
	{
		$api = [];

		list($class, $method) = explode('::', $base);

		$class = @$class ?: 'Sys';
		$class = explode('_', $class);
		$class = array_map('ucfirst', $class);
		$class = \implode('', $class);

		$method = @$method ?: 'index';

		$api['base']   = $base;
		$api['name']   = $class;
		$api['class']  = 'app\\api\\' . $api['name'];
		$api['method'] = $method;
		
		if (!method_exists($api['class'], $api['method']))
		{
			return [[
				'result' => EnumConst::RESULT_API,
				'error'  => '接口【' . $base . '】不存在',
			], $api];
		}

		// 检测参数有效性，判断权限
		list($params, $err, $err_code) = self::check($api, $params);
		if (!empty($err))
		{
			return [[
				'result' => $err_code,
				'error'  => $err,
			], $api];
		}

		// 调用API
		$ms = Tool::ms();
		try
		{
			$res = call_user_func([$api['class'], '_init'], $api, $params);
			if (!empty($res) && $res['result'] == 1)
			{
				$res = call_user_func([$api['class'], $api['method']], $params);
			}
		}
		catch(\Exception $ex)
		{
			Log::error($ex);

			$res = [
				'result' => EnumConst::RESULT_EXCEPTION,
				'error'  => '代码执行异常 ' . $ex -> getMessage(),
				'exception' => $ex,
			];
		}

		$api['use_ms'] = Tool::ms($ms);

		return [$res, $api];
	}

	private static function apiLog($res, $api)
	{
		try
		{
			$str = date('Y-m-d H:i:s') . "\t";
			$str .= self::$env['ip'] . "\t";
			$str .= '[' . $api['name'] . '::' . $api['method'] . "]\t";
			$str .= $api['use_ms'] . "ms\t";
			$str .= $res['result'] . "\t";
			$str .= (@$res['error'] ?: '') . PHP_EOL;

			$file_path = ROOT_PATH . 'runtime/api_log/log_' . date('Ymd') . '.log';

			file_put_contents($file_path, $str, FILE_APPEND|LOCK_EX);
		}
		catch(\Exception $ex)
		{}
	}

	private static function check($api, $params)
	{
		// 获取接口列表
		$api_list = \file_get_contents(ROOT_PATH . "app/api.json");
		$api_list = json_decode($api_list, true);
		$api_list = $api_list['class_list'];

		// 获取API
		$cur_fn = null;
		foreach ($api_list as $class)
		{
			if ($class['code'] == $api['name'])
			{
				foreach ($class['fn_list'] as $fn)
				{
					if ($fn['code'] == $api['method'])
					{
						$fn['need_login'] = $class['need_login'];
						$cur_fn = $fn;
						break;
					}
				}
				break;
			}
		}

		if (empty($cur_fn))
		{
			return [null, '接口 【' . $api['base'] . '】 未注册', EnumConst::RESULT_API];
		}

		// 检测权限
		if (!empty($cur_fn['need_login']))
		{
			$bo = User::checkPower($api['name'] . '.' . $api['method']);
			if ($bo !== true)
			{
				return [null, $bo['error'], $bo['result']];
			}
		}

		// 获取参数
		$new_params = [];
		foreach ($cur_fn['params'] as $r)
		{
			$key = $r['code'];
			$val = null;
			$is_set = true;
			if (isset($params[$key]))
			{
				$val = $params[$key];
			}
			else
			{
				// 必填
				if ($r['require'])
				{
					return [null, '参数[' . $key . ']为空', EnumConst::RESULT_PARAM];
				}

				$is_set = false;
				$val = $r['value'];
			}


			if ($val !== null)
			{
				switch ($r['type'])
				{
					case 'string':
						$val = '' . $val;
						break;
					case 'int':
						$val = intval($val);
						break;
					case 'float':
						$val = floatval($val);
						break;
					case 'date':
						if (empty($val))
						{
							$val = null;
						}
						else
						{
							$val = $val == 'today' ? Tool::date('Y-m-d') : Tool::date('Y-m-d', Tool::strtotime($val));
						}
						break;
					case 'time':
						if (empty($val))
						{
							$val = null;
						}
						else
						{
							$val = $val == 'now' ? Tool::date('Y-m-d H:i:s') : Tool::date('Y-m-d H:i:s', Tool::strtotime($val));
						}
						break;
					case 'json':
						$val = json_decode($val, true);
						break;
					case 'array':
						if (is_string($val))
						{
							$val = json_decode($val, true) ?: explode(',', $val);
						}
						break;
					case 'array_int':
						if (is_string($val))
						{
							$val = explode(',', $val);
							$val = array_map('intval', $val);
						}
						break;
					case 'code':
						$val = Tool::parseCode($val);
						if ($val === false)
						{
							return [null, '参数[' . $key . ']解析失败', EnumConst::RESULT_PARAM];
						}
						break;
					case 'id':
						$val = Tool::parseId($val, $r['memo']);
						if ($val === false)
						{
							return [null, '参数[' . $key . ']解析失败', EnumConst::RESULT_PARAM];
						}
						break;
					default:
						break;
				}
			}

			$new_params[$key] = $val;
		}

		return [$new_params, ''];
	}
	
	/**
	 * 获取连续编号
	 */
	public static function getNo($key, $pre = 'NO', $num = 4)
	{
		$item = DB::table('sys_no')
			-> where('code', $key)
			-> find();
		
		$val = 1;
		if (empty($item))
		{
			DB::table('sys_no')
				-> insert([
					'code' => $key,
					'value' => 1,
				]);
		}
		else
		{
			$val = intval($item['value']) + 1;
			DB::table('sys_no')
				-> where('code', $key)
				-> update([
					'value' => '::value+1',
				]);
		}

		$no = $pre;
		$no .= $num > 0 ? sprintf('%0' . $num . 'd', $val) : $val;

		return $no;
	}
}