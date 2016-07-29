<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 竞技场ai
 * @name Ai.php
 * @author yanghan
 * @since 2013-05-15
 *
 */
class Data_Arena_Ai extends Data_Abstract
{
	/**
	 * 单例对象
	 * @var	Data_Arena_Ai[]
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
					'uid' => 0,
					'info' => '',
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
	 * @return	Data_Arena_Ai
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
	 * 获得ai
	 * @return array
	 * */
	public function getEnemyAI()
	{
		//如果没有就从AI中找
		$cache = Common::getCache();
		$enemyListKey = "arena_normal_ai_enemy";
		static $enemyList = array();
		if( empty( $enemyList ) && !$enemyList = $cache->get( $enemyListKey ) )
		{
			$sql = "select * from `arena_ai`";
			$dbEngine = Common::getDB( $this->userId );
			$data = $dbEngine->findQuery( $sql  );
			
			$enemyList = array();
			foreach($data as $v){
				$v = $this->_formatFromDBData($v);
				$enemyList[$v['uid']] = json_decode( $v['info'] , true );
			}
			
			if(empty( $enemyList  ))
			{
				$enemyList = $this->initEnemyAI();
			}
			else
			{
				$cache->set(   "arena_normal_ai_enemy"  , $enemyList  );
			}
		}
		return $enemyList;
	}
	
	/**
	 * 设置AI对手
	 * 自动生成50组NPC，每个NPC带着5个武将,2,3,4,5星各一个
	 */
	public function initEnemyAI()
	{
		$sql = "insert into `arena_ai` values ";
		$sqlRank = "insert into `arena_normal` values ";
		$npcList = array();
		$npcNums = 50;
		for ( $npcId = -$npcNums ; $npcId <  0 ;  $npcId++  )
		{
			$sql .= "(".$npcId.",";
			$sqlRank .= "(".$npcId.",";
			$npcInfo = array();
			for ( $cardId = 1 ; $cardId <= 5 ;  $cardId++ )
			{
				//意思是，6，5，4，3，2星武将各一个
				//$cardStar =  7 - $cardId  ;
				$cardStar = 3;
				$npcInfo[$cardId] = $this->randAICard( $cardStar  );
			}
			$npcList[$npcId] = $npcInfo;
			$sql .= "'".json_encode($npcInfo)."')";
			$sqlRank .= "10,10,0,0,'',10,0,0,'',15)";
			if( $npcId < -1 )
			{
				$sql .= ",";
				$sqlRank .= ",";
			}
		}
	
		//判断是否保存成功
		$inited = true;
		
		$dbEngine = Common::getDB( 1 );
		if( !$dbEngine->query( array( $sql ) ) ) $inited = false;
		if( !$dbEngine->query( array( $sqlRank ) ) ) $inited = false;
		
		if($inited)
		{
			$cache = Common::getCache();
			$cache->set(   "arena_normal_ai_enemy"  , $npcList  );
		}
		return $npcList;
	}
	
	/**
	 * 随机星级武将
	 * @param unknown $star
	 *  
	 */
	private function randAICard( $star )
	{
		$cardConfig = Common::getConfig( "card" );
		$starCards = array();
		foreach ( $cardConfig as $cardId => $card )
		{
			if( $card['star'] == $star )
			{
				$starCards[] = $cardId;
			}
		}
		
		$randNum = mt_rand( 0 ,  count(  $starCards ) - 1 );
		return $starCards[$randNum];
	}
}
