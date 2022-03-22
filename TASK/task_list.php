<?php

return [
    'mail_send' => ['name' => '邮件发送', 'url' => 'http://api.com/index.php?_=mail::check', 'timeout' => 50, 'sleep' => 1, ],
    'sms_send' => ['name' => '短信发送', 'url' => 'http://api.com/index.php?_=sms::check', 'timeout' => 50, 'sleep' => 1, ],
    'aci_alarm' => ['name' => '短信发送', 'url' => 'http://api.com/index.php?_=sync::check_alarm&_ajax=1', 'timeout' => 50, 'sleep' => 5, ],
    'stat' => ['name' => '统计测试','url' => 'http://api.com/tool/ajax.php', 'time' => 'Y-m-d H:i', 'value' => 'Y-m-d 14:02' ],
];