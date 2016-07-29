<?php 
/**
 * 通用功能类
 */
class Common
{
	/**
	 * 普通数据的数据库引擎
	 * @var array(
	 * 			{dbKey}:array(
	 * 				{dbId}:MysqlDb
	 * 			)
	 * 		)
	 */
	private static $_dbNormalEngine = array();
	
	/**
	 * 解除转义
	 * @param	mixed $var
	 * @return	mixed
	 */
	public static function prepareGPCData( & $var )
	{
		if( is_array( $var ) )
		{
			while( ( list( $key , $val ) = each( $var ) ) != null )
			{
				$var[$key] = self::prepareGPCData( $val );
			}
		}
		else 
		{
			$var = stripslashes( $var );
		}
		
		return $var;
	}
	
	/**
	 * 获取系统配置信息
	 * @param	string $key		配置文件项
	 * @return	array
	 */
	public static function & getConfig( $key = '' )
	{
		static $config = array();
		if( !$config )
		{
			$sysConfig = self::getSysConfig();
			$config = self::getConfigFile( $sysConfig );
			$config = array_merge_recursive( $config , self::getConfigFile( "GameConfig" ) );
		}

		if( !isset( $config[$key] ) )
		{
			$config[$key] = self::getConfigFile( $key );
		}
		
		if( $key )		
			return $config[$key];
		
		return $config;
	}
	
	
	public static function getSysConfig()
	{
		$sysConfig = "SystemConfig";
		return $sysConfig;
	}
	
	/**
	 * 设置系统配置
	 * @param	string $key		配置文件项
	 * @param	mixed $value	配置值
	 */
	public static function setConfig( $key , $value )
	{
		$content = "<?php\n";
		$content .= "return ". var_export( $value , true ) .";\n";
		$content .= "?>";
		$fp = fopen( CONFIG_DIR . "/{$key}.php" , "w" );
		fwrite( $fp , $content );
		fclose( $fp );
	}
	
	/**
	 * 获取Cache实例
	 * @param	string $param	Cache服务器名称
	 * @return	iCache
	 */
	public static function & getCache( $param = 'data' )
	{
		static $cache = array();
		if( empty( $cache[$param] ) )
		{
			$config = self::getConfig( 'memcache' );
			if( empty( $config[$param] ) )
			{
				$cache[$param] = false;
				
				//系统错误,缺少必要的Memcache配置
				return false;
			}
			else
			{
				$memcacheClass = self::getConfig( 'memcacheClass' );
				$cache[$param] = new $memcacheClass( $config[$param] );
			}
		}
		
		return $cache[$param];
	}
	
	/**
	 * 获取DB实例
	 * @param	string $dbName	DB名称
	 * @return	iDber
	 */
	public static function & getDB( $uId )
	{
		$dbClassName = Common::getConfig( 'dbClassName' );
		$uId = ltrim( $uId , '0' );
		switch ( $dbClassName )
		{
			case 'MysqlPool':
			
				return MysqlPool::getInstance( $uId );
			case 'MysqlDber':
				return MysqlDber::getInstance( $uId );
			case 'Dber':
				return Dber::getInstance( $uId );
		}
		throw new GameException( 101 );
	}
	
	/**
	 * 获取框架客户端
	 * @param	string $serverName	服务器名称
	 * @return	RestClient
	 */
	public static function & getClient( $serverName = 'frameServer' )
	{
		static $client = array();
		if( empty( $client[$serverName] ) )
		{
			$config = Common::getConfig( $serverName );
			$client[$serverName] = new RestClient( $config['url'] , $config['protocalType'] , $config['timeout'] );
		}
		return $client[$serverName];
	}

	
	/**
	 * 计算最小的不重复值
	 * @param	array $ids			数字
	 * @param	int $min			最小值
	 * @return	int
	 */
	public static function computeMinUnique( $ids , $min = 1 )
	{
		array_multisort( $ids , SORT_ASC );
		foreach ( $ids as $item )
		{
			if( $min == $item )
			{
				$min++;
			}
		}
		return $min;
	}
	
	
	
