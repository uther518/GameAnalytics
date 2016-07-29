<?php
class MysqlPool
{
	/**
	 * 链接游戏库的引擎
	 * @var	MysqlDb
	 */
	protected $dbEngine;
	
	/**
	 * 用户ID
	 * @var	int
	 */
	protected $userId;
	
	/**
	 * 所有游戏库的配置
	 * @var	array
	 */
	protected static $dbConfigs = null;
	
	/**
	 * 索引数据库引擎
	 * @var	MysqlDb
	 */
	protected static $indexDbEngine = null;
	
	/**
	 * 游戏数据库引擎
	 * @var	MysqlDb[]
	 */
	protected static $dbEngines = array();
	
	/**
	 * 获取数据库单例
	 * @param	int $userId	用户ID
	 * @return	iDber
	 */
	public static function & getInstance( $userId )
	{
		static $dbObject = array();
		if( !isset( $dbObject[$userId] ) )
		{
			$dbObject[$userId] = new MysqlPool( $userId );
		}
		
		return $dbObject[$userId];
	}
	
	/**
	 * 实例化数据库类
	 * @param	int	$userId	用户ID
	 */
	protected function __construct( $userId )
	{
		$dbConfig = & Common::getConfig( 'mysqlPool' );
		$this->userId = (integer)$userId;
		//$dbId =  $this->getUserDbId();		
		$dbId =  1;
		$this->dbEngine = $dbConfig['game'][$dbId];		
	}
	
	
	
	/**
	 * 获取用户所在数据库ID
	 * @return	integer
	 */
	protected function getUserDbId()
	{
		$indexCache = & Common::getCache( 'index' );
		$dbId = $indexCache->get( $this->userId .'_gamedbId' );
	
		if( $dbId === false )
		{
			$result = self::getIndexDbEngine()->fetchOneAssoc( 'SELECT `db_id` AS `dbId` FROM `index_0` WHERE `userid` = '. $this->userId );
			if( empty( $result ) )
			{
				$dbId = $this->allocateDbForUser();

			}
			else
			{
				$dbId = $result['dbId'];
			}
			
			$indexCache->set( $this->userId .'_gamedbId' , $dbId );
		}
		return $dbId;
	}
	
	/**
	 * 分配一个Db给用户
	 * @return	integer
	 */
	protected function allocateDbForUser()
	{
		$canUseDbConfigs = self::getCanUseDbConfig();
		$dbId = array_rand( $canUseDbConfigs );
		$sql =  "INSERT INTO `index_0` ( `userid` , `db_id` ) VALUES ( {$this->userId} , {$dbId} ) " ;
		if( self::getIndexDbEngine()->query( $sql ) == false )
		{
			throw new Exception( "The new user could not create index data.\n" , 6 );
		}
		return $dbId;
	}
	
	/**
	 * 获取可使用的数据库
	 * @return	array
	 */
	protected static function getCanUseDbConfig()
	{
		$canUseDbConfigs = self::getDbConfigs( true );

		if( empty( $canUseDbConfigs ) )
		{
			$canUseDbConfigs = self::getDbConfigs();
		}
		
		if( empty( $canUseDbConfigs ) )
		{
			throw new Exception( "Not have database can use.\n" , 6 );
		}
		
		return $canUseDbConfigs;
	}
	
	/**
	 * 获取所有数据库配置
	 * @param	boolean $isOnlyNotFull	是否只返回未满的数据库
	 */
	protected static function getDbConfigs( $isOnlyNotFull = false )
	{
		if( self::$dbConfigs === null )
		{
			$dbConfigs = Common::getCache( 'index' )->get( 'gameDBConfigs' );
			if( $dbConfigs == false )
			{
			
				$dbConfigs = self::getDbConfigFromDb();
				
				
				Common::getCache( 'index' )->set( 'gameDBConfigs' , $dbConfigs , 3600 );
			}
			
			self::$dbConfigs = $dbConfigs;
		}
			
		if( !$isOnlyNotFull )
		{
			return self::$dbConfigs;
		}
		
		return self::filterFullDb();
	}
	
	/**
	 * 从数据库中获取数据库配置
	 * @return	array
	 */
	protected static function getDbConfigFromDb()
	{
		$dbConfigs = array();
		$result = self::getIndexDbEngine()->fetchArray( 'SELECT `id` AS `dbId` , `is_full` AS `isFull` , `master_ip` AS `host` , `master_port` AS `port` , `username` AS `user` , `pwd` AS `passwd` , `db_name` AS `name` FROM `db_config`' );
		
		if( empty( $result ) )
		{
			throw new Exception( "Not have database can use.\n" , 6 );
		}
		
		foreach( $result as $dbConfig )
		{
			$dbConfigs[$dbConfig['dbId']] = $dbConfig;
		}
		return $dbConfigs;
	}

	/**
	 * 过滤已经满数据库
	 * @return	array
	 */
	protected static function filterFullDb()
	{
		$notFullDbConfigs = array();
		foreach( self::$dbConfigs as $dbId => $dbConfig )
		{
			if( !$dbConfig['isFull'] )
			{
				$notFullDbConfigs[$dbId] = $dbConfig;
			}
		}
		return $notFullDbConfigs;
	}
	
