<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 使用邀请码邀请到的好友
 * @name model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_InviteCode_User extends Data_Abstract
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
			'code_invite_outline' => array(
				'columns' => array(
					 'award1' 
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		
		parent::__construct( $userId , 'code_outline_invite' , $lock  );
	
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_InviteCode_User
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
			'award1' => $data['award1'],
		);
		
		return $returnData;
	}
	
	/**
	 * 格式化从数据库查到的数据
	 * @see Data_Abstract::formatFromDBData()
	 */
	protected function formatFromDBData( $table , $datas )
	{
		$returnData = array();
		if( !empty( $datas ))
		{
			foreach ( $datas as $data )
			{
				$returnData[$data[1]] = array(
						'id' => $data[1],
						'award1' => $data[2],
					);
			}
		}
		return $returnData;
	}
	
	/**
	 * 邀请到一个好友
	 * @param int $userId 这就是我的邀请码的功劳，帮我邀请到的
	 */
	public function inviteUser( $userId )
	{
		if( !$this->data[$userId] )
		{
			$this->data[$userId] = array(
				'id' => $userId,
				'award1' => 0,	
					
			);
			$this->updateToDb( 'code_invite_outline' , self::DATA_ACTION_ADD , $this->data[$userId] );
		}
	}
	/**
	 * 领取了某位任兄给我带来的奖励
	 * @param unknown $userId
	 * @param unknown $step
	 */
	public function award( $userId , $step )
	{
		$award = "award".$step;
		if(  $this->data[$userId] && $this->data[$userId][$award] == 0 )
		{
			$this->data[$userId][$award] = 1;
			$this->updateToDb( 'code_invite_outline' , self::DATA_ACTION_UPDATE , $this->data[$userId] );
			return true;
		}
		return false;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		return $this->data;
	}
	
	
	
	
}
