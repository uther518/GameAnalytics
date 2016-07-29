<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 累计消费活动
 * @name Model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Activity_Model extends Data_Abstract
{
	
	/**
	 * 单例对象
	 * @var	User_Model[]
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
			'activity' => array(
				'columns' => array(
					'id' , 'rewardTime' , 
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		
		parent::__construct( $userId , 'activity_model' , $lock  );
	
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_Activity_Model
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
			'rewardTime' => (int)$data['rewardTime'],
		
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
						'rewardTime' => $card[2],
						
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
	 * 更新活动时间
	 * @param unknown_type $cardInfo
	 */
	public function updateRecord( $actId )
	{
		
		if( empty( $this->data[$actId] ) )
		{
			
			$act = self::DATA_ACTION_ADD;
			$this->data[$actId] = array(
					'id' => $actId,
					'rewardTime' => $_SERVER['REQUEST_TIME'],
			);
			
		}
		else
		{
			$act = self::DATA_ACTION_UPDATE;
			$this->data[$actId]['rewardTime'] = $_SERVER['REQUEST_TIME'];
		}
		
		$this->updateToDb( 'activity' , $act  , $this->data[$actId] );
		return true;
	}
	
	
	/**
	 * 获取活动信息
	 * @param unknown $actId
	 */
	public function getActivity( $actId )
	{
		return $this->data[$actId];
	}
	
	
}