	/**
	 * 获取索引数据库引擎
	 * @return	MysqlDb
	 */
	protected static function getIndexDbEngine()
	{
		if( self::$indexDbEngine === null )
		{
			$dbConfig = & Common::getConfig( 'mysqlPool' );
			self::$indexDbEngine = $dbConfig['dbIndex'];
		}
		return self::$indexDbEngine;
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
		$value = $value + $condition;
		$value['uid'] = $this->userId;
		$keys = array_keys( $value );
		$sql = "INSERT INTO `{$tableName}` (`" . implode( "` , `" , $keys ) . "`) VALUES (\"" . implode( '" , "' , $value ) . "\")";
		
		
		$conditionStr = implode( "_", $condition );
		ObjectStorage::registerSql( $this->userId , "insert" , $tableName ,  $conditionStr  , $sql );
	
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
		$sql = "UPDATE `{$tableName}` SET ";
		foreach ( $value as $key => $item )
		{
			$sql .= "`{$key}` = '{$item}',";
		}
		
		$sql .= "`uid` = {$this->userId} WHERE `uid` = {$this->userId}";
	
		foreach ( $condition as $key => $item )
		{
			$sql .= " AND `{$key}` = '{$item}'";   
		}

		$conditionStr = implode( "_", $condition );
		ObjectStorage::registerSql( $this->userId , "update" , $tableName , $conditionStr , $sql );
	}
	
	/**
	 * 数据删除接口
	 * @param	string $tableName		数据表名
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function delete( $tableName , $condition = array() )
	{
		$sql = "DELETE FROM `{$tableName}` WHERE `uid` = {$this->userId}";
		foreach ( $condition as $key => $item )
		{
			$sql .= " AND `{$key}` = '{$item}'";   
		}
		$conditionStr = implode( "_", $condition );
		
		ObjectStorage::registerSql( $this->userId , "delete" , $tableName , $conditionStr ,  $sql  );
	
	}
	
	/**
	 * 数据单项查询接口(只能根据用户ID查询)
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @return	array
	 */
	public function find( $tableName )
	{
		$sql = "SELECT * FROM `{$tableName}` WHERE `uid` = {$this->userId} LIMIT 1";
		$result = $this->findQuery( $sql );
		return $result[0];
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
		$sql = "SELECT * FROM `{$tableName}` WHERE `uid` = {$this->userId}";
		$result = $this->findQuery( $sql );
		return $result;
	}
	

	public function query( $rawsqls , $cgiServer = null )
	{
		$fastcgiServer = $cgiServer ? $cgiServer : $this->dbEngine;	
		if(count($rawsqls) == 0)
			return true;
		
		$strsqls = $this->sqlAssemble( $rawsqls );
		$url = "$fastcgiServer?mod=execsql&act=direct&sqlnum=".count($rawsqls);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Expect:'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $strsqls);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		
		
		return ($result !== 'Error');
	}
	
	

	public function findQuery( $rawsql , $cgiServer = null )
	{
		$fastcgiServer = $cgiServer ? $cgiServer : $this->dbEngine;
		$strsql = "sql=".urlencode($rawsql);
	
		$url = "$fastcgiServer?mod=querysql&act=direct";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Expect:'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $strsql);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		if( $result === false )
			return array();
	
		
		if( $result !== 'NULL' && $result !== 'Error' )
		{
			$result = $this->sqlretParse($result);
		}
		else 
		{
			$result = array();
		}
	
		return $result;
	}
	
	
	public function sqlAssemble($rawsqls)
	{
		$strsqls = array();
		for($i=0;$i<count($rawsqls);$i++)
		{
			$strsqls[] = "sql$i=".urlencode($rawsqls[$i]);
		}
	
		return implode("&",$strsqls);
	}
	
	
	public  function sqlretParse($sqlret)
	{
		$result = array();
		$lines = explode("\n",$sqlret);
		foreach($lines as $line)
		{
			$columns = explode("\t",$line);
			$result[] = $columns;
		}
	
		return $result;
	}
	
	
	
	/**
	 * 全局数据ID获取接口
	 *
	 * @param	string $tableName		数据表名
	 * @return	int
	 */
//	public function getID( $tableName )
//	{
//		return $this->dbEngine->get_id( $tableName );
//	}
	
	/**
	 * 对所有游戏库执行语句
	 * @param	string $sql	SQL语句
	 */
	public static function fetchAllDatabase( $sql )
	{
		self::initAllDbEngine();
		$result = array();
		foreach( self::$dbEngines as $dbId => $dbEngine )
		{
			$result[$dbId] = $dbEngine->fetchArray( $sql );	
		}
		return $result;
	}
	/**
	 * 对单个库执行语句
	 * @param string $sql
	 * @param int $id
	 */
	public static function fetchOneDatabase( $sql , $id )
	{
		self::initAllDbEngine( $id );
		$result = array();
		foreach( self::$dbEngines as $dbId => $dbEngine )
		{
			if( $id > 0  && $dbId == $id )
			{
				$result = $dbEngine->fetchArray( $sql );
			}
		}
		return $result;
	}
	
	/**
	 * 初始化所有数据库
	 */
	protected static function initAllDbEngine( $id = 0 )
	{
		$dbConfigs = self::getDbConfigs();
		foreach( $dbConfigs as $dbId => $dbConfig )
		{
			if( !isset( self::$dbEngines[$dbId] ) )
			{
				if( ( $id > 0 && $dbId == $id )   ||  $id == 0 )
				{
					self::$dbEngines[$dbId] = new MysqlDb( $dbConfig );
				}
			}
		}
	}
	
	
	
	
	
	
}
