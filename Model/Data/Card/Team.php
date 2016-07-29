<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 武将卡编队模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Card_Team extends Data_Abstract
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
			'card_team' => array(
				'columns' => array(
					'cards'
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		parent::__construct( $userId , 'card_team' , $lock  );
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
		
		if( $lock && !self::$singletonObjects[$userId]->isLocked() )
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
		if( $table == 'card_team' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['card_team']['columns'] ) )
				{
					$returnData[$key] = $value;
				}	
			}
		}
		return $returnData;
	}
	
	protected function formatFromDBData( $table , $data )
	{
		return array( 'cards' => $data[1] );
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		$data = array(
			'cards' => "",
		);
		$this->updateToDb( 'card_team' , self::DATA_ACTION_ADD , $data );
		return $data;
	}
	
	/**
	 * 设置武将编队
	 * @param int $leaderId
	 */
	public function setCards( $cardStr )
	{
		$this->data['cards'] = $cardStr;
		$this->updateToDb( 'card_team' , self::DATA_ACTION_UPDATE , array( 'cards' => $cardStr ) );
	}
	
	
}
