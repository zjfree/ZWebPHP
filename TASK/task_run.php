#!/usr/bin/php
<?php

/*
===========================
PHP 基于linux的任务管理系统
===========================
2019-07-22

功能：
-----------------
1. 指定间隔时间访问指定URL地址
2. 指定时间访问指定URL地址
3. 任务管理工具

原理
-----------------
1. 使用PHP cli模式运行PHP，指定 -n 参数，允许其可执行shell
2. 启动过程，关闭正在运行PHP任务进程，依次启动PHP任务，启动守护进程；

查询进程
ps -ef|grep task_list|grep -v "grep"

ps -ef|grep "task_run.php"|grep -v "grep"

kill -9 [pid]

*/

set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));

require 'Tool.php';

Tool::addLog('系统启动');

echo <<<EOT
=============================
PHP 任务管理系统
=============================
EOT;

// $argc 参数数量  $argv 参数值列表

echo PHP_EOL;
Tool::show('PHP ' . PHP_VERSION . ' ' . php_uname());
Tool::show('当前目录：' . ROOT_PATH);

// 仅允许启动一个
$output = [];
exec('ps -ef|grep "task_run.php"|grep -v "grep"', $output);
if (count($output) > 1)
{
    for ($i=0; $i<count($output)-1; $i++)
    {
        Tool::killTask($output[$i]);
    }
}

// 关闭任务进程
$output = [];
exec('ps -ef|grep task_list|grep -v "grep"', $output);
for ($i=0; $i<count($output); $i++)
{
    Tool::killTask($output[$i]);
}

sleep(1);

// 生成任务
foreach (glob(ROOT_PATH . "/task_list/task_*.php") as $file)
{
    if (basename($file) != '_task.php')
    {
        unlink($file);
    }
}
$task_list = require 'task_list.php';
foreach ($task_list as $k => $r)
{
    copy(ROOT_PATH . '/task_list/_task.php', ROOT_PATH . '/task_list/task_' . $k . '.php');
}

sleep(1);

foreach (glob(ROOT_PATH . "/task_list/task_*.php") as $file)
{
    Tool::show($file);
    Tool::cmd('php ' . $file);
    sleep(1);
}

// 启动完成
Tool::addLog('系统启动就绪');

/*
while (true)
{
    echo '::>';
    $stdin = fopen('php://stdin', 'r');
    $line = trim(fgets($stdin));
    
    switch ($line)
    {
        case ':q':
        case ':exit':
        case ':quit':
            break 2;
        case ':help':
        case ':?':
        case '?':
            Tool::show(file_get_contents(ROOT_PATH . '/help.txt'));
            break;
        case '?task_list':
            break;
        case '':
            break;
        default:
            Tool::show($line);
            break;
    }
}

while (true)
{
    file_put_contents(ROOT_PATH . '/data/run_last_time.txt', Tool::now());
    Tool::show(Tool::now());

    sleep(5);
}

Tool::addLog('系统退出');
*/