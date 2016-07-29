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

class Data_User_Profile extends Data_Abstract
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
	public function __construct( $userId , $lock = false , $isNewUser = false   )
	{
		$this->dbColumns = array(
			'user_profile' => array(
				'columns' => array(
					//loginDays连续登录，  totalLogins总登录天数
					'loginTime','registerTime','loginTimes','loginName' ,
					 'password' , 'nickName' , 'loginDays' , 'refer' , 'totalLogins' , 'loginRewardTime' , 'platform'
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		parent::__construct( $userId , 'user_profile' , $lock  );
		
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
		
		if( $lock && !self::$singletonObjects[$userId]->isLocked() )
		{
			self::$singletonObjects[$userId] = new self( $userId , $lock , $isNotReadData , $isMock );
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
			'loginTime' => $data['loginTime'],
			'registerTime' => $data['registerTime'],
			'loginTimes' => $data['loginTimes'],
			'loginDays' => $data['loginDays'],
			'loginName' => $data['loginName'],
			'password' => $data['password'],
			'nickName' => $data['nickName'],
			'refer' => $data['refer'],
			'totalLogins' => $data['totalLogins'] ? $data['totalLogins'] : 0 ,
			'loginRewardTime' => $data['loginRewardTime'] ? $data['loginRewardTime']  : 0,
			'platform' => $data['platform'],
		);
		return $returnData;
	}
	
	protected function formatFromDBData( $table , $data )
	{
		$returnData = array(
				'uid' => $data[0],
				'loginTime' => $data[1],
				'registerTime' => $data[2],
				'loginTimes' => $data[3],
				'loginDays' => $data[4],
				'loginName' => $data[5],
				'password' => $data[6],
				'nickName' => $data[7],
				'refer' => $data[8],
				'totalLogins' => $data[9],
				'loginRewardTime' => $data[10],
				'platform' => $data[11],
		);
		return $returnData;
	}
	
	/**
	 * 如果从数据库获取到的数据为空，则初化初始值
	 * 其初始值为$this->data的数组格式
	 * @see Data_Abstract::emptyDataWhenloadFromDB()
	 */
	protected function emptyDataWhenloadFromDB( $table )
	{	
		
		$platform = Common::getConfig( "pf");
		$data['uid'] = $this->userId;
		$data['loginTime'] = 0;
		$data['registerTime'] = $_SERVER['REQUEST_TIME'];
		$data['loginTimes'] = 0;
		$data['loginDays'] = 1;
		$data['loginName'] = "";
		$data['password'] = "";
		$data['nickName'] = "测试号";
		$data['refer'] = $_REQUEST['refer'] ? $_REQUEST['refer'] : 0;
		$data['totalLogins'] = 0;
		$data['loginRewardTime'] = 0;
		$data['platform'] = $platform;
	
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_ADD , $data );
		$this->isNewUser = true;
		return $data;
	}
	
	/**
	 * 设置用户信息
	 * @param unknown_type $userInfo
	 */
	public function setUserInfo( $userInfo )
	{
		if( $userInfo['nickName'] )
		{
			$this->data['nickName'] = $userInfo['nickName'];
		}
		
		$userInfo['loginName'] = $userInfo['loginName'] ? $userInfo['loginName'] : $_GET['loginName'];
		
		if( $userInfo['loginName'] )
		{
			$this->data['loginName'] = $userInfo['loginName'];
		}
		
		if( $userInfo['password'] )
		{
			$this->data['password'] = $userInfo['password'];
		}
		
		if( $userInfo['loginTime'] )
		{
			$this->data['loginTime'] = $_SERVER['REQUEST_TIME'];
			$this->data['loginTimes'] += 1;
		}
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	
	public function setLoginDays( $days )
	{
		$this->data['loginDays'] = $days;
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	public function addTotalLogins()
	{
		$this->data['totalLogins'] += 1;
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	/**
	 * 设置登录领奖时间
	 */
	public function setLoginRewardTime()
	{
		$this->data['loginRewardTime'] = $_SERVER['REQUEST_TIME'];
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	/**
	 * 设置分流下载来源
	 * @param int $referCode 分流标志
	 */
	public function setDownRefer( $referCode )
	{
		$this->data['refer'] = $referCode;
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	/**
	 * 设置平台
	 * @param string $pf
	 */
	public function setPlatform( $pf  )
	{
		$this->data['platform'] = $pf;
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	/**
	 * 更新登录时间
	 */
	public function updateLoginTime()
	{
		$this->data['loginTime'] = $_SERVER['REQUEST_TIME'];
		$this->data['loginTimes'] += 1;
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	

	/**
	 * 数据修复
	 */
	public function recoveryData()
	{
		$this->updateToDb( 'user_profile' , self::DATA_ACTION_ADD ,  $this->data );
	}
	
	
	/**
	 * 是否新用户
	 */
	public function isNewUser()
	{
		//和注册时间同一天，并且第一次登录
		if( date( "Ymd" , $this->data['registerTime'] ) == date( "Ymd" , $_SERVER['REQUEST_TIME'] ) 
			&& $this->data['loginTimes'] <= 1
		)
		{
			return true;
		}
		return false;
	}
	
	
}
