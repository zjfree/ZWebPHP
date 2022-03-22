<?php

set_time_limit(0);
define('ROOT_PATH', dirname(dirname(__FILE__)));

require ROOT_PATH . '/Tool.php';

$task_list = require ROOT_PATH . '/task_list.php';
$task_key = basename(__FILE__, '.php');
$task_key = substr($task_key, 5);

if (empty($task_list[$task_key]))
{
    Tool::addLog('任务[' . $task_key . '] 不存在');
    exit;
}
$task = $task_list[$task_key];

Tool::addLog(json_encode($task, JSON_UNESCAPED_UNICODE), $task_key);

if (empty($task['time']))
{
    // 定时任务
    $sleep = intval(@$task['sleep'] ?: 1);
    $sleep = max($sleep, 1);
    $timeout = intval(@$task['timeout'] ?: 10);
    $timeout = max($timeout, 1);

    while (true)
    {
        sleep($sleep);
        $res = Tool::urlGet($task['url'], $timeout);
        Tool::addLog($res, $task_key);
    }
}
else
{
    // 统计任务
    $data_file = ROOT_PATH . '/data/' . $task_key . '.txt';
    $last_data = @file_get_contents($data_file);
    if (empty($last_data))
    {
        $last_data = [
            'scan_time' => Tool::now(),
            'run_time'  => '',
        ];
    }
    else
    {
        $last_data = json_decode($last_data, true);
    }

    $timeout = intval(@$task['timeout'] ?: 10);
    $timeout = max($timeout, 1);
    while (true)
    {
        $last_data['scan_time'] = Tool::now();
        $time = date($task['time']);
        $value = date($task['value']);
        if ($time == $value && $last_data['run_time'] != $value)
        {
            $res = Tool::urlGet($task['url'], $timeout);
            Tool::addLog($res, $task_key);
            $last_data['run_time'] = $value;
        }

        file_put_contents($data_file, json_encode($last_data));

        sleep(30);
    }
}