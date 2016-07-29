<?php
class Dber
{
	protected $uId;
	
	protected $db;
	
	/**
	 * 获取数据库单例
	 * @param	int $uId	用户ID
	 * @return	iDber
	 */
	public static function & getInstance( $uId )
	{
		static $dbObject = array();
		if( empty( $dbObject[$uId] ) )
		{
			$dbObject[$uId] = new Dber( $uId );
		}
		
		return $dbObject[$uId];
	}
	
	/**
	 * 生成FM_DB key
	 * @param	string $tableName		数据表名
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	string
	 */
	private function _makeKey( $tableName , $condition = array() )
	{
		$conditionStr = "{$this->uId}#{$tableName}";
		if( $condition )
		{
			foreach ( $condition as $key => $value )
			{
				$conditionStr .= "#{$key}#{$value}";
			}
		
		}
		return $conditionStr;
	}
	
	/**
	 * 实例化(不容许外部调用)
	 * @param	int $uId	用户ID
	 */
	protected function __construct( $uId )
	{
		$dbConfig = & Common::getConfig( 'midWareDb' );
		$this->db = new fm_dbmidware( $dbConfig['host'] , $dbConfig['port'] );
		$this->uId = $uId;
	}
	
	/**
	 * 数据新增接口
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function add( $tableName , $value , $condition = array() )
	{
		if( $condition )
		{
			$value['id'] = $condition['id'];
		}
		
		$result = $this->db->add( $this->uId , $this->_makeKey( $tableName , $condition ) , $this->encode( $value ) );
		if( $result )
		{
			return true;
		}
		else
		{
//			@file_put_contents( '/tmp/mid_db_err.log' , "add|{$this->uId}|{$tableName}" , FILE_APPEND );
			return false;

		}
	}
	
	/**
	 * 数据修改接口
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function update( $tableName , $value , $condition = array() )
	{
		if( $condition )
		{
			$value['id'] = $condition['id'];
		}
		$result = $this->db->update( $this->uId , $this->_makeKey( $tableName , $condition ) , $this->encode( $value ) );
		if( $result )
		{
			return true;
		}
		else
		{
//			@file_put_contents( '/tmp/mid_db_err.log' , "update|{$this->uId}|{$tableName}" , FILE_APPEND );
			return false;
		}
	}
	
	/**
	 * 数据删除接口
	 * @param	string $tableName		数据表名
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function delete( $tableName , $condition = array() )
	{
		$result = $this->db->del( $this->uId , $this->_makeKey( $tableName , $condition ) );
		if( $result )
		{
			return true;
		}
		else
		{
//			@file_put_contents( '/tmp/mid_db_err.log' , "delete|{$this->uId}|{$tableName}" , FILE_APPEND );
			return false;
		}
	}
	
	/**
	 * 数据单项查询接口(只能根据用户ID查询)
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @return	array
	 */
	public function find( $tableName , $returnItems )
	{
		$result = $this->db->find( $this->uId , $this->_makeKey( $tableName )  , $this->encodeReturnItems( $returnItems ) );
		if( $result )
		{
			return json_decode( $result , TRUE );
		}
		else
		{
//			@file_put_contents( '/tmp/mid_db_err.log' , "find|{$this->uId}|{$tableName}" , FILE_APPEND );
			throw new GameException( 7 );
		}
	}
	
	/**
	 * 数据多项查询接口
	 *
	 * @param	string $tableName		数据表名
	 * @param	array $returnItems		需要的字段
	 * @return	array
	 */
	public function findAll( $tableName , $returnItems )
	{
		$returnItems[] = 'id';
		$result = $this->db->find_all( $this->uId , $this->_makeKey( $tableName ) , "id" , $this->encodeReturnItems( $returnItems ) );
		if( $result )
		{
			$info = json_decode( $result , TRUE );
			return array_values( $info );
		}
		else
		{
//			@file_put_contents( '/tmp/mid_db_err.log' , "findAll|{$this->uId}|{$tableName}" , FILE_APPEND );
			throw new GameException( 7 );
		}
	}
	
	/**
	 * 全局数据ID获取接口
	 *
	 * @param	string $tableName		数据表名
	 * @return	int
	 */
	public function getID( $tableName )
	{
		return $this->db->get_id( $this->uId , $tableName );
	}
	
	/**
	 * 生成返回项
	 * @return	string
	 */
	private function encodeReturnItems( $info )
	{
		return "{\"" . implode( "\",\"" , $info ) . "\"}";
	}
	
	/**
	 * 生成字符串键值
	 * @return	string
	 */
	private function encode( $info )
	{
		$json = array();		
		foreach( $info as $key => $value )
		{
			if( is_numeric( $value ) )
				$json[] = "\"{$key}\":{$value}";
			else
				$json[] = "\"{$key}\":\"{$value}\"";
		}
		
		$strJSON = '{' . implode( "," , $json ) . '}'; 
		if( DEBUG )
		{
			//file_put_contents( '/tmp/dber_encoded_json' , $strJSON ."\n" , FILE_APPEND );
		}
		
		return $strJSON;
	}
}
?>
