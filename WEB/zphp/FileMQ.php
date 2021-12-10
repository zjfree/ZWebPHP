<?php
namespace zphp;

/**
 * 基于文件的消息队列
 */
class FileMQ
{
    const LINE_MAX = 4096;

    private $file_content = '';
    private $file_cfg = '';
    private $file_cur = '';

    function __construct($key = '_base')
    {
        $this->file_content = ROOT_PATH . 'runtime/mq/' . $key . '.mq';
        $this->file_cfg = ROOT_PATH . 'runtime/mq/' . $key . '.mq_cfg';
        $this->file_cur = ROOT_PATH . 'runtime/mq/' . $key . '.mq_cur';
    }

    private static function now()
    {
        return date('Y-m-d H:i:s');
    }

    public function getCfg()
    {
        $now = self::now();
        if (!file_exists($this->file_cfg))
        {
            return [
                'cur' => 0,
                'len' => 0,
                'read_time'  => $now,
                'write_time' => $now,
                'create_time' => $now,
            ];
        }

        $content = \file_get_contents($this->file_cfg);
        $cfg = json_decode($content, true);

        return $cfg;
    }

    private function setCfg($cfg)
    {
        file_put_contents($this->file_cfg, \json_encode($cfg));
    }

	/**
	 * 入栈
	 */
	public function push($data)
	{
        $this->pushList([$data]);
	}
    
	/**
	 * 批量入栈
	 */
	public function pushList($list)
	{
        if (empty($list) || !is_array($list))
        {
            return;
        }

        $now = self::now();

        $cfg = $this->getCfg();
        $cfg['len'] += count($list);
        $cfg['write_time'] = $now;
        $this->setCfg($cfg);

        $str_append = '';
        foreach ($list as $r)
        {
            $str = base64_encode(\json_encode($r));
            $str_append .= $now . ',' . strlen($str) . ',' . $str . PHP_EOL;
        }
        
        file_put_contents($this->file_content, $str_append, FILE_APPEND | LOCK_EX);
	}

	/**
	 * 清空
	 */
    public function clear()
    {
        unlink($this->file_content);
        unlink($this->file_cur);
        
        $cfg = $this->getCfg();
        $cfg['cur'] = 0;
        $this->setCfg($cfg);
    }
    
	/**
	 * 出栈
	 */
	public function pop()
	{
        $list = $this->popList(1);
        if (empty($list))
        {
            return null;
        }

        return $list[0];
	}
    
	/**
	 * 批量出栈
	 */
	public function popList($count)
	{
        if ($count < 1)
        {
            return [];
        }

        $now = self::now();

        $cfg = $this->getCfg();
        if (!file_exists($this->file_cur))
        {
            if (file_exists($this->file_content))
            {
                $cfg['cur'] = 0;
                rename($this->file_content, $this->file_cur);
            }
            else
            {
                return [];
            }
        }

        $cur = $cfg['cur'];
        $cfg['cur'] += $count;
        $cfg['read_time'] = $now;
        $this->setCfg($cfg);

        $handle = @fopen($this->file_cur, "r");
        if (!$handle)
        {
            return [];
        }
        
        $list = [];
        $i = 0;
        $icount = 0;
        while (($str_line = fgets($handle, self::LINE_MAX)) !== false)
        {
            if (empty($str_line) || $icount >= $count)
            {
                break;
            }

            if ($i >= $cur)
            {
                $icount++;
                if ($icount <= $count)
                {
                    $arr = explode(',', trim($str_line));
                    if (count($arr) != 3)
                    {
                        continue;
                    }
            
                    $list[] = json_decode(base64_decode($arr[2]), true);
                }
            }
            $i++;
        }
        
        fclose($handle);

        if (empty($str_line))
        {
            unlink($this->file_cur);
        }

        return $list;
	}
}