<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 将领数据模块
 * @name model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Card_Model extends Data_Abstract
{
	/**
	 * 单例对象
	 * @var	Data_Card_Model[]
	 */
	protected static $singletonObjects;
	/**
	 * 结构化对象
	 * @param	string $userId	用户ID
	 * @param	boolean $lock	是否加锁（需要写的话一定要加锁）
	 */
	public function __construct( $userId , $lock = false  )
	{
		$this->dbColumns = array(
			'card' => array(
				'columns' => array(
					'id' , 'cardId' , 'exp' , 'level' , 'skillLevel' , 'addTime' 
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		
		parent::__construct( $userId , 'card_model' , $lock  );
	
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_Card_Model
	 */
	public static function & getInstance( $userId , $lock = false  )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId , $lock  );
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
			
		$returnData = array(
			'id' => $data['id'],
			'cardId' => $data['cardId'],
			'exp' => $data['exp'],
			'level' => $data['level'],
			'skillLevel' => $data['skillLevel'],
			'addTime' => $data['addTime'],
			
		);
		
		return $returnData;
	}
	
	/**
	 * 格式化从数据库查到的数据
	 * @see Data_Abstract::formatFromDBData()
	 */
	protected function formatFromDBData( $table , $data )
	{
		$returnData = array();
		if( !empty( $data ))
		{
			foreach ( $data as $card )
			{
				$returnData[$card[1]] = array(
						'id' => $card[1],
						'cardId' => $card[2],
						'exp' => $card[3],
						'level' => $card[4],
						'skillLevel' => $card[5],
						'addTime' => $card[6],
						
				);
			}
		}
		return $returnData;
	}
	
	
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		return $this->data;
	}
	
	/**
	 * 添加一个武将
	 * @param unknown_type $cardInfo
	 */
	public function addCard( $cardInfo )
	{
		$cardId = 1;
		if( !empty( $this->data ) )
		{
			$cardIds = array_keys( $this->data );
			$cardId = max( $cardIds ) + 1;
		}
		
		$this->data[$cardId] = array(
			'id' => $cardId,
			'cardId' => $cardInfo['cardId'],
			'exp' => $cardInfo['exp'],
			'level' => $cardInfo['level'],
			'skillLevel' => $cardInfo['skillLevel'],
			'addTime' => $_SERVER['REQUEST_TIME'],
		);
		
		$this->updateToDb( 'card' , self::DATA_ACTION_ADD , $this->data[$cardId] );
		return $cardId;
	}
	
	/**
	 * 升级技能等级
	 */
	public function upSkillLevel( $cardId , $maxLevel  )
	{
		if(  $this->data[$cardId]['skillLevel'] >= $maxLevel )
		{
			return false;
		}

		$this->data[$cardId]['skillLevel'] += 1;
		$this->updateToDb( 'card' , self::DATA_ACTION_UPDATE , $this->data[$cardId] );
		return true;
	}
	
	
	/**
	 * 设置技能等级
	 */
	public function setSkillLevel( $cardId , $maxLevel  )
	{
		if( $this->data[$cardId]['skillLevel'] !=  $maxLevel )
		{
			$this->data[$cardId]['skillLevel'] = $maxLevel;
			$this->updateToDb( 'card' , self::DATA_ACTION_UPDATE , $this->data[$cardId] );
		}
		return true;
	}
	
	/**
	 * 设置武将信息
	 * @param unknown_type $exp
	 */
	public function setCardInfo( $cardId , $cardInfo )
	{
		foreach ( $cardInfo as $key=> $value )
		{
			$this->data[$cardId][$key] = $value;
		}
		$this->updateToDb( 'card' , self::DATA_ACTION_UPDATE , $this->data[$cardId] );
	}
	
	/**
	 * 移除一个武将
	 * @param int $cardId
	 */
	public function removeCard( $cardId )
	{	
		$cardLog = new ErrorLog( "cardHistory" );
		$msg = "lostCard>>userId:{$this->userId};cardId:{$this->data[$cardId]['cardId']};addTime:{$_SERVER['REQUEST_TIME']}";
		$cardLog->addLog( $msg );
		$this->updateToDb( 'card' , self::DATA_ACTION_DELETE , array( 'id' => $cardId ) );
		unset( $this->data[$cardId] );
	}
	
	
	/**
	 * 根据武将ID获取武将信息
	 * @param unknown_type $cardId
	 */
	public function getCardInfo( $cardId )
	{
		return $this->data[$cardId] ? $this->data[$cardId] : array();
	}
	
	/**
	 * 加锁
	 * @param unknown_type $cardId
	 */
	public function lock( $cardId )
	{
		$this->data[$cardId]['lock'] = 1;
	
	}
	/**
	 * 解锁
	 * @param unknown_type $cardId
	 */
	public function unlock( $cardId )
	{
		$this->data[$cardId]['lock'] = 0;
		
	}
}
