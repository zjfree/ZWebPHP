<?php return array (
  'build_time' => '2021-12-10 09:08:53',
  'class_list' => 
  array (
    0 => 
    array (
      'code' => 'Client',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/Client.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '客户维护',
        'memo' => '客户维护管理操作类',
        'author' => 'zjfree',
        'date' => '2019-03-12',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '列表页',
            'memo' => '列表查询页',
            'author' => 'zjfree',
            'date' => '2019-03-12',
            'return' => '客户列表',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'client_type',
              'name' => '客户类型',
              'type' => 'int',
              'require' => false,
              'value' => '0',
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'query',
              'name' => '查询关键词',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'sort',
              'name' => '排序',
              'type' => 'int',
              'require' => false,
              'value' => '0',
              'memo' => '',
            ),
          ),
        ),
        1 => 
        array (
          'code' => 'add',
          'doc' => 
          array (
            'name' => '添加页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        2 => 
        array (
          'code' => 'edit',
          'doc' => 
          array (
            'name' => '编辑页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'client',
            ),
          ),
        ),
        3 => 
        array (
          'code' => 'save',
          'doc' => 
          array (
            'name' => '保存',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => false,
              'value' => '0',
              'memo' => 'client',
            ),
            1 => 
            array (
              'code' => 'name',
              'name' => '名称',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'status',
              'name' => '状态',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'client_type',
              'name' => '客户类型',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            4 => 
            array (
              'code' => 'ext',
              'name' => '扩展属性',
              'type' => 'array',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            5 => 
            array (
              'code' => 'memo',
              'name' => '备注',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        4 => 
        array (
          'code' => 'update_status',
          'doc' => 
          array (
            'name' => '状态更新',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'client',
            ),
            1 => 
            array (
              'code' => 'status',
              'name' => '状态',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        5 => 
        array (
          'code' => 'delete',
          'doc' => 
          array (
            'name' => '删除',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'client',
            ),
          ),
        ),
        6 => 
        array (
          'code' => 'upload_img',
          'doc' => 
          array (
            'name' => '上传图片',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'client',
            ),
            1 => 
            array (
              'code' => 'img_url',
              'name' => '图片网址',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
      ),
    ),
    1 => 
    array (
      'code' => 'Doc',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/Doc.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '文档中心',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '首页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
      ),
    ),
    2 => 
    array (
      'code' => 'LocalService',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/LocalService.php',
      'need_login' => false,
      'doc' => 
      array (
        'name' => '本地服务',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'test',
          'doc' => 
          array (
            'name' => '测试',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
      ),
    ),
    3 => 
    array (
      'code' => 'My',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/My.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '当前用户',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '系统首页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        1 => 
        array (
          'code' => 'passwd',
          'doc' => 
          array (
            'name' => '修改密码页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        2 => 
        array (
          'code' => 'passwd_save',
          'doc' => 
          array (
            'name' => '修改密码保存',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'old_passwd',
              'name' => '原密码',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'new_passwd1',
              'name' => '新密码',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'new_passwd2',
              'name' => '确认密码',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        3 => 
        array (
          'code' => 'edit',
          'doc' => 
          array (
            'name' => '编辑页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        4 => 
        array (
          'code' => 'save',
          'doc' => 
          array (
            'name' => '保存',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'name',
              'name' => '',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'phone',
              'name' => '',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'email',
              'name' => '',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'weixin',
              'name' => '',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            4 => 
            array (
              'code' => 'memo',
              'name' => '',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        5 => 
        array (
          'code' => 'save_memo',
          'doc' => 
          array (
            'name' => '保存备忘信息',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'memo',
              'name' => '备忘信息',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
      ),
    ),
    4 => 
    array (
      'code' => 'Service',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/Service.php',
      'need_login' => false,
      'doc' => 
      array (
        'name' => '服务',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'client_upload_cam',
          'doc' => 
          array (
            'name' => '客户手机拍照上传',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'client',
            ),
            1 => 
            array (
              'code' => 'img_url',
              'name' => '图片网址',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
      ),
    ),
    5 => 
    array (
      'code' => 'Sys',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/Sys.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '系统',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '系统首页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        1 => 
        array (
          'code' => 'phone_index',
          'doc' => 
          array (
            'name' => '手机首页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        2 => 
        array (
          'code' => 'about',
          'doc' => 
          array (
            'name' => '关于我们',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        3 => 
        array (
          'code' => 'help',
          'doc' => 
          array (
            'name' => '系统帮助',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
      ),
    ),
    6 => 
    array (
      'code' => 'SysBase',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/SysBase.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '系统基础',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'debug',
          'doc' => 
          array (
            'name' => '调试页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        1 => 
        array (
          'code' => 'debug_info',
          'doc' => 
          array (
            'name' => '调试信息显示',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'md5',
              'name' => '',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        2 => 
        array (
          'code' => 'debug_info_clear',
          'doc' => 
          array (
            'name' => '调试信息清空',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        3 => 
        array (
          'code' => 'phpinfo',
          'doc' => 
          array (
            'name' => 'php调试信息',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        4 => 
        array (
          'code' => 'file_log_clear',
          'doc' => 
          array (
            'name' => '清空文件日志',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        5 => 
        array (
          'code' => 'user_log_clear',
          'doc' => 
          array (
            'name' => '清空用户操作日志',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        6 => 
        array (
          'code' => 'upload_clear',
          'doc' => 
          array (
            'name' => '清空上传文件',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        7 => 
        array (
          'code' => 'db_query',
          'doc' => 
          array (
            'name' => '数据库查询',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'sql',
              'name' => '',
              'type' => 'string',
              'require' => false,
              'value' => 'SHOW TABLE STATUS',
              'memo' => '',
            ),
          ),
        ),
        8 => 
        array (
          'code' => 'err',
          'doc' => 
          array (
            'name' => '系统错误测试页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        9 => 
        array (
          'code' => 'log',
          'doc' => 
          array (
            'name' => '系统操作日志',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'dt',
              'name' => '查询日期',
              'type' => 'date',
              'require' => false,
              'value' => 'today',
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'page',
              'name' => '分页',
              'type' => 'int',
              'require' => false,
              'value' => '1',
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'page_size',
              'name' => '单页数量',
              'type' => 'int',
              'require' => false,
              'value' => '30',
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'type',
              'name' => '类型',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            4 => 
            array (
              'code' => 'user',
              'name' => '用户',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        10 => 
        array (
          'code' => 'login_log',
          'doc' => 
          array (
            'name' => '用户登录日志',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'dt',
              'name' => '查询日期',
              'type' => 'date',
              'require' => false,
              'value' => 'today',
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'page',
              'name' => '分页',
              'type' => 'int',
              'require' => false,
              'value' => '1',
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'page_size',
              'name' => '单页数量',
              'type' => 'int',
              'require' => false,
              'value' => '30',
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'user',
              'name' => '用户',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        11 => 
        array (
          'code' => 'update_text',
          'doc' => 
          array (
            'name' => '更新文本字段',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'table',
              'name' => '表名',
              'type' => 'code',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'field',
              'name' => '字段名',
              'type' => 'code',
              'require' => false,
              'value' => 'name',
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'val',
              'name' => '更新值',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
      ),
    ),
    7 => 
    array (
      'code' => 'SysSetting',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/SysSetting.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '系统配置',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '基础配置',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        1 => 
        array (
          'code' => 'save',
          'doc' => 
          array (
            'name' => '基础配置',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'type',
              'name' => '类型标识',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'arr',
              'name' => '数据项',
              'type' => 'any',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
      ),
    ),
    8 => 
    array (
      'code' => 'Ui',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/Ui.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => 'UI类',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '首页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        1 => 
        array (
          'code' => 'tmp',
          'doc' => 
          array (
            'name' => '未整理',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        2 => 
        array (
          'code' => 'base',
          'doc' => 
          array (
            'name' => '基础样式',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        3 => 
        array (
          'code' => 'db',
          'doc' => 
          array (
            'name' => '数据库操作',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        4 => 
        array (
          'code' => 'tool',
          'doc' => 
          array (
            'name' => '工具类',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        5 => 
        array (
          'code' => 'api',
          'doc' => 
          array (
            'name' => 'API文档',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        6 => 
        array (
          'code' => 'chart_js',
          'doc' => 
          array (
            'name' => 'chart.js',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        7 => 
        array (
          'code' => 'echart',
          'doc' => 
          array (
            'name' => 'echart',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        8 => 
        array (
          'code' => 'table1',
          'doc' => 
          array (
            'name' => 'table1',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'page',
              'name' => '第几页',
              'type' => 'int',
              'require' => false,
              'value' => '1',
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'user_type',
              'name' => '用户类型',
              'type' => 'int',
              'require' => false,
              'value' => '0',
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'dt',
              'name' => '日期',
              'type' => 'date',
              'require' => false,
              'value' => 'today',
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'dt_begin',
              'name' => '',
              'type' => 'date',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            4 => 
            array (
              'code' => 'dt_end',
              'name' => '',
              'type' => 'date',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        9 => 
        array (
          'code' => 'table2',
          'doc' => 
          array (
            'name' => 'table2',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        10 => 
        array (
          'code' => 'table3',
          'doc' => 
          array (
            'name' => 'table3',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        11 => 
        array (
          'code' => 'form1',
          'doc' => 
          array (
            'name' => 'form1',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        12 => 
        array (
          'code' => 'form2',
          'doc' => 
          array (
            'name' => 'form2',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        13 => 
        array (
          'code' => 'form3',
          'doc' => 
          array (
            'name' => 'form3',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        14 => 
        array (
          'code' => 'form4',
          'doc' => 
          array (
            'name' => 'form4',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        15 => 
        array (
          'code' => 'form_frame',
          'doc' => 
          array (
            'name' => 'frame_form',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        16 => 
        array (
          'code' => 'page_empty',
          'doc' => 
          array (
            'name' => 'page_empty',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        17 => 
        array (
          'code' => 'page_login',
          'doc' => 
          array (
            'name' => 'page_login',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        18 => 
        array (
          'code' => 'page_register',
          'doc' => 
          array (
            'name' => 'page_register',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        19 => 
        array (
          'code' => 'file_mq',
          'doc' => 
          array (
            'name' => '测试',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'type',
              'name' => '类型',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'content',
              'name' => '内容',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
      ),
    ),
    9 => 
    array (
      'code' => 'User',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/User.php',
      'need_login' => false,
      'doc' => 
      array (
        'name' => '用户',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'login',
          'doc' => 
          array (
            'name' => '登录',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'account',
              'name' => '账号',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            1 => 
            array (
              'code' => 'passwd',
              'name' => '密码',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        1 => 
        array (
          'code' => 'logout',
          'doc' => 
          array (
            'name' => '退出',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        2 => 
        array (
          'code' => 'register',
          'doc' => 
          array (
            'name' => '注册',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
      ),
    ),
    10 => 
    array (
      'code' => 'UserManage',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/UserManage.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '用户管理',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '列表页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'user_type',
              'name' => '用户类型',
              'type' => 'int',
              'require' => false,
              'value' => '0',
              'memo' => '',
            ),
          ),
        ),
        1 => 
        array (
          'code' => 'add',
          'doc' => 
          array (
            'name' => '添加页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'user_type',
              'name' => '用户类型',
              'type' => 'int',
              'require' => false,
              'value' => '0',
              'memo' => '',
            ),
          ),
        ),
        2 => 
        array (
          'code' => 'edit',
          'doc' => 
          array (
            'name' => '编辑页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'user',
            ),
          ),
        ),
        3 => 
        array (
          'code' => 'save',
          'doc' => 
          array (
            'name' => '保存',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => false,
              'value' => '0',
              'memo' => 'user',
            ),
            1 => 
            array (
              'code' => 'user_type_id',
              'name' => '用户类型ID',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'name',
              'name' => '名称',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'account',
              'name' => '账户',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            4 => 
            array (
              'code' => 'passwd',
              'name' => '密码',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '当添加时必填',
            ),
            5 => 
            array (
              'code' => 'phone',
              'name' => '手机号',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            6 => 
            array (
              'code' => 'email',
              'name' => '电子邮箱',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            7 => 
            array (
              'code' => 'weixin',
              'name' => '微信',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            8 => 
            array (
              'code' => 'status',
              'name' => '状态',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            9 => 
            array (
              'code' => 'memo',
              'name' => '备注',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        4 => 
        array (
          'code' => 'update_status',
          'doc' => 
          array (
            'name' => '状态更新',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'user',
            ),
            1 => 
            array (
              'code' => 'status',
              'name' => '状态',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        5 => 
        array (
          'code' => 'delete',
          'doc' => 
          array (
            'name' => '删除',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'user',
            ),
          ),
        ),
        6 => 
        array (
          'code' => 'reload',
          'doc' => 
          array (
            'name' => '重新加载',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
      ),
    ),
    11 => 
    array (
      'code' => 'UserType',
      'file' => 'D:\\GIT\\ZWebPHP\\WEB\\app/api/UserType.php',
      'need_login' => true,
      'doc' => 
      array (
        'name' => '用户类型管理',
        'memo' => '',
        'author' => '',
        'date' => '',
      ),
      'fn_list' => 
      array (
        0 => 
        array (
          'code' => 'index',
          'doc' => 
          array (
            'name' => '列表页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        1 => 
        array (
          'code' => 'add',
          'doc' => 
          array (
            'name' => '添加页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
          ),
        ),
        2 => 
        array (
          'code' => 'edit',
          'doc' => 
          array (
            'name' => '编辑页',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'user_type',
            ),
          ),
        ),
        3 => 
        array (
          'code' => 'save',
          'doc' => 
          array (
            'name' => '保存',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => false,
              'value' => '0',
              'memo' => 'user_type',
            ),
            1 => 
            array (
              'code' => 'code',
              'name' => '编号',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            2 => 
            array (
              'code' => 'name',
              'name' => '名称',
              'type' => 'string',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            3 => 
            array (
              'code' => 'is_hide',
              'name' => '是否隐藏',
              'type' => 'int',
              'require' => true,
              'value' => NULL,
              'memo' => '',
            ),
            4 => 
            array (
              'code' => 'power',
              'name' => '权限',
              'type' => 'array',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            5 => 
            array (
              'code' => 'menu',
              'name' => '菜单',
              'type' => 'array',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
            6 => 
            array (
              'code' => 'memo',
              'name' => '备注',
              'type' => 'string',
              'require' => false,
              'value' => NULL,
              'memo' => '',
            ),
          ),
        ),
        4 => 
        array (
          'code' => 'delete',
          'doc' => 
          array (
            'name' => '删除',
            'memo' => '',
            'author' => '',
            'date' => '',
            'return' => '',
          ),
          'params' => 
          array (
            0 => 
            array (
              'code' => 'id',
              'name' => 'ID编号',
              'type' => 'id',
              'require' => true,
              'value' => NULL,
              'memo' => 'user_type',
            ),
          ),
        ),
      ),
    ),
  ),
);