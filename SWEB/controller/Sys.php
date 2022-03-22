<?php

class SysController extends Sys
{
    public static function index()
    {
        DB::insert('a1', [
            'name' => 'a1a1',
            'add_time' => '::now()',
        ]);

        $list = DB::runSql("SELECT * FROM a1");

        DB::update('a1', [
            'id' => 1,
            'name' => 'a1a1a2',
            'add_time' => '::now()',
        ]);

        return self::_success('123');
    }
    
    public static function ajax()
    {
        return self::_success('123');
    }
}