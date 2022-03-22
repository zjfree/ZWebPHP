<?php

class DB
{
    private static $conn = null;

    private static function connect()
    {
        if (self::$conn != null)
        {
            return self::$conn;
        }

        $db = Sys::$config['db'];
		try
		{
			$conn = new PDO($db['dsn'], $db['user'], $db['password'], [
				PDO::ATTR_PERSISTENT => TRUE,
			]);
		}
        catch (\Exception $ex)
        {
            Tool::addLog('DB connect error!' . PHP_EOL . $ex, 'error');
            throw new \Exception('DB connect error!');
		}

		$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn -> setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        
        self::$conn = $conn;
        
        return $conn;
    }

    public static function runSql($sql, $param = [])
    {
        $sql = trim($sql);
        $sql_type = strtoupper(explode(' ', $sql)[0]);
        
        if (strpos($sql, 'INTO OUTFILE') !== FALSE)
        {
            $sql_type = 'OUTFILE';
        }

        $con = self::connect();
        $cmd = $con -> prepare($sql);
        
        try
        {
            $cmd -> execute($param);
        }
        catch (\Exception $ex)
        {
            $show_sql = str_replace("\t", '', $sql);
            $show_sql = str_replace('    ', '', $sql);
            $log = 'SQL ERROR ' . PHP_EOL . '[SQL]' . PHP_EOL . $show_sql . PHP_EOL;
            if (!empty($param))
            {
                $log .= '[参数]' . PHP_EOL . var_export($param, true) . PHP_EOL;
            }
            $log .= '[错误详情]' . PHP_EOL . $ex . PHP_EOL;

            Tool::addLog($log, 'error');

            throw $ex;
        }

        $return = NULL;
        if ($sql_type == 'SELECT' || $sql_type == 'SHOW')
        {
            $return = $cmd -> fetchAll(PDO::FETCH_ASSOC);
        }
        else if ($sql_type == 'INSERT')
        {
            $return = $con -> lastInsertId();
        }
        else
        {
            $return = $cmd -> rowCount();
        }
        
        return $return;
    }
    
	/**
     * 查找一个
     */
    public static function find($sql, $param = [])
    {
        $res = self::runSql($sql, $param);

        if (empty($res))
        {
            return $res;
        }

        return $res[1];
    }

	/**
     * 插入
     */
	public static function insert($table, $data)
	{
        $field_list = [];
        $value_list = [];
        $param_index = 1;
        $param = [];
        foreach ($data as $field => $val)
        {
            $field_list[] = $field;
            if (strpos($val, '::') === 0)
            {
                $value_list[] = substr($val, 2);
            }
            else
            {
                $key = 'p' . $param_index++;
                $param[$key] = $val;
                $value_list[] = ':' . $key;
            }
        }

        $sql = 'INSERT INTO ';
        $sql .= $table . ' (' . join(',', $field_list) . ')' . PHP_EOL;
        $sql .= 'VALUE(' . join(',', $value_list) . ')';

        $res = self::runSql($sql, $param);

        return $res;
    }
    
	/**
     * 更新
     */
	public static function update($table, $data, $where = '')
	{
        $set_list = [];
        $param_index = 1;
        $param = [];
        foreach ($data as $field => $val)
        {
            if (\strtolower($field) == 'id' && empty($where))
            {
                $where = 'id = ' . $val;
                continue;
            }

            $value = '';
            if (strpos($val, '::') === 0)
            {
                $value = substr($val, 2);
            }
            else
            {
                $key = 'p' . $param_index++;
                $param[$key] = $val;
                $value = ':' . $key;
            }
            
            $set_list[] = $field . '=' . $value;
        }

        if (empty($set_list))
        {
            throw new \Exception('DB update field not find!');
        }

        $sql = 'UPDATE ' . $table . PHP_EOL;
        $sql .= 'SET ' . join(',', $set_list) . PHP_EOL;
        if (!empty($where))
        {
            $sql .= 'WHERE ' . $where;
        }

        $res = self::runSql($sql, $param);

        return $res;
    }
}