<?php

class Sys
{
    public static $config = [];
    public static $user = null;

    public static function _success($data = null)
    {
        $res = [
            'result' => 1,
            'data' => $data,
        ];

        return $res;
    }
    
    public static function _error($error, $code = 0)
    {
        $res = [
            'result' => $code,
            'error' => $error,
        ];

        return $res;
    }

    public static function _login($account, $passwd)
    {
        $user_list = @self::$config['user_list'] ?: [];
        if (!isset($user_list[$account]))
        {
            return false;
        }

        $user = $user_list[$account];
        if ($user['password'] != $passwd)
        {
            return false;
        }

        $user['account'] = $account;
        self::$user = $user;
        
        return true;
    }
}