	protected static function & getConfigFile( $configKey )
	{
		$config = array();
		if( file_exists( CONFIG_DIR . "/OperationData/{$configKey}.php" ) )
		{
			$config = require( CONFIG_DIR . "/OperationData/{$configKey}.php" );
		}
		else if( file_exists( CONFIG_DIR . "/FollowVersion/{$configKey}.php" ) )
		{
			$config = require( CONFIG_DIR . "/FollowVersion/{$configKey}.php" );
		}
		else if( file_exists( CONFIG_DIR . "/SystemConfig/{$configKey}.php" ) )
		{
			$config = require( CONFIG_DIR . "/SystemConfig/{$configKey}.php" );
		}
		
		return $config;
	}
	
	/**
	 * 获取普通数据的数据库引擎
	 * @param string $dbKey	数据库的键
	 * @return	MysqlDb
	 */
	public static function & getNormalDatabaseEngine( $dbKey )
	{
		if( !isset( self::$_dbNormalEngine[$dbKey] ) )
		{
			$dbConfig = Common::getConfig( 'mysqlDb' );
			self::$_dbNormalEngine[$dbKey] = new MysqlDb( $dbConfig[$dbKey] );
		}
		return self::$_dbNormalEngine[$dbKey]; 
	}
	
	
	
	public static function _getDbEngine( $userId )
	{
		$result['dbId'] = 1;
		$dbConfig = Common::getConfig( "mysqlDb" );
		
		static $dbEngines = array();
		if( !isset( $dbEngines[$result['dbId']] ) )
		{
			$dbEngines[$result['dbId']] = new MysqlDb( 
					array(
							'host' => $dbConfig['game']['host'],
							'port' => $dbConfig['game']['port'],
							'user' =>  $dbConfig['game']['user'],
							'passwd' =>  $dbConfig['game']['passwd'],
							'name' =>  $dbConfig['game']['name'],
							)
				);
		}
		return $dbEngines[$result['dbId']];
	}
	
}


function output($response)
{
	header('Cache-Control: no-cache, must-revalidate');
	header("Content-Type: text/plain; charset=utf-8");
	ob_start('ob_gzip');
	echo $response;
	ob_end_flush();
}



//这是ob_gzip压缩机
function ob_gzip($content)
{
	if( !headers_sent() &&
			extension_loaded("zlib") &&
			isset($_SERVER["HTTP_ACCEPT_ENCODING"]) &&
			strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip"))
	{
		$content = gzencode($content."",9);

		header("Content-Encoding: gzip");
		header("Vary: Accept-Encoding");
		header("Content-Length: ".strlen($content));
	}
	return $content;
}


function Lang( $key )
{
	$confName = 'LangCn';
	if( $_SESSION['lang'] == 'en' )
	{
		$confName = 'LangEn';
	}

	$config = Common::getConfig( $confName );
	if( $config[$key] )
	{
		echo $config[$key];
	}
	else 
	{
		echo $key;
	}
}

/**
 * 自动加载类文件
 * @param string $classname
 */
function __autoload( $classname ) 
{
	$classname = str_replace( '_' , '/' , $classname );

	//在模块文件夹搜索
	if( file_exists( LIB_DIR . "/{$classname}.php" ) )
	{
		include_once( LIB_DIR . "/{$classname}.php" );
		return ;
	}
	
	//在模块文件夹搜索
	if( file_exists( MOD_DIR . "/{$classname}.php" ) )
	{
		include_once( MOD_DIR . "/{$classname}.php" );
		return ;
	}
	
	//在控制器文件夹搜索
	if( file_exists( CON_DIR . "/{$classname}.php" ) )
	{
		include_once( CON_DIR . "/{$classname}.php" );
		return ;
	}
	
	
}