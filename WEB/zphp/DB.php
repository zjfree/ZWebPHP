<?php
namespace zphp;

use PDO;

/**
 * 数据库操作类
 */
class DB
{
    private static $isInit = false;

	// 默认连接配置
	protected static $config = [
        'default' => [
            'dsn'      => 'mysql:dbname=test;host=127.0.0.1;port=3306;charset=utf8',
            'user'     => 'root',
            'password' => '',
            'overtime' => 1000,
        ]
    ];
    
    public static function init($config = null, $key = 'default')
    {
        if ($config === null)
        {
            $config = Sys::getConfig('db');
        }

        if (!empty($config))
        {
            self::$config[$key] = array_merge(@self::$config[$key] ?: self::$config['default'], $config);
        }

        self::$isInit = true;
    }

    public static function table($table = '', $db_key = 'default')
    {
        $db = new DB($table, $db_key);
        
        return $db;
    }
    
    public static function runSql($sql, $param = [])
    {
        $db = new DB();
        
        return $db -> call_sql($sql, $param);
    }
	
	// 数据库连接对象
    protected $conn = NULL;
    
    protected $table = '';
    protected $param = [];
    protected $paramIndex = 1;
    protected $field = '*';
    protected $join  = '';
    protected $where = '';
    protected $group = '';
    protected $having = '';
    protected $order = '';
    protected $limit = '';
    protected $json = false;
    protected $db_key = 'default';
	
	/// 连接数据库
	function __construct($table = '', $db_key = 'default')
	{
        if (!self::$isInit)
        {
            self::init();
        }

        if (empty(self::$config[$db_key]))
        {
            Log::writeFile('DB_connect[' . $db_key . '] not find!', 'db_log');
            throw new \Exception('DB_connect[' . $db_key . '] not find!');
        }

        $this -> db_key = $db_key;
        $db = self::$config[$this -> db_key];
		
		try
		{
			$conn = new PDO($db['dsn'], $db['user'], $db['password'], [
				PDO::ATTR_PERSISTENT => TRUE,
			]);
		}
        catch (\Exception $ex)
        {
            Log::writeFile('DB connect error!' . PHP_EOL . $ex, 'db_log');

            throw new \Exception('DB connect error!');
		}

		$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn -> setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        
        $this -> table = $table;
		$this -> conn = $conn;
    }
    
	/// 执行SQL
	private function call_sql($sql, $param = [])
	{
        $run_ms = Tool::ms();

		$sql = trim($sql);
		
		$sql_type = strtoupper(explode(' ', $sql)[0]);
		
		if (strpos($sql, 'INTO OUTFILE') !== FALSE)
		{
			$sql_type = 'OUTFILE';
		}

		$con = $this -> conn;
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

            Log::writeFile($log, 'db_log');

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
        
        $ms = Tool::ms($run_ms);
        if ($ms > self::$config[$this -> db_key]['overtime'])
        {
            $show_sql = str_replace("\t", '', $sql);
            $show_sql = str_replace('    ', '', $sql);
            $log = 'SQL 执行超时 ' . $ms . 'ms' . PHP_EOL . '[SQL]' . PHP_EOL . $show_sql . PHP_EOL;
            if (!empty($param))
            {
                $log .= '[参数]' . PHP_EOL . var_export($param, true) . PHP_EOL;
            }
            $log .= Tool::backtrace();

            Log::writeFile($log, 'db_log');
        }
		
		return $return;
    }

    // 快捷查询
    private function quickWhere($where)
    {
        if (\is_numeric($where))
        {
            $this -> where = 'id = :id';
            $this -> param = ['id' => $where];
        }
        else if (!empty($where))
        {
            $this -> where = $where;
        }
    }

	/**
     * JSON解析
     */
	public function json($json = true)
	{
        $this -> json = $json;

        return $this;
    }

    // 解析数组JSON元素
    private function list2json($list)
    {
        $key_list = array_keys($list[0]);
        foreach ($list as &$r)
        {
            foreach ($key_list as $k)
            {
                if (\substr($k, -5) == '_json')
                {
                    $r[\substr($k, 0, -5)] = json_decode($r[$k], true);
                    unset($r[$k]);
                }
            }
        }
        unset($r);

        return $list;
    }
    
	/**
     * 查询多条
     */
	public function select($where = '')
	{
        $this -> quickWhere($where);

        $sql = $this -> buildSelectSql();
        $res = $this -> call_sql($sql, $this -> param);

        if ($this -> json && !empty($res))
        {
            $res = $this -> list2json($res);
        }

        return $res;
    }
    
