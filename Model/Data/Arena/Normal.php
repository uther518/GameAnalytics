<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 竞技场信息
 * @name Normal.php
 * @author yanghan
 * @since 2013-05-15
 *
 */
class Data_Arena_Normal extends Data_Abstract
{
	/**
	 * 单例对象
	 * @var	Data_Arena_Normal[]
	 */
	protected static $singletonObjects;
	
	/**
	 * 结构化对象
	 * @param	string $userId	用户ID
	 * @param	boolean $lock	是否加锁（需要写的话一定要加锁）
	 */
	public function __construct( $userId , $lock = false  )
	{
		$this->tablename = 'arena_normal';
		$this->dbColumns = array(
			$this->tablename => array(
				'columnsInfo' => array(
					'benefit' => 0,
					'normalScore' => 0,
					'normalTimes' => 10,
					'normalDate' => 0,
					'normalBonus' => '',
					'advancedScore' => 0,
					'advancedTimes' => 10,
					'advancedDate' => 0,
					'advancedBonus' => '',
					'level' => 0,
				),
				'isNeedFindAll' => false ,
			) ,
		);
		$this->_setColumns();
		parent::__construct( $userId , $this->tablename , $lock  );
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_Arena_Normal
	 */
	public static function & getInstance( $userId , $lock = false , $isNotReadData = false , $isMock = false  )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId , $lock , $isNotReadData , $isMock  );
		}
		
		if( $lock )
		{
			ObjectStorage::register( self::$singletonObjects[$userId] );
		}
		
		return self::$singletonObjects[$userId];
	}
	
	/**
	 * 格式化保存到数据库的数据
	 * @param	array $table	表名
	 * @param	array $data		数据
	 * @return	array
	 */
	protected function formatToDBData( $table , $data )
	{
		$returnData = array();
		foreach ( $data as $k=>$v)
		{
			$returnData[$k] = $v;
		}
		
		return $returnData;
	}
	
	protected function formatFromDBData( $table , $data )
	{
		$returnData = array();
		$idx = 0;
		$cols = &$this->dbColumns[$this->tablename]['columnsInfo'];
		foreach($cols as $k=>$v)
		{
			$returnData[$k] = $this->_getDataByType( $v , $data[++$idx]);
		}
		return $returnData;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{
		$userLvl = User_Info::getInstance( $this->userId )->getLevel();
		$data = $this->dbColumns[$this->tablename]['columnsInfo'];
		$data['level'] = $userLvl;
		
		$this->updateToDb( $this->tablename , self::DATA_ACTION_ADD , $data );
		return $data;
	}
	
	/**
	 * 重置每日挑战次数
	 */
	public function resetDailyTimes()
	{
		$this->data['normalTimes'] = 10;
		$this->data['advancedTimes'] = 10;
		$this->updateToDb( $this->tablename, self::DATA_ACTION_UPDATE, $this->data );
	}
	
	/**
	 * 将单个AI或玩家加入榜单库或更新榜单自身信息
	 * @param int $id 用户或怪物ID
	 * @param array $data
	 * @return boolean
	 */
	public static function setNormalInfo( $id , $data )
	{
		$returnData = self::getInstance( $id , true )->setData( $data );
	
		return $returnData;
	}
	/**
	 * 获取单个怪物或用户的信息
	 * @param int $id
	 * @return array
	 */
	
	public static function getNormalInfo( $id )
	{
		if($id > 0)
		{
			$ret = self::getInstance( $id ,true )->getData();
			$ret['uid'] = $id;
		}
		else
		{
			$cacheKey = 'arena_normal';
			$cacheKey = $id .'_'. $cacheKey;
				
			$cache = Common::getCache();
			$ret = $cache->get( $cacheKey );
			if( !is_array( $ret ) || empty( $ret ) )
			{
				$sql = "select * from `arena_normal` where uid=".intval($id);
				$dbEngine = Common::getDB( 1 );
				$result = $dbEngine->findQuery( $sql );
				if(!empty($result[0]))
				{
					$ret = self::getInstance( 1 )->_formatFromDBData( $result[0] );
					$ret['uid'] = $id;
				}
	
				if( is_array( $ret ) )
				{
					$cache->set( $cacheKey , $ret );
				}
			}
		}
	
		return $ret;
	}
	
}
