<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 用户信息模块
 * @name Info.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Admin_Model extends Data_Abstract
{
	
	const ADMIN_USER_ID = 1;
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
			'admin_user' => array(
				'columns' => array(
					'id', 'loginName' , 'password' , 'permission'
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'admin_user' , $lock  );
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_User_Profile
	 */
	public static function & getInstance( $userId , $lock = false  )
	{
		$userId = $userId ? $userId : self::ADMIN_USER_ID;
		
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
		if( $table == 'admin_user' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['admin_user']['columns'] ) )
				{
					$returnData[$key] = $value;
				}	
			}
		}
		return $returnData;
	}
	
	protected function formatFromDBData( $table , $data )
	{
		foreach ( $data as $admin )
		{
			//$this->data[$admin['loginName']] = $admin;
			$this->data[$admin[2]] = array(
					'id' => $admin[1],
					'loginName' => $admin[2],
					'password' => $admin[3],
					'permission' => $admin[4],
					
			);
		
		}

		return $this->data;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		$loginName = "admin";
		$password = "lchb5299";
		$this->addAdmin( $loginName, $password , '*' );
		return $this->data[$loginName];
	}
	
	/**
	 * 设置管理员信息
	 * @param unknown $userInfo
	 */
	public function addAdmin( $loginName , $password , $permission )
	{	
		$loginName = trim( $loginName );
		$password = trim( $password );
		if( !$this->data[$loginName])
		{
			
			$newId = rand( 1 , 10000000 );
			$this->data[$loginName] =array(
					'id' => $newId,
					'loginName' => $loginName,
					'password' => md5( md5( $password ).'#@$3FW4#' ),
					'permission' => $permission,
			);
			
			
			$this->updateToDb( 'admin_user' , self::DATA_ACTION_ADD , $this->data[$loginName] );
		}
		else 
		{
			$this->setAdmin($loginName, $password, $permission);
		}
		//print_r( $this->data );exit;
		
		return $this->data;
	}
	
	
	public function setAdmin( $loginName , $password , $permission )
	{

		$loginName = trim( $loginName );
		$password = trim( $password );
	
		$this->data[$loginName] =array(
				'id' => $this->data[$loginName]['id'],
				'loginName' => $loginName,
				'password' => $password ? md5( md5( $password ).'#@$3FW4#' ) : $this->data[$loginName]['password'] ,
				'permission' => $permission ? $permission : $this->data[$loginName]['permission'] ,
		);
		$this->updateToDb( 'admin_user' , self::DATA_ACTION_UPDATE , $this->data[$loginName] );
		
		return $this->data;
	}
	
	
	public function deleteAdmin( $loginName )
	{
		if( $this->data[$loginName])
		{
	
			$this->updateToDb( 'admin_user' , self::DATA_ACTION_DELETE , array( 'id' =>   $this->data[$loginName]['id'] ) );
			unset( $this->data[$loginName] );
		}
	}
	
	
	public function checkPwd( $admin , $password )
	{
		//return true;
		if( $this->data[$admin]['password'] == md5( md5( $password ).'#@$3FW4#' ) )
		{
			return true;
		}
		return false;
	}
	
	public function getInfo( $adminName )
	{
		return $this->data[$adminName];
	}
	
}
