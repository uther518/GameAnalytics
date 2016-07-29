<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 别人向我发起好友请求列表
 * @name request.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Friend_Request extends Data_Abstract
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
			'friend_request' => array(
				'columns' => array(
					 'addTime' 
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		
		parent::__construct( $userId , 'friend_request' , $lock  );
	
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_User_Profile
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
			foreach ( $data as $friend )
			{
				$returnData[$friend[1]] = array(
						'id' => $friend[1],
						'addTime' => $friend[2],
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
	 * 添加一个好友请求
	 * @param int $fId
	 */
	public function addReq( $fId )
	{
		if( !$this->data[$fId] )
		{
			$this->data[$fId] = array(
				'id' => $fId,
				'addTime' => $_SERVER['REQUEST_TIME'],
			);
			
			$this->updateToDb( 'friend_request' , self::DATA_ACTION_ADD , $this->data[$fId] );
		}
		return $fId;
	}
	
	/**
	 * 移除一个好友请求
	 * @param int $cardId
	 */
	public function removeReq( $fId )
	{
		if(  $this->data[$fId]  )
		{
			unset( $this->data[$fId] );
			$this->updateToDb( 'friend_request' , self::DATA_ACTION_DELETE , array( 'id' => $fId ) );
		}
	}
	
}
