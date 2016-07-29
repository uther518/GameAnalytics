<?php
/** 
 * 普通数据访问基层
 * @author Lucky
 */
if( !defined( 'IN_INU' ) )
{
    return;
}
abstract class Data_aNormalDataBaseModel
{
	/**
	 * 存储在缓存中的键名
	 * @var	string
	 */
	private $_cacheIndexKey;
	
	/**
	 * 缓存对象
	 * @var	iCache
	 */
	private $_cacheEngine;
	
	/**
	 * 缓存写锁对象
	 * @var	iCache
	 */
	private $_cacheLockEngine;
	
	/**
	 * 缓存主键名
	 * @var	string
	 */
	private $_dataKey;
	
	/**
	 * 模块数据
	 * @var	array
	 */
	protected $data;
	
	/**
	 * 模块是否加锁
	 * @var	boolean
	 */
	protected $_lock = false;
	
	/**
	 * 数据是否已经修改（是否有脏数据）
	 * @var	array(
	 * 			array(
	 * 				table:string
	 * 				action:int
	 * 				data:array
	 * 			)
	 * 		)
	 */
	private $_dirtyData = array();
	
	/**
	 * 数据需要读取的字段名（如果是多条记录，需要加入id字段）
	 * @var	array(
	 * 			{tableName}:array(			//数据表名称
	 * 				columns:array(			//字段名
	 * 					{columnName} , ...
	 * 				) ,
	 * 				isNeedFindAll:boolean	//是否需要搜索多条
	 * 			)
	 * 		)
	 */
	protected $dbColumns = array();
	
	/**
	 * 是否后置删除缓存
	 * @var	boolean
	 */
	private $_isPostProcessingDeleteCache = false;
	
	/**
	 * 索引数据（搜索条件）
	 * @var array(
	 * 			{key}:{value}
	 * 		)
	 */
	protected $indexData = array();
	
	/**
	 * 所用的memcache
	 * @var string
	 */
	protected $dataCache = 'data';
	
	/**
	 * 数据操作行为（添加）
	 * @var	int
	 */
	const DATA_ACTION_ADD = 1;
	
	/**
	 * 数据操作行为（更新）
	 * @var	int
	 */
	const DATA_ACTION_UPDATE = 2;
	
	/**
	 * 数据操作行为（删除）
	 * @var	int
	 */
	const DATA_ACTION_DELETE = 3;
	
	/**
	 * 初始化模块
	 * @param	string $cacheKey	缓存主键名
	 * @param	boolean $lock		是否需要加锁
	 * @param	boolean $isNotReadData		是否不需要读取数据（高风险选项，只适合用户注册时使用）
	 */
	protected function __construct( $dataKey , $lock = false , $isNotReadData = false )
	{
		$this->_dataKey = $dataKey;
		$this->_cacheIndexKey = $this->makeCacheIndexKey();
		if( empty( $this->_cacheIndexKey ) )
		{
			throw new GameException( 201 );
		}
		
		$this->_cacheEngine = & Common::getCache( $this->dataCache );
		$this->_cacheLockEngine = & Common::getCache( 'lock' );
		
		if( $lock )
		{
			$this->_lock();
		}
		
		$this->_init( $isNotReadData );
	}
	
	/**
	 * 析构
	 */
	public function __destruct()
	{
		if( $this->_lock )
		{
			$this->_unlock();
		}
	}
	
	/**
	 * 初始化所有数据
	 * @param	boolean $isNotReadData		是否不需要读取数据（高风险选项，只适合用户注册时使用）
	 */
	private function _init( $isNotReadData = false )
	{
		if( !$isNotReadData )
		{
			$this->_loadFromCache();
		}
		
		if( !is_array( $this->data ) )
		{
			$this->_loadFromDb( $isNotReadData );
			$this->afterLoadDb();
			$this->_saveToCache();
			$this->_saveToDb();
		}
	}
	
	/**
	 * 从缓存加载数据
	 */
	private function _loadFromCache()
	{
		$this->data = $this->_cacheEngine->get( $this->_cacheIndexKey );
	}
	
	/**
	 * 将数据保存到缓存
	 */
	private function _saveToCache()
	{
		if( is_array( $this->data ) )
		{
			$this->_cacheEngine->set( $this->_cacheIndexKey , $this->data );
		}
	}
	
	/**
	 * 从数据库中获取数据
	 * @param	boolean $isNotReadData		是否不需要读取数据（高风险选项，只适合用户注册时使用）
	 */
	private function _loadFromDb( $isNotReadData = false )
	{
		if( !$isNotReadData )
		{
			$db = & Common::getNormalDatabaseEngine( $this->_dataKey );
		}
		$this->data = array();
		
		foreach( $this->dbColumns as $tableName => $tableConfig )
		{
			$data = array();
			if( !$isNotReadData )
			{
				if( $tableConfig['isNeedFindAll'] )
				{
					$data = $db->fetchArray( $tableConfig['querySQL'] );
				}
				else 
				{
					$data = $db->fetchOneAssoc( $tableConfig['querySQL'] );
				}
			}
			
			if( !$data )
			{
				$data = $this->emptyDataWhenloadFromDB( $tableName );
			}
			
			$data = $this->formatFromDBData( $tableName , $data );
			$this->data = array_merge( $this->data , $data );
		}
	}
	
