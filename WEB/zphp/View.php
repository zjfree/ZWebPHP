<?php
namespace zphp;

/**
 * 页面使用工具类
 */
class View
{
	private static $sys = [];

	/**
	 * 获取公共值
	 */
	public static function getSys()
	{
		self::$sys['sys_name'] = Sys::getConfig('sys_name');
		self::$sys['now'] = date('Y-m-d H:i:s');
		self::$sys['browser'] = self::isMobile() ? 'mobile' : 'pc';

		return self::$sys;
	}

	/**
	 * 设置公共值
	 */
	public static function setSys($key, $val)
	{
		self::$sys[$key] = $val;
	}

	/**
	 * 是否手机
	 */
	public static function isMobile()
	{  
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
		$mobile_browser = '0';  
		if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
			$mobile_browser++;  
		if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
			$mobile_browser++;  
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
			$mobile_browser++;  
		if(isset($_SERVER['HTTP_PROFILE']))  
			$mobile_browser++;  
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
		$mobile_agents = array(  
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
			'wapr','webc','winw','winw','xda','xda-' 
			);  
		if(in_array($mobile_ua, $mobile_agents))  
			$mobile_browser++;  
		if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
			$mobile_browser++;  
		// Pre-final check to reset everything if the user is on Windows  
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
			$mobile_browser=0;  
		// But WP7 is also Windows, with a slightly different characteristic  
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
			$mobile_browser++;  

		return $mobile_browser > 0;
	}

	/**
	 * 是否有权限
	 */
	public static function hasPower($str)
	{
		return User::hasPower($str);
	}

	/**
	 * 获取枚举值列表
	 */
	public static function getEnumList($key)
	{
		$item = Sys::getEnum($key);
		if (empty($item))
		{
			return [];
		}

		return $item['list'];
	}

	/**
	 * 格式化输出菜单HTML
	 */
	public static function formatMenu()
	{
		$user = User::current();
		$menu = $user['menu_list'];

		$html = PHP_EOL . '<!--菜单 BEGIN-->' . PHP_EOL;

		foreach ($menu as $k => $r)
		{
			if (isset($r['title']))
			{
				// 大标题
				$html .= '<h3>';
				if (!empty($r['icon']))
				{
					$html .= '<i class="' . $r['icon'] . '"></i> ';
				}
				$html .= $r['title'] . '</h3>' . PHP_EOL;
			}
			else if (isset($r['list']))
			{
				// 二级菜单
				$html .= '<div class="level-1' . (empty($r['open']) ? '' : ' open') . '">' . PHP_EOL;
				$html .= self::getLinkHtml($k, $r);
				$html .= "\t" . '<div class="level-2">'. PHP_EOL;
				foreach ($r['list'] as $kk => $rr)
				{
					$html .= "\t\t" . self::getLinkHtml($k . '_' . $kk, $rr);
				}
				$html .= "\t" . '</div>' . PHP_EOL;
				$html .= '</div>' . PHP_EOL;
			}
			else
			{
				// 一级菜单
				$html .= self::getLinkHtml($k, $r);
			}
		}

		$html .= '<!--菜单 END-->' . PHP_EOL;

		return $html;
	}

	// 获取连接HTML
	private static function getLinkHtml($k, $r)
	{
		$html = '';
		//<a id="NavMenu_table" href="table.php"><i class="fa fa-table"></i> 表格样式 <span class="badge badge-info">9</span></a>
		$html .= '<a id="NavMenu_' . $k . '" href="' . (@$r['href'] ?: '#') . '"';
		if (!empty($r['target']))
		{
			$html .= ' target="' . $r['target'] . '"';
		}
		if (!empty($r['color']))
		{
			$html .= ' style="color:' . $r['color'] . ';"';
		}
		$html .= '>';
		if (!empty($r['icon']))
		{
			$html .= '<i class="' . $r['icon'] . '"></i> ';
		}
		$html .= $r['name'];
		if (!empty($r['badge']))
		{
			$arr = explode(':', $r['badge']);
			if (count($arr) == 1)
			{
				$html .= ' <span class="badge badge-info">' . $arr[0] . '</span>';
			}
			else
			{
				$html .= ' <span class="badge badge-' . $arr[0] . '">' . $arr[1] . '</span>';
			}
		}
		$html .= '</a>' . PHP_EOL;

		return $html;
	}
}