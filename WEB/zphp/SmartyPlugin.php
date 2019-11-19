<?php
namespace zphp;

// SMARTY 前缀字符
defined('SMARTY_PRE') or define('SMARTY_PRE', 'z_');

class SmartyPlugin
{
    // 数据库表缓存
    protected static $_sql_cache_list = [];

	// 今天
	protected static $_today = '';

	/// 自动绑定函数到SMARTY
	public static function bind(&$smarty)
	{
		// static::class 修改为 get_called_class，PHP5.4 不兼容
		$fn_list = get_class_methods(get_called_class());
		
		$plugin_list = ['function', 'modifier', 'block'];
		foreach ($fn_list as $r)
		{
			$arr = explode('_', $r);

			if (count($arr) > 0 && in_array($arr[0], $plugin_list))
			{
				$fn_name = str_replace($arr[0] . '_', SMARTY_PRE, $r);

				$smarty -> registerPlugin($arr[0], $fn_name, get_called_class() . '::' . $r);
			}
		}

		self::$_today = date('Y-m-d');
	}
    
    ///# 块
	/// 测试
	public static function block_test($params, $content, &$smarty, &$repeat)
    {
    	if ($content == '')
    	{
    		return '';
    	}

        $name = @$params['name'] ?: '无';
		$html = "<div><h3>hello $name</h3> $content</div>";
		
    	return $html;
    }

	///# 函数
	/// 删除按钮
	public static function function_btn_del($params, $template)
	{
		$url = @$params['url'] ?: '';
		$html = '<a href="' . $url . '" class="btn-del" data-toggle="tooltip" title="删除"><i class="fa fa-close"></i></a>';
        
		return $html;
	}
	
	/// 编辑按钮
	public static function function_btn_edit($params, $template)
	{
		$url = @$params['url'] ?: '';
		$html = '<a href="' . $url . '" class="btn-edit" data-toggle="tooltip" title="编辑"><i class="fa fa-edit"></i></a>';
        
		return $html;
    }
	
	/// 编辑按钮
	public static function function_btn_frame_edit($params, $template)
	{
		$url = @$params['url'] ?: '';
		$size = @$params['size'] ?: '';
		$title = @$params['title'] ?: '编辑';
		$html = '<a href="' . $url . '" class="btn-frame-edit" data-frame-size="' . $size . '" data-toggle="tooltip" title="' . $title . '"><i class="fa fa-edit"></i></a>';

		return $html;
	}
	
	///# 修改器
	/// 数组输出
	public static function modifier_list($arr, $split = ',')
	{
		if (empty($arr))
		{
			return '';
		}

		return implode($split, $arr);
	}

	/// JSON输出
	public static function modifier_json($arr, $type = 384)
	{
		if (empty($arr))
		{
			return 'null';
		}

		return json_encode($arr, $type);
	}

	/// 金额格式化
	public static function modifier_money_format($money, $zero = '-')
	{
		$money = floatval($money);
		
		if ($money == 0)
		{
			return $zero;
		}
		
		return $money . ' 元';
	}
	
	/// 根据ID查询名称
	public static function modifier_id_name($id, $table, $key = 'name', $default = '')
	{
		$k = $table . '.' . $key;
		$arr = [];
		if (isset(self::$_sql_cache_list[$k]))
		{
			$arr = self::$_sql_cache_list[$k];
		}
		else
		{
			$arr = DB::table($table) -> column($key, 'id');
			self::$_sql_cache_list[$k] = $arr;
		}

		return @$arr[$id] ?: $default;
	}
	
	/// 表格的状态显示
	public static function modifier_td_status($status, $url)
	{
		$html = '<td class="z-status" data-url="' . $url . '">' . PHP_EOL;
		if ($status == 1)
		{
			$html .= '<span class="active">启用</span><span>禁用</span>';
		}
		else
		{
			$html .= '<span>启用</span><span class="active">禁用</span>';
		}
		$html .= '</td>' . PHP_EOL;

		return $html;
	}

	/// ENUM HTML显示
	public static function modifier_enum_format($status, $type)
	{
		return Sys::getEnumHtml($type, $status);
	}
	
	/// ENUM 名称显示
	public static function modifier_enum_name($status, $type)
	{
		return Sys::getEnumName($type, $status);
	}
	
	/// 返回验证CODE
	public static function modifier_code($code)
	{
		return Tool::getCode($code);
	}
	
	/// 返回验证ID
	public static function modifier_id($id, $table)
	{
		if (empty($id))
		{
			return '0';
		}

		return Tool::getId($id, $table);
	}

	/// 时长格式化
	public static function modifier_timespan($timespan)
	{
		return Tool::timespanFormat($timespan);
	}

	/// 日期输出
	public static function modifier_dt($time)
	{
		$t = Tool::strtotime($time);
		if (empty($time) || $t == Tool::strtotime('2000-01-01'))
		{
			return '-';
		}

		return Tool::date('Y-m-d', $t);
	}

	/// 时间输出
	public static function modifier_date_time($time)
	{
		$t = Tool::strtotime($time);
		if ($t == Tool::strtotime('2000-01-01'))
		{
			return '-';
		}

		return $time;
	}
	
	/// 时间输出
	public static function modifier_time($time)
	{
		$t = Tool::strtotime($time);
		if ($t == Tool::strtotime('2000-01-01'))
		{
			return '-';
		}

		$day = Tool::strtotime(date('Y-m-d'));
		if ($t > $day)
		{
			return '今天 ' . Tool::date('H:i:s', $t);
		}
		else if ($t > $day - 3600*24)
		{
			return '昨天 ' . Tool::date('H:i:s', $t);
		}

		return $time;
	}
	
	/// 重量格式化
	public static function modifier_weight_format($weight)
	{
		if (empty($weight))
		{
			return '-';
		}

		$pre = $weight < 0 ? '-' : '';
		$weight = abs($weight);
		if ($weight < 1000)
		{
			return $pre . $weight . 'kg';
		}

		$weight = $weight / 1000;
		if ($weight < 10000)
		{
			return $pre . round($weight, 3) . '吨';
		}

		$weight = round($weight);
		
		return $pre . number_format($weight) . '吨';
	}
	
	/// 字节格式化
	public static function modifier_byte_format($byte)
	{
		return Tool::byteFormat($byte);
	}
	
	/// 百分比
	public static function modifier_percent($weight, $total)
	{
		if (empty($weight))
		{
			return '0 %';
		}

		if (empty($total))
		{
			return '- %';
		}

		$percent = round($weight / $total * 100);
		if ($percent < 1)
		{
			$percent = round($weight / $total * 100, 2);
		}

		return $percent . ' %';
	}
	
	/// 截取中文 zjfree@2015-08-30
	public static function modifier_cut($str, $length = 10, $etc = '...')
	{
		$len = mb_strlen($str, 'utf-8');
		
		if ($len <= $length)
		{
			$str = htmlspecialchars($str);
			$str = nl2br($str);
			return $str;
		}

		$str_new = mb_substr($str, 0, $length, 'utf-8') . $etc;
		$str = htmlspecialchars($str);
		$str_new = htmlspecialchars($str_new);
		$str_new = nl2br($str_new);

		if (strlen($str) > 1000)
		{
			$str = substr($str, 0, 1000);
		}

		$html = "<span title=\"$str\">$str_new</span>";
        
        return $html;
	}
}