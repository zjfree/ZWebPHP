<?php
namespace zphp;

use app\EnumConst;

class ApiBase
{
    // 是否需要登录
    protected static $need_login = true;

    /**
     * 默认静态方法
     */
    public static function  __callStatic($name, $arguments) 
    {
        return self::_error('方法【' . $name . '】不存在');
    }

    /**
     * 是否需要登录
     */
    public static function _getNeedLogin()
    {
        return static::$need_login;
    }
    
    /**
     * 初始化
     */
    public static function _init($api, $params = [])
    {
        return self::_success();
    }

    /**
     * 成功返回
     */
    protected static function _success($data = null, $ext = null)
    {
        $res = [
            'result' => EnumConst::RESULT_SUCCESS,
            'data'   => $data,
        ];

        if ($ext !== null)
        {
            $res = array_merge($res, $ext);
        }

        return $res;
    }

    /**
     * 错误返回
     */
    protected static function _error($error, $code = EnumConst::RESULT_FAIL)
    {
        $res = [
            'result' => $code,
            'error' => $error,
        ];

        return $res;
    }
    
    /**
     * 强制终止
     */
    protected static function _exit($is_text = true)
    {
        if ($is_text)
        {
		    header('Content-type: text/plain; charset=utf-8');
        }

        $res = [
            'result' => EnumConst::RESULT_SUCCESS,
            'data'   => null,
            'exit'   => true,
        ];

        return $res;
    }
}