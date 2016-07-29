<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 竞技场排名历史记录
 * @name Rank.php
 * @author yanghan
 * @since 2013-05-15
 *
 */
class Data_Arena_Rank extends Data_Abstract
{
	/**
	 * 单例对象
	 * @var	Data_Arena_Rank[]
	 */
	protected static $singletonObjects;
	
	/**
	 * 结构化对象
	 * @param	string $userId	用户ID
	 * @param	boolean $lock	是否加锁（需要写的话一定要加锁）
	 */
	public function __construct( $userId , $lock = false  )
	{
		$this->tablename = 'arena_ai';
		$this->dbColumns = array(
			$this->tablename => array(
				'columnsInfo' => array(
					'type' => 0,
					'time' => 0,
					'data' => '',
				),
				'isNeedFindAll' => false ,
			) ,
		);
		$this->_setColumns();
		//parent::__construct( $userId , $this->tablename , $lock  );
		
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_Arena_Rank
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
		$idx = -1;
		$cols = &$this->dbColumns[$this->tablename]['columnsInfo'];
		foreach($cols as $k=>$v)
		{
			$returnData[$k] = $this->_getDataByType( $v , $data[++$idx]);
		}
		return $returnData;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{
		$data = $this->dbColumns[$this->tablename]['columnsInfo'];
		
		$this->updateToDb( $this->tablename , self::DATA_ACTION_ADD , $data );
		return $data;
	}
	
	/**
	 * 保存排行结果
	 * */
	public function setRankResult( $type , $time , $data)
	{
		$cacheKey = 'arena_rank';
		$cacheKey = $cacheKey."_".$type."_".$time;
			
		$cache = Common::getCache();
		$cache->delete( $cacheKey );
		
		$sql = "insert into `arena_rank` values(".intval($type).",".intval($time).",'".json_encode($data)."')";

		$dbEngine = Common::getDB( $this->userId );
		$result = $dbEngine->query( array( $sql )  );
		
		return true;
	}
	
	/**
	 * 获得排行结果
	 * @param int $type 排行类型(0用于判断是否存在记录,1普通,2精英)
	 * @param int $time 排行时间每周周一10点
	 * @param int $id 获得指定用户的排名
	 * @return array 排行结果
	 * */
	public function getRankResult( $type , $time , $id = 0 )
	{
		$cacheKey = 'arena_rank';
		$cacheKey = $cacheKey."_".$type."_".$time;
			
		$cache = Common::getCache();
		$ret = $cache->get( $cacheKey );
		if( !is_array( $ret ) || empty( $ret ) )
		{
			if( $type == 0 )
			{
				$sql = "select type,time,'[1]' from `arena_rank` where type=1 and time=".intval($time);
			}
			else
			{
				$sql = "select * from `arena_rank` where type=".intval($type)." and time=".intval($time);
			}
			$dbEngine = Common::getDB( $this->userId );
			$result = $dbEngine->findQuery( $sql  );
			
			if(!empty($result[0])) $ret = Data_Arena_Rank::getInstance( $this->userId )->_formatFromDBData( $result[0] );
		
			if( is_array( $ret ) )
			{
				$cache->set( $cacheKey , $ret );
			}
		}
		
		$ret = empty( $ret['data'] ) ? array() : json_decode( $ret['data'] , true );
		if( empty($id) )
		{
			return $ret;
		}
		else
		{
			$rank = Arena_Normal::NUMBER_NOT_IN_RANK;
			foreach( $ret as $k => $v )
			{
				if( $v['uid'] == $id )
				{
					$rank = $k + 1;
				}
			}
			return array( 'rankList' => $ret , 'rank' => $rank );
		}
	}
	
}