	/**
	 * 将数据保存到数据库中
	 */
	private function _saveToDb()
	{
		$db = null;
		foreach( $this->_dirtyData as $record )
		{
			if( !$db )
			{
				$db = & Common::getNormalDatabaseEngine( $this->_dataKey );
			}
			$sql = $this->formatToDBData( $record['table'] , $record['action'] , $record['data'] );
			if( $sql )
			{
				$db->query( $sql );
				if( $db->getErrorNumber() > 0 )
				{
					;
				}
			}
		}
	}
	
	protected function updateToDb( $table , $action , $data )
	{
		$this->_dirtyData[] = array(
			'table' => $table ,
			'action' => $action ,
			'data' => $data ,
		);
	}
	
	/**
	 * 保存数据
	 */
	public function save()
	{
		if( $this->_lock )
		{
			$this->_saveToDb();
			$this->_dirtyData = array();
			
			if( !$this->_isPostProcessingDeleteCache )
			{
				$this->_saveToCache();
			}
			
			if( !$this->_isPostProcessingDeleteCache )
			{
				$this->_unlock();
			}
			else 
			{
				$this->deleteCache();
			}
		}
	}

	/**
	 * 加锁
	 */
	private function _lock()
	{
		if( !$this->_cacheLockEngine->add( $this->_cacheIndexKey . '_lock' , 1 , 5 ) )
		{
			throw new GameException( 200 );
		}
		
		$this->_lock = true;
	}
	
	/**
	 * 解锁
	 */
	private function _unlock()
	{
		$this->_cacheLockEngine->delete( $this->_cacheIndexKey . '_lock' );
		$this->_lock = false;
	}
	
	/**
	 * 获取是否已经加锁
	 * @return	boolean
	 */
	public function isLocked()
	{
		return $this->_lock;
	}
	
	/**
	 * 删除缓存数据
	 * @return	boolean
	 */
	public function deleteCache( $isPostProcessing = false )
	{
		if( !$isPostProcessing )
		{
			$result = false;
			if( $this->_lock )
			{
				$result = $this->_cacheEngine->delete( $this->_cacheIndexKey );
				if( $result )
				{
					$this->_unlock();
				}
			}
			return $result;
		}

		else 
		{
			$this->_isPostProcessingDeleteCache = true;
			return true;
		}
	}
	
	/**
	 * 切换缓存Key
	 * @param	int $userId	用户ID
	 * @param	string $cacheKey	[optional]缓存主键名
	 * @return	boolean
	 */
	protected function changeCacheKey( $newCacheIndexKey )
	{
		$result = false;
		if( $this->_lock && is_array( $this->data ) )
		{
			if( !$this->_cacheLockEngine->add( $newCacheIndexKey . '_lock' , 1 , 5 ) )
			{
				throw new GameException( 200 );
			}
			
			$result = $this->_cacheEngine->set( $newCacheIndexKey , $this->data );
			if( $result )
			{
				$result = $this->deleteCache();
			}
			
			if( $result )
			{
				$this->_lock = true;
				$this->_cacheIndexKey = $newCacheIndexKey;
			}
		}

		return $result;
	}
	
	/**
	 * 在读取完数据库之后的一些操作
	 */
	protected function afterLoadDb()
	{
		;
	}
	
	/**
	 * 获取数据信息
	 * @return	array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * 获取缓存主键
	 * @return	string
	 */
	public function getCacheKey()
	{
		return $this->_dataKey;
	}
	
	/**
	 * 获取唯一索引键
	 */
	public function getIndexKey()
	{
		return $this->makeCacheIndexKey();
	}
	
	/**
	 * 格式化保存到数据库的数据
	 * @param	array $table	表名
	 * @param	array $action	操作动作
	 * @param	array $data		数据
	 * @return	array
	 */
	abstract protected function formatToDBData( $table , $action , $data );
	
	/**
	 * 格式化从数据库读取出来的数据
	 * @param	array $table	表名
	 * @param	array $data		数据
	 * @return	string
	 */
	abstract protected function formatFromDBData( $table , $data );
	
	/**
	 * 当获取数据是发现返回是空数据
	 * @param	array $table	表名
	 * @return	array
	 */
	abstract protected function emptyDataWhenloadFromDB( $table );
	
	/**
	 * 计算索引缓存键值
	 * @return	string
	 */
	abstract protected function makeCacheIndexKey();
}
?>