	/**
     * 查询一条
     */
	public function find($where = '')
	{
        $this -> quickWhere($where);

        $this -> limit(1);

        $sql = $this -> buildSelectSql();
        $list = $this -> call_sql($sql, $this -> param);

        if (empty($list))
        {
            return null;
        }
        
        if ($this -> json)
        {
            $list = $this -> list2json($list);
        }

        return $list[0];
    }

	/**
     * 查询一列值
     */
	public function column($field, $id = null)
	{
        if (is_string($field))
        {
            $field = \explode(',', $field);
        }

        $query_field = $field;
        if ($id !== null && !in_array($id, $field) && !in_array('*', $field))
        {
            $query_field[] = $id;
        }

        if ($this -> field == '*')
        {
            $this -> field($query_field);
        }

        $list = $this -> select();

        if (empty($list))
        {
            return [];
        }

        $value_list = [];
        if (count($field) == 1 && $field[0] != '*')
        {
            $value_list = array_column($list, $field[0], $id);
        }
        else
        {
            $value_list = array_column($list, null, $id);
        }

        return $value_list;
    }
    
	/**
     * 查询一个值
     */
	public function value($name, $default = null)
	{
        if ($this -> field == '*')
        {
            $item = $this -> field($name) -> find();
        }
        else
        {
            $item = $this -> find();
        }

        if (empty($item))
        {
            return $default;
        }

        return $item[$name];
    }
    
	/**
     * 批量处理方法
     */
	public function chunk($count, $callback)
	{
        $old_field = $this -> field;
        $total_count = $this -> count();
        $this -> field = $old_field;

        if ($total_count <= $count)
        {
            $list = $this -> limit(0) -> select();
            $callback($list, $total_count, 0);

            return;
        }

        $i = 0;
        while ($i < $total_count)
        {
            $list = $this -> limit($i, $count) -> select();
            $callback($list, $total_count, $i);
            $i += $count;
        }
    }
    
	/**
     * 重置
     */
	public function reset()
	{
        $this -> param  = [];
        $this -> paramIndex = 0;
        $this -> field  = '*';
        $this -> join   = '';
        $this -> where  = '';
        $this -> group  = '';
        $this -> having = '';
        $this -> order  = '';
        $this -> limit  = '';
        $this -> db_key = 'default';
        $this -> json   = false;
        
        return $this;
    }

	/**
     * 参数
     */
	public function param($key, $val)
	{
        $this -> param[$key] = $val;

        return $this;
    }
    
	/**
     * 设置参数
     */
	protected function setParam($val)
	{
        $key = 'p' . $this -> paramIndex++;
        $this -> param[$key] = $val;

        return ':' . $key;
    }
    
	/**
     * 字段
     */
	public function field($field = '*')
	{
        if (is_array($field))
        {
            $field = join(',', $field);
        }

        $this -> field = $field;

        return $this;
    }
    
	/**
     * JOIN
     */
	public function join($join)
	{
        if (empty($this -> join))
        {
            $this -> join = $join;
        }
        else
        {
            $this -> join .= PHP_EOL . $join;
        }

        return $this;
    }
    
	/**
     * WHERE
     */
	public function whereIf($bo, $field, $op = null, $val = null)
	{
        if (empty($bo))
        {
            return $this;
        }

        return $this -> where($field, $op, $val);
    }

	/**
     * WHERE
     */
	public function whereOr($field, $op = null, $val = null)
	{
        return $this -> where($field, $op, $val, 'OR');
    }

	/**
     * WHERE
     */
	public function where($field, $op = null, $val = null, $link = 'AND')
	{
        $where = '';
        if ($op === null && $val === null)
        {
            if (is_numeric($field))
            {
                $where = 'id = ' . $field;
            }
            else
            {
                $where = $field;
            }
        }
        else if ($val === null)
        {
            $p = $this -> setParam($op);
            $where = $field . ' = ' . $p;
        }
        else
        {
            $op = strtoupper($op);
            if (in_array($op, ['IN', 'NOT IN']))
            {
                if (is_array($val))
                {
                    if (count($val) > 0)
                    {
                        $where = $field . ' ' . $op . ' (\'' . join('\',\'', $val) . '\')';
                    }
                }
                else
                {
                    $where = $field . ' ' . $op . ' (' . $val . ')';
                }
            }
            else if (in_array($op, ['BETWEEN', 'NOT BETWEEN']))
            {
                if (is_array($val) && count($val) == 2)
                {
                    $p1 = $this -> setParam($val[0]);
                    $p2 = $this -> setParam($val[1]);
                    $where = $field . ' ' . $op . ' ' . $p1 . ' AND ' . $p2;
                }
            }
            else
            {
                if (strpos($val, '::') === 0)
                {
                    $where = $field . ' ' . $op . ' ' . substr($val, 2);
                }
                else
                {
                    $p = $this -> setParam($val);
                    $where = $field . ' ' . $op . ' ' . $p;
                }
            }
        }

        if ($where !== '')
        {
            if ($this -> where === '')
            {
                $this -> where = $where;
            }
            else
            {
                $this -> where .= ' ' . $link . ' ' . $where;
            }
        }

        return $this;
    }
    
