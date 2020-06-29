<?php
namespace zphp;

/**
 * 日志类
 */
class Log
{
    /**
     * 写入文件日志
     */
    public static function writeFile($str, $type = 'log')
    {
        if (!is_string($str))
        {
            $str = (is_array($str) ? PHP_EOL : '') . var_export($str, true) . PHP_EOL;
        }

		$file = ROOT_PATH . 'runtime/log/' . $type . '_' . date('Ymd') . '.log';
		$str = date('Y-m-d H:i:s ') . $str . PHP_EOL;

		file_put_contents($file, $str, FILE_APPEND|LOCK_EX);
    }

    /**
     * 写入错误日志
     */
    public static function error($str)
    {
        if ($str instanceof  \Exception)
        {
            $str = '' . $str . PHP_EOL;
        }

        self::writeFile($str, 'error');
    }

    /**
     * 写入业务日志
     */
    public static function add($str, $type = '日志')
    {
        $user = User::current();
        
        if (empty($user))
        {
            DB::table('sys_log')
                -> insert([
                    'user_id'   => 0,
                    'user_name' => '系统',
                    'user_type' => 'sys',
                    'type'      => $type,
                    'content'   => $str,
                    'add_time'  => '::now()',
                ]);
        }
        else
        {
            DB::table('sys_log')
                -> insert([
                    'user_id'   => $user['id'],
                    'user_name' => $user['name'] . ' (' . $user['account'] . ')',
                    'user_type' => 'user',
                    'type'      => $type,
                    'content'   => $str,
                    'add_time'  => '::now()',
                ]);
        }
    }
    
    /**
     * 调式
     */
    public static function debug()
    {
        $params = func_get_args();

        $list = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        $file = ROOT_PATH . 'runtime/log/debug.log';
        
        if (@filesize($file) > 1024*1024)
        {
            unlink($file);
        }

        $str = date('Y-m-d H:i:s') . ' [' . Tool::ip() . '] ' . $list[0]['file'] . '(' . $list[0]['line'] . ') ';
        
        if (empty($params))
        {
            $str .= 'NULL';
        }
        elseif (count($params) == 1)
        {
            if (is_string($params[0]))
            {
                $str .= $params[0];
            }
            else
            {
                $str .= var_export($params[0], true);
            }
        }
        else
        {
            $str .= json_encode($params, JSON_UNESCAPED_UNICODE);
            if (strlen($str) > 300)
            {
                $str = \substr($str, 0, 300) . '...';
            }
        }

        $str .= PHP_EOL;

        @file_put_contents($file, $str, FILE_APPEND|LOCK_EX);
    }
}