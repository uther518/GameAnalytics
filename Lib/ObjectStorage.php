<?php

/**
 * 对象模块仓库
 * @author	Lucky
 */
class ObjectStorage
{
	/**
	 * 对象集
	 * @var	array
	 */
	protected static $objects = array();
	
	/**
	 * 任务对象集
	 * @var	array
	 */
	protected static $sqlCollect = array();
	
	/**
	 * 注册需要保存的对象
	 * @param	aBaseModel $obj	
	 * @return	object
	 */
	public static function register( Data_Abstract & $obj )
	{
		if( !isset( self::$objects[$obj->getUserId()][$obj->getCacheKey()] ) )
		{
			self::$objects[$obj->getUserId()][$obj->getCacheKey()] = $obj;
		}
	}
	
	/**
	 * SQL语句合并
	 * @param unknown $userId
	 * @param unknown $action add/update/delete操作
	 * @param unknown $table  数据库
	 * @param unknown $cond   条件，如delete from tb where id = xx
	 * @param unknown $sql
	 */
	public static function registerSql( $userId , $action , $table , $cond , $sql )
	{
		$index = $action."_".$table."_".$cond;
		self::$sqlCollect[$userId][$index] = $sql;
	}
	

	/**
	 * 保存所有对象
	 * @return	boolean
	 */
	public static function save()
	{
		foreach( self::$objects as $userId => $objects )
		{
		
			foreach ( $objects as $obj )
			{
				$obj->makeSqlCollect();
			}

			if( self::$sqlCollect[$userId] )
			{
				$sqls = array_values(  self::$sqlCollect[$userId] );
				$queryResult = MysqlPool::getInstance( $userId )->query( $sqls );
			}
		
			
			if( $queryResult == false && !empty( $sqls ) )
			{
				
			
				$errorLog = new ErrorLog( "sqlError" );
				$msg = json_encode( $sqls );
				$errorLog->addLog( $msg );
				
				throw new GameException( GameException::DB_SQL_ERROR );
			}
			else 
			{
		
				foreach( $objects as $obj )
				{
					$obj->save();
				}
				
			}
			unset( self::$sqlCollect[$userId] );
		}
		
		self::$objects = array();
	}
	
	
	
}

?>