	/**
     * Order by
     */
	public function order($order)
	{
        $this -> order = $order;

        return $this;
    }
    
	/**
     * group by
     */
	public function group($group)
	{
        $this -> group = $group;

        return $this;
    }
    
	/**
     * having
     */
	public function having($having)
	{
        $this -> having = $having;

        return $this;
    }
    
	/**
     * limit
     */
	public function limit($begin, $end = 0)
	{
        if ($end === 0)
        {
            $this -> limit = $begin;
        }
        else
        {
            $this -> limit = $begin . ',' . $end;
        }

        return $this;
    }
    
	/**
     * 创建查询SQL
     */
    public function buildSelectSql()
    {
        $sql = 'SELECT ' . $this -> field . PHP_EOL . 'FROM ' . $this -> table . PHP_EOL;
        if (!empty($this -> join))
        {
            $sql .= $this -> join . PHP_EOL;
        }
        if (!empty($this -> where))
        {
            $sql .= 'WHERE ' . $this -> where . PHP_EOL;
        }
        if (!empty($this -> group))
        {
            $sql .= 'GROUP BY ' . $this -> group . PHP_EOL;
        }
        if (!empty($this -> having))
        {
            $sql .= 'HAVING ' . $this -> having . PHP_EOL;
        }
        if (!empty($this -> order))
        {
            $sql .= 'ORDER BY ' . $this -> order . PHP_EOL;
        }
        if (!empty($this -> limit))
        {
            $sql .= 'LIMIT ' . $this -> limit . PHP_EOL;
        }

        return $sql;
    }

	/**
     * 插入
     */
	public function insert($data, $replace = false)
	{
        if (!is_array($data))
        {
            throw new \Exception('DB insert param not array!');
        }
        
        if (empty($data))
        {
            throw new \Exception('DB insert param not find!');
        }

        $field_list = [];
        $value_list = [];
        foreach ($data as $field => $val)
        {
            $field = trim($field);
            // 将array转为JSON字符串
            if (\substr($field, -5) == '_json')
            {
                if (is_array($val))
                {
                    $val = json_encode($val);
                }
            }

            $field_list[] = $field;
            if (strpos($val, '::') === 0)
            {
                $value_list[] = substr($val, 2);
            }
            else
            {
                $value_list[] = $this -> setParam($val);
            }
        }

        $sql = $replace ? 'REPLACE INTO ' : 'INSERT INTO ';
        $sql .= $this -> table . ' (' . join(',', $field_list) . ')' . PHP_EOL;
        $sql .= 'VALUE(' . join(',', $value_list) . ')';

        $res = $this -> call_sql($sql, $this -> param);

        return $res;
    }
    
	/**
     * 批量插入
     */
	public function insertAll($list)
	{
        if (!is_array($list)) return 0;
        if (count($list) == 0) return 0;

        // 获取字段
		$insert_fields = array_keys($list[0]);
		
		$chunk_list = array_chunk($list, 500);
		foreach ($chunk_list as $patch)
		{
            $insert_vals = [];
            
			foreach ($patch as $r)
			{
                $val_sql = '(';
                foreach ($r as $rr)
                {
                    $val_sql .= "'" . addslashes($rr) . "',";
                }
                $val_sql = trim($val_sql, ',');
                $val_sql .= ')';

                $insert_vals[] = $val_sql;
            }

            $fields = '`' . implode('`, `', $insert_fields) . '`';
            $vals = implode(', ', $insert_vals);

            $table = $this -> table;

            $sql = "INSERT INTO `$table` ($fields) VALUES $vals";
            
            $this -> call_sql($sql);
        }

        return count($list);
    }
    
