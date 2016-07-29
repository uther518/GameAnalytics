<?php 

/**
 * @file	BaseModel.php
 * @author	wzhzhang , Luckyboys
 * @date	2010.06.01
 * @brief	所有model的基类
 */
if( !defined( 'IN_INU' ) )
{
    return;
}
abstract class Data_Abstract
{
	/**
	 * 模块所属的用户ID
	 * @var	int
	 */
	protected $userId;
	
	/**
	 * 表名
	 * @var string
	 * */
	protected $tablename;
	
	/**
	 * 存储在缓存中的键名
	 * @var	string
	 */
	private $_userCacheKey;
	
	/**
	 * 缓存对象
	 * @var	iCache
	 */
	private $_cacheEngine;
	
	/**
	 * 缓存对象
	 * @var	iCache
	 */
	private $_cacheEngineForLock;
	
	/**
	 * 缓存主键名
	 * @var	string
	 */
	private $_cacheKey;
	
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
	 * 系统配置
	 *
	 * @var	array
	 */
	protected $config;
	
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
	 * @param	int $userId			用户ID
	 * @param	string $cacheKey	缓存主键名
	 * @param	boolean $lock		是否需要加锁
	 */
	protected function __construct( $userId , $cacheKey , $lock = false  )
	{
		if( $userId < 1 )
		{
			throw new GameException( 201 );
		}
	
		$this->userId = $userId;
		$this->_userCacheKey = $userId .'_'. $cacheKey;
		$this->_cacheKey = $cacheKey;
		$this->_cacheEngine = & Common::getCache();
		$this->_cacheEngineForLock = & Common::getCache( 'lock' );

		if( $lock )
		{
			$this->_tryLock();
		}
		
		$this->_init();
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
	 */
	private function _init()
	{
	
		$this->_loadFromCache();
		if( !is_array( $this->data ) || empty( $this->data ) )
		{
			$needSaveCache = $this->_loadFromDb();
			$this->afterLoadDb();
			if( $needSaveCache ) $this->_saveToCache();
			//$this->_saveToDb();
		}
	}
	
	/**
	 * 从缓存加载数据
	 */
	private function _loadFromCache()
	{
		$this->data = $this->_cacheEngine->get( $this->_userCacheKey );
	}
	
	/**
	 * 将数据保存到缓存
	 */
	private function _saveToCache()
	{
		if( is_array( $this->data ) )
		{
			$this->_cacheEngine->set( $this->_userCacheKey , $this->data );
		}
	}
	
	/**
	 * 从数据库中获取数据
	 */
	private function _loadFromDb()
	{
		$db = & Common::getDB( $this->userId );
		$needSaveCache = false;
		
		$this->data = array();
		foreach( $this->dbColumns as $tableName => $tableConfig )
		{
			
			$data = array();
			if( $tableConfig['isNeedFindAll'] )
			{
				$data = $db->findAll( $tableName , $tableConfig['columns'] );
			}
			else 
			{
				$data = $db->find( $tableName , $tableConfig['columns'] );
			}
			
			if( !$data )
			{
				$data = $this->emptyDataWhenloadFromDB( $tableName );
			}
			else 
			{	
				$data = $this->formatFromDBData( $tableName , $data );
				$needSaveCache = true;
			}
			//$this->data = array_merge( $this->data , $data );		
			$this->data = $data;			
		}
	
		return $needSaveCache;
	}
	
	/**
	 * 将数据保存到数据库中
	 */
	private function _saveToDb()
	{
		$db = null;
		foreach( $this->_dirtyData as $record )
		{
			$db = & Common::getDB( $record['userId'] );
			$data = $this->formatToDBData( $record['table'] , $record['data'] );
			if( $data )
			{
				$condition = array();
				if( isset( $data['id'] ) )
				{
					$condition['id'] = $data['id'];
					unset( $data['id'] );
				}
				switch( $record['action'] )
				{
					case self::DATA_ACTION_ADD:
						$rs = $db->add( $record['table'] , $data , $condition );
						break;
					case self::DATA_ACTION_UPDATE:
						$db->update( $record['table'] , $data , $condition );
						break;
					case self::DATA_ACTION_DELETE:
						$db->delete( $record['table'] , $condition );
						break;
				}
			}
		}
		$this->_dirtyData = array();
	}
	
	protected function updateToDb( $table , $action , $data = array() )
	{
		$this->_dirtyData[] = array(
			'userId' => $this->userId ,
			'table' => $table ,
			'action' => $action ,
			'data' => $data ,
		);
	}
	
	
	/**
	 * 生成SQL语句集,当前使用在mysql_pool中
	 */
	public function makeSqlCollect()
	{
		$this->_saveToDb();	
	}
	
	/**
	 * 保存数据
	 */
	public function save()
	{
		$this->_saveToCache();
		$this->_dirtyData = array();
		/*
		if( !$this->_isPostProcessingDeleteCache )
		{
			$this->_saveToCache();
		}
		
		$this->_saveToDb();
		$this->_dirtyData = array();
		if( !$this->_isPostProcessingDeleteCache )
		{
			$this->_unlock();
		}
		else 
		{
			$this->deleteCache();
		}	
		*/
	}

	/**
	 * 尝试加锁
	 * 循环延迟0.1秒执行
	 */
	private function _tryLock()
	{
		$this->_lock = true;
		/*
		$flag = false;
		for ( $i = 0 ; $i < 20 ; $i++ )
		{
			while( $this->_cacheEngineForLock->add( $this->_userCacheKey . '_lock' , 1 , 5 ) )
			{
				$flag = true;
			}
			usleep( 100000 );
		}
		
		
	
		if( $flag == false )
		{
			throw new GameException( 200 );
		}
		*/
	}
	/**
	 * 加锁
	 */
	private function _lock()
	{
		
		if( !$this->_cacheEngineForLock->add( $this->_userCacheKey . '_lock' , 1 , 5 ) )
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
		//$this->_cacheEngineForLock->delete( $this->_userCacheKey . '_lock' );
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
				$result = $this->_cacheEngine->delete( $this->_userCacheKey );
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
	protected function changeCacheKey( $userId , $cacheKey = null )
	{
		$result = false;
		if( $this->_lock && is_array( $this->data ) )
		{
			if( $cacheKey == null )
			{
				$cacheKey = $this->_cacheKey;
			}
			
			$userCacheKey = $userId .'_'. $cacheKey;
			if( !$this->_cacheEngine->add( $userCacheKey . '_lock' , 1 , 5 ) )
			{
				throw new GameException( 200 );
			}
			
			$result = $this->_cacheEngine->set( $userCacheKey , $this->data );
			if( $result )
			{
				$result = $this->deleteCache();
			}
			
			if( $result )
			{
				$this->_lock = true;
				$this->_userCacheKey = $userCacheKey;
				$this->userId = $userId;
			}
		}

		return $result;
	}
	/**
	 * 格式化从数据库读取出来的数据
	 * @param	array $table	表名
	 * @param	array $data		数据
	 * @return	array
	 */
	abstract protected function formatFromDBData( $table , $data );
	
	/**
	 * 当获取数据是发现返回是空数据
	 * @param	array $table	表名
	 * @return	array
	 */
	abstract protected function emptyDataWhenloadFromDB( $table );
	
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
	 * 获取用户ID
	 * @return	int
	 */
	public function getUserId()
	{
		return $this->userId;
	}
	
	/**
	 * 获取缓存主键
	 * @return	string
	 */
	public function getCacheKey()
	{
		return $this->_cacheKey;
	}
	/**
	 *设置值，仅在测试模拟时可用
	 */
	public function set( $item , $value )
	{
		$this->data[$item] = $value;
	}
	
	protected function _setColumns()
	{
		$colsInfo = &$this->dbColumns[$this->tablename]['columnsInfo'];
		$cols = &$this->dbColumns[$this->tablename]['columns'];
		foreach($colsInfo as $k=>$v)
		{
			$cols[] = $k;
		}
	}
	
	protected function _getDataByType( $type , $var )
	{
		$ret = $var;
		if( is_float( $type ) )
		{
			$ret = floatval($var);
		}
		elseif( is_int( $type ) )
		{
			$ret = intval( $var );
		}
		else
		{
			$ret = strval($var);
		}
		return $ret;
	}
	
	/**
	 * 设置数据
	 * @param array $data 需设置的数据
	 * */
	public function setData($data)
	{
		$cols = &$this->dbColumns[$this->tablename]['columnsInfo'];
		foreach($data as $k=>&$v)
		{
			if( isset( $cols[$k] ) )
			{
				$this->data[$k] = $this->_getDataByType( $cols[$k] , $v );
			}
		}
		$this->updateToDb( $this->tablename, self::DATA_ACTION_UPDATE, $data );
		return true;
	}
	
	
	
	public function formatData($data)
	{
		$cols = &$this->dbColumns[$this->tablename]['columnsInfo'];
		foreach($data as $k=>&$v)
		{
			if( isset( $cols[$k] ))
			{
				$v = $this->_getDataByType( $cols[$k] , $v );
			}
		}
		if(isset($data['uid'])) $data['uid'] = intval($data['uid']);
		return $data;
	}
	
	public function _formatFromDBData($data)
	{
		return $this->formatFromDBData($this->tablename, $data);
	}
	
}
