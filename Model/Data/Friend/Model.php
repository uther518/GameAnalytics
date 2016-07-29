<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 好友数据模块
 * @name model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Friend_Model extends Data_Abstract
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
			'friend' => array(
				'columns' => array(
					 'hasHelp','addTime' 
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		
		parent::__construct( $userId , 'friend_model' , $lock  );
	
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
			'hasHelp' => $data['hasHelp'],
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
						'hasHelp' => $friend[2],
						'addTime' => $friend[3],
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
	 * 添加一个好友
	 * @param int $fId
	 */
	public function addFriend( $fId )
	{
		
		if( $this->data[$fId] )
		{
			return $fId;
		}
		
		$this->data[$fId] = array(
			'id' => $fId,
			'hasHelp' => 0,
			'addTime' => $_SERVER['REQUEST_TIME'],
		);
		
		$this->updateToDb( 'friend' , self::DATA_ACTION_ADD , $this->data[$fId] );
		return $fId;
	}
	
	/**
	 * 移除一个好友
	 * @param int $cardId
	 */
	public function removeFriend( $fId )
	{
		unset( $this->data[$fId] );
		$this->updateToDb( 'friend' , self::DATA_ACTION_DELETE , array( 'id' => $fId ) );
	}
	
	
	public function clearHelped()
	{
		foreach ( $this->data as $fid => $info )
		{
			if(  $this->data[$fid]['hasHelp'] == 1 )
			{
				$this->data[$fid]['hasHelp'] = 0;
				$this->updateToDb( 'friend' , self::DATA_ACTION_UPDATE , $this->data[$fid] );
			}
		}
	}
	
	/**
	 * 设置是否帮助过
	 * @param unknown_type $help
	 */
	public function setHelped( $fId ,  $help )
	{
		$this->data[$fId]['hasHelp'] = $help ? 1 : 0;
		$this->updateToDb( 'friend' , self::DATA_ACTION_UPDATE , $this->data[$fId] );
	}
	
	/**
	 * 是否好友
	 * @param int $fId
	 */
	public function areFriend( $fId )
	{
		return $this->data[$fId] ? true : false;
	}
	
}
