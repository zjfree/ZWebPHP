<?php
namespace zphp;

class Rand
{
	const CHAR_LIST = 'ABCDEFGHIJKLMNOPRQSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	// 获取GUID
	public static function guid($hyphen = '')
	{
		// $hyphen = '-';
		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
		$uuid = ''
			. substr($charid,  0,  8) . $hyphen
			. substr($charid,  8,  4) . $hyphen
			. substr($charid, 12,  4) . $hyphen
			. substr($charid, 16,  4) . $hyphen
			. substr($charid, 20, 12);

		return $uuid;
	}

	// 生产数字
	public static function num($min = 1, $max = 100, $dec = 0)
	{
		$res = 0;
		if ($dec == 0)
		{
			$res = mt_rand($min, $max);
		}
		else
		{
			$v = pow(10, $dec);;
			$res = mt_rand($min*$v, $max*$v);
			$res = number_format($res / $v, $dec, '.', '');
		}

		return $res;
	}

	// 生成字符串
	public static function str($count = 10, $count_max = null, $char_str = null)
	{
		$c = $count_max === null ? $count : mt_rand($count, $count_max);
		$char_str = $char_str ?: self::CHAR_LIST;

		$res = '';
		$len = strlen($char_str) - 1;
		for ($i=0; $i<$c; $i++)
		{
			$res .= $char_str[mt_rand(0, $len)];
		}

		return $res;
	}

	// 时间
	public static function time($begin = '2000-01-01', $end = null)
	{
		$begin = Tool::strtotime($begin);
		$end = $end === null ? time() : Tool::strtotime($end);

		$res = mt_rand($begin, $end);
		$res = Tool::date('Y-m-d H:i:s', $res);

		return $res;
	}

	// 日期
	public static function date($begin = '2000-01-01', $end = null)
	{
		$begin = Tool::strtotime($begin);
		$end = $end === null ? time() : Tool::strtotime($end . ' 23:59:59');

		$res = mt_rand($begin, $end);
		$res = Tool::date('Y-m-d', $res);

		return $res;
	}

	// 汉字
	public static function ch($count = 10, $count_max = null)
	{
		$res = '';
		$c = $count_max === null ? $count : mt_rand($count, $count_max);

		for ($i=0; $i<$c; $i++)
		{
			$res .= html_entity_decode('&#'.mt_rand(19968, 40869).';',ENT_NOQUOTES,'UTF-8');
		}

		return $res;
	}

	// 颜色
	public static function color()
	{
		$res = '#' . self::str(6, 6, '0123456789ABCDEF');

		return $res;
	}

	// 连续数字
	private static $numAddArr = [];
	public static function numAdd($key='k1', $min=1, $max=100, $step=[1,-1])
	{
		if (!isset(self::$numAddArr[$key]))
		{
			self::$numAddArr[$key] = [
				'min'   => $min,
				'max'   => $max,
				'step'  => $step,
				'value' => mt_rand($min, $max),
			];
		}

		$last = self::$numAddArr[$key];
		$res = $last['value'];
		if (isset($last['step']['min']))
		{
			$res += mt_rand($last['step']['min'], $last['step']['max']);
		}
		else
		{
			$res += $last['step'][mt_rand(0, count($last['step'])-1)];
		}
		$res = max($last['min'], $res);
		$res = min($last['max'], $res);
		self::$numAddArr[$key]['value'] = $res;

		return $res;
	}
	
	// 列表随机
	public static function one($list = ['hello'])
	{
		if (array_keys($list)[0] !== 0)
		{
			$total = array_sum($list);
			$val = mt_rand(0, $total);
			$res = '';
			$sum = 0;
			foreach ($list as $k => $v)
			{
				$sum += $v;
				if ($val < $sum)
				{
					$res = $k;
					break;
				}
			}

			return $res;
		}
		else
		{
			$k = mt_rand(0, count($list) - 1);
		}

		return $list[$k];
	}
	
	// 顺序ID
	private static $idArr = [];
	public static function id($key='k1', $id=1)
	{
		$res = $id;
		if (isset(self::$idArr[$key]))
		{
			self::$idArr[$key]++;
			$res = self::$idArr[$key];
		}
		else
		{
			self::$idArr[$key] = $id;
		}

		return $res;
	}
}
