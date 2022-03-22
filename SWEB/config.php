<?php

return [
    'name' => '简易PHP框架',
    'user_list' => [
        'admin' => ['name' => '管理员', 'password' => 'admin'],
    ],
    'db' => [
        'dsn'      => 'mysql:dbname=test;host=127.0.0.1;port=3306;charset=utf8',
        'user'     => 'root',
        'password' => '',
        'overtime' => 1000,
    ],
];