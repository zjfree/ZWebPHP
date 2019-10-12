<?php

// 系统枚举 配置文件
return [
    'status' => [
        'name' => '状态',
        'list' => [
            0 => ['tag' => 'disabled', 'name' => '禁用', 'color' => 'red'],
            1 => ['tag' => 'normal'  , 'name' => '正常', 'color' => 'green'],
            2 => ['tag' => 'deleted' , 'name' => '删除', 'color' => 'red'],
        ],
    ],
    'result' => [
        'name' => '返回状态',
        'list' => [
            -101 => ['tag' => 'tid'      , 'name' => '_tid无效'],
            -100 => ['tag' => 'token'    , 'name' => 'token解析异常'],
            -90  => ['tag' => 'exception', 'name' => '处理异常'],
            -31  => ['tag' => 'power'    , 'name' => '无权限'],
            -30  => ['tag' => 'offline'  , 'name' => '未登录'],
            -20  => ['tag' => 'param'    , 'name' => '参数缺失'],
            -11  => ['tag' => 'view'     , 'name' => 'view页面不存在'],
            -10  => ['tag' => 'api'      , 'name' => '接口不存在'],
             -1  => ['tag' => 'fail'     , 'name' => '操作失败'],
              0  => ['tag' => 'error'    , 'name' => '错误'],
              1  => ['tag' => 'success'  , 'name' => '成功'],
              2  => ['tag' => 'custom'   , 'name' => '自定义返回'],
        ],
    ],
    'online_status' => [
        'name' => '在线状态',
        'list' => [
            0 => ['tag' => 'null'      , 'name' => '未知'],
            1 => ['tag' => 'online'    , 'name' => '在线', 'color' => '#49B000'],
            2 => ['tag' => 'offline'   , 'name' => '离线', 'color' => 'red'],
            3 => ['tag' => 'cancel'    , 'name' => '作废', 'color' => 'red'],
        ],
    ],
    
    'client_type' => [
        'name' => '客户类型',
        'list' => [
            1  => ['tag' => 'person'    , 'name' => '个人'],
            2  => ['tag' => 'company'   , 'name' => '公司'],
            3  => ['tag' => 'group'     , 'name' => '集团'],
        ],
    ],
    
];