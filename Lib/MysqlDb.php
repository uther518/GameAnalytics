<?php
class MysqlDb
{
	protected $db;

	public function __construct( $config )
	{

		if( ( $this->db = mysql_connect( "{$config['host']}:{$config['port']}" , $config['user'] , $config['passwd'] , true , MYSQL_CLIENT_INTERACTIVE ) ) != null )
		{
			mysql_query( "SET NAMES 'utf8'" );
			if( isset( $config['name'] ) )
			{
				return mysql_select_db( $config['name'], $this->db );
			}
			
			return $this->db;
		}
		else
		{
			return false;
		}
		
	}
	
	public function selectDb( $name )
	{
		mysql_select_db( $name, $this->db );
	}	
	
	public function query($sql)
	{
		return mysql_query($sql,$this->db);
	}

	public function fetchArray($sql)
	{
		$result = $this->query($sql);
		return $this->res2Assoc($result);
	}

	public function fetchAssoc($result)
	{
		if( $result == false )
		{
			return false;
		}
		return mysql_fetch_assoc($result);
	}

	public function fetchOneAssoc($query)
	{
		$result = $this->query($query);
		return $this->fetchAssoc($result);
	}

	public function fetchObject($result)
	{
		return mysql_fetch_object($result);
	}

	public function affectedRows()
	{
		return mysql_affected_rows($this->db);
	}

	public function insertId()
	{
		return mysql_insert_id($this->db);
	}

	public function getCount($tables, $condition = "")
	{
		$r = $this->fetchOneAssoc("select count(*) as count` from $tables " . ( $condition ? " where $condition" : ""));
		return $r['count'];
	}

	public function & res2Assoc(& $res)
	{
		$rows = array();
		while( ( $row = $this->fetchAssoc($res) ) != null)
		{
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function startTransaction()
	{
		mysql_query( "SET AUTOCOMMIT=0" , $this->db );
		mysql_query( "START TRANSACTION" , $this->db );
	}
	
	public function commitTransaction()
	{
		mysql_query( "COMMIT" );
		mysql_query( "SET AUTOCOMMIT=1" , $this->db );
	}

	public function rollbackTransaction()
	{
		mysql_query( "ROLLBACK" , $this->db );
	}

	/**
	 * 获取错误号码
	 *
	 * @return integer
	 */
	public function getErrorNumber()
	{
		return mysql_errno( $this->db );
	}
	
	/**
	 * 获取错误消息
	 *
	 * @return string
	 */
	public function getErrorMessage()
	{
		return mysql_error( $this->db );
	}
}
