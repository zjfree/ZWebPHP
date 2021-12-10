<?php

// 菜单列表
// Primary Secondary Success Danger Warning Info Light Dark
$menu_list = [
    'sys' => ['name' => '系统维护', 'icon' => 'fa fa-cog',
        'list' => [
            'user'         => ['name' => '用户管理', 'icon' => 'fa fa-user', 'href' => '/index.php?_=user_manage',],
            'user_type'    => ['name' => '用户类型', 'icon' => 'fa fa-users', 'href' => '/index.php?_=user_type',],
            'config'       => ['name' => '系统设置', 'icon' => 'fa fa-sliders', 'href' => '/index.php?_=sys_setting',],
            'api'          => ['name' => 'API文档', 'icon' => 'fa fa-book', 'href' => '/index.php?_=doc', 'target' => '_blank'],
            'log'          => ['name' => '操作日志', 'icon' => 'fa fa-file-text', 'href' => '/index.php?_=sys_base::log',],
            'login_log'    => ['name' => '登录日志', 'icon' => 'fa fa-user-md', 'href' => '/index.php?_=sys_base::login_log',],
            'debug'        => ['name' => '系统调试', 'icon' => 'fa fa-bug', 'href' => '/index.php?_=sys_base::debug',],
        ],
    ],
    
    'client' => ['name' => '客户维护', 'icon' => 'fa fa-vcard', 'href' => '/index.php?_=client',],
    'about'  => ['name' => '关于我们', 'icon' => 'fa fa-info-circle', 'href' => '/index.php?_=sys::about'],
    'help'   => ['name' => '系统帮助', 'icon' => 'fa fa-book', 'href' => '/index.php?_=sys::help'],

    'ui' => ['title' => '框架帮助', 'icon' => 'fa fa-diamond'],
    'ui_index' => ['name' => '框架概述', 'icon' => 'fa fa-umbrella', 'href' => '/index.php?_=ui::index'],
    'ui_tmp'   => ['name' => '未整理样式', 'icon' => 'fa fa-cubes', 'href' => '/index.php?_=ui::tmp'],
    'ui_base'  => ['name' => '基本样式', 'icon' => 'fa fa-diamond', 'href' => '/index.php?_=ui::base'],
    'ui_db'    => ['name' => '数据库', 'icon' => 'fa fa-database', 'href' => '/index.php?_=ui::db'],
    'ui_tool'  => ['name' => '工具类', 'icon' => 'fa fa-wrench', 'href' => '/index.php?_=ui::tool'],
    'ui_api'   => ['name' => 'API开发', 'icon' => 'fa fa-plug', 'href' => '/index.php?_=ui::api'],
    'ui_mq'    => ['name' => '消息队列', 'icon' => 'fa fa-gg', 'href' => '/index.php?_=ui::file_mq'],
    'ui_icon'    => ['target' => '_blank', 'name' => '系统图标', 'icon' => 'fa fa-plane', 'href' => '/tool/icon_fa.html'],
    'ui_table' => ['name' => '表格控制', 'icon' => 'fa fa-table',
        'list' => [
            'table1' => ['name' => '表格列表', 'icon' => 'fa fa-table', 'href' => '/index.php?_=ui::table1',],
            'table2' => ['name' => '表格大数据1', 'icon' => 'fa fa-table', 'href' => '/index.php?_=ui::table2',],
            'table3' => ['name' => '表格大数据2', 'icon' => 'fa fa-table', 'href' => '/index.php?_=ui::table3',],
        ],
    ],
    'ui_form' => ['name' => '表单提交', 'icon' => 'fa fa-pencil-square-o',
        'list' => [
            'form1' => ['name' => '弹出表单', 'icon' => 'fa fa-list-alt', 'href' => '/index.php?_=ui::form1',],
            'form2' => ['name' => '基础表单', 'icon' => 'fa fa-list-alt', 'href' => '/index.php?_=ui::form2',],
            'form3' => ['name' => '扩展表单', 'icon' => 'fa fa-list-alt', 'href' => '/index.php?_=ui::form3',],
            'form4' => ['name' => '分隔表单', 'icon' => 'fa fa-list-alt', 'href' => '/index.php?_=ui::form4',],
        ],
    ],
    'ui_page' => ['name' => '独立页', 'icon' => 'fa fa-sticky-note',
        'list' => [
            'page_empty' => ['target' => '_blank', 'name' => '空白页', 'icon' => 'fa fa-sticky-note', 'href' => '/index.php?_=ui::page_empty',],
        ],
    ],
    'ui_menu' => ['name' => '菜单功能演示', 'icon' => 'fa fa-plug',
        'list' => [
            'echart' => ['name' => 'EChart', 'icon' => 'fa fa-area-chart', 'href' => ''],
            'chart'  => ['name' => 'chart.js', 'icon' => 'fa fa-bar-chart', 'href' => '', 'color' => '#ff0'],
            'jstree' => ['name' => 'jsTree', 'icon' => 'fa fa-tree', 'href' => '', 'badge' => '8'],
            'leafletjs' => ['name' => 'leafletjs', 'icon' => 'fa fa-map', 'href' => '', 'badge' => 'danger:100'],
        ],
    ],
];

return $menu_list;