	/**
     * 更新
     */
	public function update($data, $val = null)
	{
        if (is_string($data))
        {
            $data = [$data => $val];
        }

        if (!is_array($data))
        {
            throw new \Exception('DB update param not array!');
        }

        $set_list = [];
        foreach ($data as $field => $val)
        {
            $field = trim($field);
            if (\strtolower($field) == 'id' && empty($this -> where))
            {
                $this -> where = 'id = ' . $val;
                continue;
            }

            $value = '';
            if (strpos($val, '::') === 0)
            {
                $value = substr($val, 2);
            }
            else
            {
                $value = $this -> setParam($val);
            }
            
            $set_list[] = $field . '=' . $value;
        }

        if (empty($set_list))
        {
            throw new \Exception('DB update field not find!');
        }

        $sql = 'UPDATE ' . $this -> table . PHP_EOL;
        $sql .= 'SET ' . join(',', $set_list) . PHP_EOL;
        if (!empty($this -> where))
        {
            $sql .= 'WHERE ' . $this -> where . PHP_EOL;
        }
        if (!empty($this -> order))
        {
            $sql .= 'ORDER BY ' . $this -> order . PHP_EOL;
        }
        if (!empty($this -> limit))
        {
            $sql .= 'LIMIT ' . $this -> limit . PHP_EOL;
        }

        $res = $this -> call_sql($sql, $this -> param);

        return $res;
    }
    
	/**
     * 批量更新
     */
	public function updateAll($data)
	{
        /*
        UPDATE categories SET
        display_order = CASE id
            WHEN 1 THEN 3
            WHEN 2 THEN 4
            WHEN 3 THEN 5
        END,
        title = CASE id
            WHEN 1 THEN 'New Title 1'
            WHEN 2 THEN 'New Title 2'
            WHEN 3 THEN 'New Title 3'
        END
        WHERE id IN (1,2,3)
        */

        if (empty($data))
        {
            return 0;
        }

        $key_list = array_keys($data[0]);
        if (!\in_array('id', $key_list))
        {
            throw new \Exception('need field [id]');
        }

        $chunk_list = array_chunk($data, 100);
        foreach ($chunk_list as $list)
        {
            $id_list = array_column($list, 'id');
    
            $sql = 'UPDATE ' . $this -> table . ' SET' . PHP_EOL;
            foreach ($key_list as $key)
            {
                if ($key == 'id')
                {
                    continue;
                }
                $sql .= $key . ' = CASE id' . PHP_EOL;
                foreach ($list as $r)
                {
                    $val = addslashes($r[$key]);
                    $sql .= 'WHEN ' . $r['id'] . " THEN '$val'" . PHP_EOL;
                }
                $sql .= 'END,';
            }
    
            $sql = trim($sql, ',') . PHP_EOL;
            $sql .= 'WHERE id IN (' . \implode(',', $id_list) . ')';
    
            $this -> call_sql($sql);
        }

        return count($data);
    }

	/**
     * 删除
     */
	public function delete($id = null)
	{
        if ($id !== null)
        {
            if (\is_numeric($id))
            {
                $this -> where($id);
            }
            else if (is_array($id))
            {
                $this -> where('id', 'in', $id);
            }
        }

        $sql = 'DELETE FROM ' . $this -> table . PHP_EOL;
        if (!empty($this -> where))
        {
            $sql .= 'WHERE ' . $this -> where . PHP_EOL;
        }
        if (!empty($this -> order))
        {
            $sql .= 'ORDER BY ' . $this -> order . PHP_EOL;
        }
        if (!empty($this -> limit))
        {
            $sql .= 'LIMIT ' . $this -> limit . PHP_EOL;
        }

        $res = $this -> call_sql($sql, $this -> param);

        return $res;
    }
    
	/**
     * 清空表
     */
	public function truncate()
	{
        $sql = 'TRUNCATE TABLE ' . $this -> table;

        $res = $this -> call_sql($sql);

        return $res;
    }

	/**
     * 个数
     */
    public function count()
	{
        $this -> field('COUNT(1) AS val');
        $res = $this -> find();

        return $res['val'];
    }

	/**
     * 创建表
     */
    public function createTable($new_table)
    {
        $sql = 'SHOW CREATE TABLE `' . $this -> table . '`';

        $res = $this -> call_sql($sql);

        if (empty($res))
        {
            return false;
        }

        $create_sql = $res[0]['create table'];

        $create_sql = str_replace('CREATE TABLE `' . $res[0]['table'] . '`', 'CREATE TABLE IF NOT EXISTS `' . $new_table . '`', $create_sql);

        $this -> call_sql($create_sql);

        return true;
    }

	/**
     * 分页查询
     */
	public function selectPage($page = 1, $page_size = 30)
	{
        $field = $this -> field;
        $count = $this -> count();

        $list = $this 
            -> field($field)
            -> limit(($page - 1) * $page_size, $page_size) 
            -> select();

        return [$count, $list];
    }
}