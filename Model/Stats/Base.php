<?php
/**
 * 统计基类
 * 
 * @name Base.php
 * @author admin
 * @since 2013-7-5
 */
if( !defined( 'IN_INU' ) )
{
    return;
}

class Stats_Base
{
	
	/**
	 * 应用ID
	 * @var unknown
	 */
	protected static $appId;
	
	/**
	 * 服务器Id
	 * @var unknown
	 */
	protected static $sid;
	/**
	 * mongodb实例
	 * @var unknown
	 */
	protected static $mongoDB;
	
	
	//集合名
	protected static $collectName;
	
	
	public static function setAppId( $appId )
	{
		self::$appId = $appId;
	}
	
	
	public static function setSid( $sid )
	{
		self::$sid = $sid;
	}
	
	/**
	 * 往表中，增加一个记录
	 * @param unknown $collectName 集合名，表名
	 * @param unknown $record 记录数组,同一个表字段要一至
	 */
	public static function add( $collectName ,  $record )
	{
		$collectName = self::init( $collectName );
		$status = self::$mongoDB->insert( $collectName, $record );
	}
	
	/**
	 * 在指定集合中，查询
	 * @param unknown $collectName 集合名,相当于mysql的表名
	 * @param unknown $queryCondition 查询条件数据，见test
	 * @param unknown $resultCondition 过滤结果的条件
	 * @param unknown $fields 结果集中显示的字段
	 */
	public static function find( $collectName , $queryCondition  = array(), $resultCondition = array() ,  $fields = array() )
	{
		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->find(  $collectName , $queryCondition , $resultCondition , $fields );
		
		return  $rs;
	}
	
/**
	 * 在指定集合中，查询一条数据
	 * @param unknown $collectName 集合名,相当于mysql的表名
	 * @param unknown $queryCondition 查询条件数据，见test
	 * @param unknown $fields 结果集中显示的字段
	 */
	public static function findOne( $collectName , $queryCondition  = array() ,  $fields = array() )
	{

		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->findOne(  $collectName , $queryCondition , $fields );
		return  $rs;
	}
	
	
	/**
	 * 查询不重复的值
	 * @param unknown $collectName
	 * @param unknown $queryCondition
	 */
	public static function distinct( $collectName , $key , $queryCondition = array()  )
	{
		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->distinct(  $collectName , $key ,  $queryCondition );
		return  $rs;
	}
	
	/**
	 * 在指定集合中，更新
	 * @param unknown $collectName
	 * @param unknown $queryCondition
	 * @param unknown $newdata
	 */
	public static function update( $collectName , $queryCondition , $newdata )
	{
		$collectName = self::init( $collectName );
		$status = self::$mongoDB->update( $collectName , $queryCondition, $newdata );
		return $status;
	}
	
	/**
	 * 在指定集合中，删除
	 * @param unknown $collectName
	 * @param unknown $queryCondition
	 */
	public static function remove( $collectName , $queryCondition )
	{
		$collectName = self::init( $collectName );
		$status = self::$mongoDB->remove( $collectName , $queryCondition );
	}
	
	public static function count(  $collectName , $queryCondition  = array() )
	{
		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->count( $collectName , $queryCondition );
		return  $rs;
	}
	
	public static function group( $collectName , $keys , $initial , $reduce , $condition  = null )
	{
		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->group( $collectName , $keys , $initial , $reduce , $condition );
		return  $rs;
	}

	
	/**
	 * 聚合方法mongodb2.2以上版本可用
	 * @param unknown $collection
	 * @param unknown $query
	 */
	public static function aggregate( $collectName , $query )
	{
		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->aggregate( $collection , $query );
		return  $rs;
	}
	
	
	public static function index(  $collectName , $index )
	{
		$collectName = self::init( $collectName );
		$rs = self::$mongoDB->ensureIndex( $collectName , $index );
		return  $rs;
	}
	
	
	/**
	 * 获取单服配置信息
	 * @return unknown
	 */
	public static function serverInfo()
	{
		$collectName = 'serverList';
		$collectName = self::init( $collectName , true );
		$rs = self::$mongoDB->findOne(  $collectName , array( 'appId' => (int)self::$appId ,  'sid' => (int)self::$sid ) );
		return  $rs;
	}
	

	/**
	 * 初始化mongodb实例，选择一个数据库
	 */
	protected static function init( $collectName  , $flag = false )
	{
	    $config = Common::getConfig( 'mongoDb' );
	    self::$mongoDB = InuMongoDB::getInstance( $config['statsDB']['host'] );
	    if( !self::$mongoDB )
	    {
	    	echo "请执行initDB和setDBName";
	    	return false;
	    }
	    
	    if( $flag == true || !self::$appId )
	    {
	    	$dbName =  $config['statsDB']['dbname'];
	    	self::$mongoDB->selectDb(  $dbName );
	    	return $collectName;
	    }
	    else 
	    {
	    	$dbName =  "Data_App_".self::$appId;
	    	self::$mongoDB->selectDb(  $dbName );
	    	return $collectName;
	    	
	    }
	}
	
	
	
}
