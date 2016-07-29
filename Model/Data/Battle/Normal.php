<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 用户资料,注册登录信息
 * @name Profile.php
 * @author liuchangbing
 * @since 2012-12-21
 *
 */
class Data_Battle_Normal extends Data_Abstract
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
			'battle_normal' => array(
				'columns' => array(
					'floor' , 'room' , 'pass' 
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		parent::__construct( $userId , 'battle_normal' , $lock  );
		
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_User_Profile
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
		$returnData = array(
				'floor' => $data['floor'],
				'room' => $data['room'],
				'pass' => $data['pass'],
		);
		
		return $returnData;
	}
	
	protected function formatFromDBData( $table , $data )
	{
		$returnData['data']['floor'] = $data[1];
		$returnData['data']['room'] = $data[2];
		$returnData['data']['pass'] = $data[3];
		
		return $returnData;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		$data = array(
			'floor' => 0,
			'room' => 0,
			'pass' => 0,
		);
		
		$this->updateToDb( 'battle_normal' , self::DATA_ACTION_ADD , $data );
		return $data;
	}
	
	/**
	 * 设置普通战场进度
	 * @param unknown $normalInfo
	 */
	public function setData( $normalInfo )
	{

		$this->data['data']['floor'] = $normalInfo['floor'];
		$this->data['data']['room'] = $normalInfo['room'];
		$this->data['data']['pass'] = $normalInfo['pass'];		
		$this->updateToDb( 'battle_normal' , self::DATA_ACTION_UPDATE , $this->data['data'] );
	}
	
	/**
	 * 获取普通战场进度
	 * @return multitype:
	 */
	public function getData()
	{
		return $this->data['data'];
	}
	
	
	public function setHistory( $history )
	{
		$this->data['history'] = $history;
	}
	
	public function getHistory()
	{
		return $this->data['history'];
	}
	
}
