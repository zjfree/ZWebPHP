#!/usr/bin/php
<?php

set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));

require 'Tool.php';

Tool::addLog('系统停止');

// 关闭任务进程
$output = [];
exec('ps -ef|grep task_list|grep -v "grep"', $output);
for ($i=0; $i<count($output); $i++)
{
    Tool::show($output[$i]);
    Tool::killTask($output[$i]);
}

// 启动完成
Tool::addLog('系统停止完成');
