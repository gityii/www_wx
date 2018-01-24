<?php
class Db
{
	private $link;
	static private $_instance;
	// 连接数据库
	private function __construct($host, $username, $password)
	{
		$this->link = mysqli_connect($host, $username, $password);
		$this->query("SET NAMES 'utf8'");
		return $this->link;
	}

	private function __clone(){}

	public static function getMySQLconnect($host, $username, $password)
	{
		if( FALSE == (self::$_instance instanceof self) )
		{
			self::$_instance = new self($host, $username, $password);
		}
		return self::$_instance;
    }

	// 连接数据表
	public function selectDb($database)
	{
		$this->result = mysqli_select_db($this->link, $database);
		return $this->result;
	}

	// 执行SQL语句
	public function query($query)
	{
		return $this->result = mysqli_query($this->link, $query);
	}

	// 将结果集保存为数组
	public function fetch_array($fetch_array)
	{
		return $this->result = mysqli_fetch_array($fetch_array, MYSQLI_BOTH);
	}

	// 关闭数据库连接
	public function close()
	{
		return $this->result = mysqli_close($this->link);
	}

}
?>
