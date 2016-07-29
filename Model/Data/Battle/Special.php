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
class Data_Battle_Special extends Data_Abstract
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
			'battle_special' => array(
				'columns' => array(
					'floor' , 'room' , 'pass' 
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'battle_special' , $lock  );
		
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
			'id' => $data['id'],
			'floor' => $data['floor'],
			'room' => $data['room'],
			'pass' => $data['pass'],
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
				$returnData['data'][$data[1]] = array(
						'id' => $data[1],
						'floor' => $data[2],
						'room' => $data[3],
						'pass' => $data[4],
					);
			}
		}
		return $returnData;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{
		return $this->data['data'];
	}
	
	/**
	 * 设置普通战场进度
	 * @param unknown $normalInfo
	 */
	public function setData( $addData )
	{
		$keys = array_keys( $this->data['data'] );
		$maxKey = max( $keys );	
		$this->data['data'][$maxKey+1] = array(
			'id' => $maxKey+1,
			'floor' => $addData['floor'],
			'room' => $addData['room'],
			'pass' => $addData['pass'] ? $addData['pass'] : 0,			  
		);
		$this->updateToDb( 'battle_special' , self::DATA_ACTION_ADD , $this->data['data'][$maxKey+1] );
	}

	public function setPassed( $floor , $room )
        {
		foreach( $this->data['data'] as $id => $data )
		{
		   if( $floor == $data['floor'] && $room == $data['room'] && $data['pass'] == 0 )
		   {
			 $this->data['data'][$id]['pass'] = 1; 
			 $this->updateToDb( 'battle_special' , self::DATA_ACTION_UPDATE , $this->data['data'][$id] );
		   }

		}
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
