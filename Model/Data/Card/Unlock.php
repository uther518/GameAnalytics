<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 武将解锁
 * @name model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Card_Unlock extends Data_Abstract
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
				'card_unlock' => array(
					'columns' => array(
						'id' ,
					) ,
				   'isNeedFindAll' => true ,
				) ,
		);
		
		parent::__construct( $userId , 'card_unlock' , $lock  );
	
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
				if( !preg_match( "/card/" , $card[1] ) )
				{
					$card[1] =  $card[1] ."_card";
				}
				$returnData[] = $card[1];
			}
		}
		return $returnData;
		
		return $returnData;
	}
	
	
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		return $this->data;
	}
	
	/**
	 * 解锁武将卡
	 * @param unknown_type $cardInfo
	 */
	public function unlockCard( $cardCode )
	{
		if( !in_array( $cardCode , $this->data ) )
		{
			$this->data[] = $cardCode;
			$this->updateToDb( "card_unlock" , self::DATA_ACTION_ADD , array( 'id' => $cardCode ) );
			return true;
		}
		return false;
	}
	

	
	
}
