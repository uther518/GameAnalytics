<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 帮助好友记录
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_User_Help extends Data_Abstract
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
			'user_help' => array(
				'columns' => array(
					 'friendTimes' , 'friendPoint' , 'otherTimes' , 'otherPoint' , 'lastTime'
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		parent::__construct( $userId , 'user_help' , $lock  );
	
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
		if( $table == 'user_help' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['user_help']['columns'] ) )
				{
					$returnData[$key] = $value;
				}	
			}
		}
		return $returnData;
	}
	
	protected function formatFromDBData( $table , $data )
	{
		
		$formatedData = array(
			'friendTimes' => $data[1],
			'friendPoint' => $data[2],
			'otherTimes' => $data[3],	//冲值币
			'otherPoint' => $data[4],	//金币
			'lastTime' => $data[5],	//耐力
			
		);
		return $formatedData;
	}

	protected function emptyDataWhenloadFromDB( $table )
	{	
	
		$data = array(
			'friendTimes' => 0,
			'friendPoint' => 0,
			'otherTimes' => 0,	//金币
			'otherPoint' => 0,	//冲值币
			'lastTime' => 0,	//耐力
		);
		
		$this->updateToDb( 'user_help' , self::DATA_ACTION_ADD , $data );
		return $data;
	}
	
	
	public function setFriendHelp( $point )
	{
		$this->data['friendTimes'] += 1;
		$this->data['friendPoint'] += $point;
		$this->data['lastTime'] = $_SERVER['REQUEST_TIME'];
		$this->updateToDb( 'user_help' , self::DATA_ACTION_UPDATE  , $this->data );
	}
	
	

	public function setOtherHelp( $point )
	{
		$this->data['otherTimes'] += 1;
		$this->data['otherPoint'] += $point;
		$this->data['lastTime'] = $_SERVER['REQUEST_TIME'];
		$this->updateToDb( 'user_help' , self::DATA_ACTION_UPDATE  , $this->data );
	}

	
	public function clear()
	{
		$this->data = array(
				'friendTimes' => 0,
				'friendPoint' => 0,
				'otherTimes' => 0,	
				'otherPoint' => 0,	
				'lastTime' => 0,
		);
		$this->updateToDb( 'user_help' , self::DATA_ACTION_UPDATE  , $this->data );
	}
}
