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
class Data_User_Info extends Data_Abstract
{
	/**
	 * 单例对象
	 * @var	Data_User_Info[]
	 */
	protected static $singletonObjects;
	/**
	 * 结构化对象
	 * @param	string $userId	用户ID
	 * @param	boolean $lock	是否加锁（需要写的话一定要加锁）
	 */
	public function __construct( $userId , $lock = false  )
	{
		$this->tablename = 'user_info';
		$this->dbColumns = array(
			'user_info' => array(
				'columns' => array(
					'exp' , 'level' , 'coin' , 'gold', 'leaderCard' ,
					'stamina' , 'staminaUpdateTime' , 'leaderShip',
				    'country' ,  'firstCharge' , 'gachaPoint' , 'maxFriendNum',
					'maxCardNum' , 'maxStamina' , 'newbieStep' , 'inviteCode',
					'firstDraw','freeDrawTime','levelUpTime' , 'buff'
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		parent::__construct( $userId , $this->tablename , $lock  );
		$this->getLevel();
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_User_Info
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
		if( $table == 'user_info' && $data )
		{
			foreach ( $data as $key => $value )
			{
				if( in_array( $key , $this->dbColumns['user_info']['columns'] ) )
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
			'exp' => $data[1],
			'level' => $data[2],
			'coin' => $data[3],	//冲值币
			'gold' => $data[4],	//金币
			'stamina' => $data[5],	//耐力
			'staminaUpdateTime' => $data[6],//耐力回复时间
			'leaderShip' => $data[7],	//统御力
			'country' => $data[8],	//国家
			'firstCharge' => $data[9],	//是否第一次付费
			'gachaPoint' => $data[10],//援军点数
			'maxCardNum' => $data[11],	//最大武将数
			'maxFriendNum' => $data[12],	//最大好友数
			'maxStamina' => $data[13],	//最大耐力上限
			'newbieStep' => $data[14],
			'inviteCode' => $data[15],
			'leaderCard' => $data[16],	//主将卡唯一ID
			'firstFreeDraw' => $data[17],	//是否第一次免费抽将
			'firstChargeDraw' => $data[18],	//是否第一次付费抽将
			'freeDrawTime' => $data[19],
			'levelUpTime' => $data[20],
			'buff' => $data[21],
		);
		return $formatedData;
	}

	protected function emptyDataWhenloadFromDB( $table )
	{	
		static $inviteCodes = array();
		if( !isset( $inviteCodes[$this->userId] ) )
		{
			$inviteCode = Data_InviteCode_Model::makeCode( $this->userId );
			$inviteCodes[$this->userId] = $inviteCode;
		}
		$data = array(
			'exp' => 0,
			'level' => 1,
			'gold' => 50000,	//金币
			'coin' => 30,	//冲值币
			'stamina' => 20,	//耐力
			'staminaUpdateTime' => 0,//耐力回复时间
			'leaderCard' => 0,	//主将卡唯一ID
			'leaderShip' => 20,	//统御力
			'country' => 0,	//国家
			'firstCharge' => 0,	//第一次付费
			'gachaPoint' => 0,//援军点数
			'maxCardNum' => 20,	//最大武将数
			'maxFriendNum' => 15,	//最大好友数
			'maxStamina' => 20,	//最大耐力上限
			'newbieStep' => 0,
			'firstFreeDraw' => 0,	//是否第一次免费抽将
			'firstChargeDraw' => 0,	//是否第一次付费抽将
			'inviteCode' => $inviteCodes[$this->userId],
			'freeDrawTime' => 0,
			'levelUpTime' => 0,	
			'buff' => '',		
		);
		
	
		
		$this->updateToDb( 'user_info' , self::DATA_ACTION_ADD , $data );
		return $data;
	}
	
	/**
	 * 数据修复
	 */
	public function recoveryData()
	{
		$this->updateToDb( 'user_info' , self::DATA_ACTION_ADD ,  $this->data );
	}
	
	/**
	 * 设置用户信息
	 * @param unknown $userInfo
	 */
	public function setUserInfo( $userInfo )
	{
		$this->data = $userInfo;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE , $this->data );
	}
	
	public function setNewbie( $step )
	{
		if( $step >= 0 )
		{
			$this->data['newbieStep'] = $step;
			$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		}
	}

	public function setCountry( $country )
	{
	     $this->data['country'] = $country;
             $this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );		
	}

	
	/**
	 * 增加耐力上限
	 * @param unknown $addNum
	 */
	public function addMaxStamina( $addNum )
	{
		$this->data['maxStamina'] = $addNum;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	/**
	 * 增加统于力
	 * @param unknown $addNum
	 */
	public function addLeaderShip( $addNum )
	{
		$this->data['leaderShip'] = $addNum;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	/**
	 * 设置主将ID
	 * @param int $leaderId
	 */
	public function setLeaderCard( $cardId )
	{
		$this->data['leaderCard'] = $cardId;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	/**
	 * 改变充值币
	 * @param unknown_type $coin
	 */
	public function changeCoin( $coin )
	{
		if( $coin >= 0 )
		{
			//$payLog = new ErrorLog( "changeCoin" );
			//$payLog->addLog(  "uid:".$this->userId."addCoin:".$coin."request:".json_encode( $_REQUEST )  );
			$this->data['coin'] += $coin;
		}
		else
		{
			$this->data['firstCharge'] += 1;
			if( $this->data['coin'] + $coin < 0 )
			{
				return false;
			}
			$this->data['coin'] += $coin;
		}

		
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE , $this->data );
		return true;
	}
	
	/**
	 * 改变金币
	 * @param unknown_type $gold
	 */
	public function changeGold( $gold )
	{
		if( $gold >= 0 )
		{
			$this->data['gold'] += $gold;
		}
		else
		{
			if( $this->data['gold'] + $gold < 0 )
			{
				return false;
			}
			$this->data['gold'] += $gold;
		}
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		
		//数据统计
		if( $gold > 0 )
		{
			Stats_Model::incomeGold( $this->userId , $gold );
		}
		else 
		{
			Stats_Model::consumeGold( $this->userId , $gold );
		}
		return true;
	}
	
	
	/**
	 * 改变耐力
	 * @param unknown_type $gold
	 */
	public function changeStamina( $stamina )
	{
		if( $stamina >= 0 )
		{
			$this->data['stamina'] += $stamina;
		}
		else
		{
			if( $this->data['stamina'] + $stamina < 0 )
			{
				return false;
			}
			
			if( $this->data['stamina'] >= $this->data['maxStamina'] )
			{
				$this->data['staminaUpdateTime'] = $_SERVER['REQUEST_TIME'];
			}
			
			$this->data['stamina'] += $stamina;
		}
		
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		return true;
	}
	
	
	public function setStaminaUpdateTime( $time )
	{
		$this->data['staminaUpdateTime'] = $time;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	
	/**
	 * 加经验
	 */
	public function setExp( $exp )
	{
		$this->data['exp'] = $exp;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	public function getExp()
	{
		return $this->data['exp'] ;
	}
	
	
	/**
	 *  设置等级
	 */
	public function setLevel( $level )
	{
		$this->data['level'] = $level;
		$this->data['levelUpTime'] = $_SERVER['REQUEST_TIME'];
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE , $this->data  );
	}
	
	public function getLevel()
	{
		$levelConfig = Common::getConfig( "level" );
		foreach( $levelConfig as $lv => $lvInfo )
		{
			if( $lvInfo['exp'] == $this->data['exp'] )
			{
					$this->data['level'] = $lv;
					break;
			}
			elseif( $lvInfo['exp'] > $this->data['exp'] )
			{
				$this->data['level'] = $lv-1;
				break;
			}
		}
		return $this->data['level'];
	}
	
	
	/**
	 * 改成援军点数
	 */
	public function changeGachaPoint( $point )
	{
		if( $point >= 0 )
		{
			$this->data['gachaPoint'] += $point;
		}
		else
		{
			if( $this->data['gachaPoint'] + $point < 0 )
			{
				return false;
			}
			$this->data['gachaPoint'] += $point;
		}
		
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		return true;
	}
	
	/**
	 * 增加武将数量上限
	 * @param unknown_type $num
	 */
	public function addCardNum( $num )
	{
		$this->data['maxCardNum'] += $num;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE , $this->data );
	}
	/**
	 * 增加好友数量上限
	 * @param unknown_type $num
	 */
	public function addFriendNum( $num )
	{
		$this->data['maxFriendNum'] += $num;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		return $this->data['maxFriendNum'];
	}
	
	/**
	 * 增加好友数量上限
	 * @param unknown_type $num
	 */
	public function setFriendNum( $num )
	{
		if( $num > 100 )
		{
			$num = 100;
		}
		$this->data['maxFriendNum'] = $num;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		return $this->data['maxFriendNum'];
	}
	
	/**
	 * 体力回满
	 */
	public function recoverStaminaFull()
	{
		$this->data['stamina'] = $this->data['maxStamina'];
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	/**
	 * 设置第一次免费抽将
	 * 等于1说明不是第一次
	 */
	public function setFirstFreeDraw()
	{
		$this->data['firstFreeDraw'] = 1;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE , $this->data  );
	}
	
	
	/**
	 * 设置第一次付费抽将
	 * 等于1说明不是第一次
	 */
	public function setFirstChargeDraw()
	{
		$this->data['firstChargeDraw'] = 1;
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE , $this->data  );
	}
	
	public function freeDrawSuperCard()
	{
		//三天抽一次
		//259200
		if( $_SERVER['REQUEST_TIME'] -  $this->data['freeDrawTime'] >=  259200  )
		{
			$this->data['freeDrawTime'] = $_SERVER['REQUEST_TIME'];
			return true;
		}
		return false;
	}
	
	/**
	 * 增加buff
	 * @param unknown $type buffId BUFFID
	 * @param unknown $time  buff持续时间段
	 */
	public function addBuff( $type , $value,   $time )
	{
		$hasTime = 0;
		$buff = json_decode( $this->data['buff'] , true );
		if( $buff[$type][$value] > 0 )
		{
			//看上一个BUFF还有多久时间消失
			$hasTime = $buff[$type][$value] - $_SERVER['REQUEST_TIME'];
			$hasTime = $hasTime < 0 ? 0 : $hasTime;	
		}
		$buff[$type][$value] = $_SERVER['REQUEST_TIME'] + $time + $hasTime;
		$this->data['buff'] = json_encode( $buff );
		$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
	}
	
	/**
	 * 清除buff
	 */
	public function clearBuff()
	{
		$update = 0 ;
		$buff = json_decode( $this->data['buff'] , true );
		if( $buff  )
		{
			foreach ( $buff as $type  => $typeInfo )
			{
				foreach ( $typeInfo as $value => $lastTime )
				{
					if( $lastTime < $_SERVER['REQUEST_TIME'] )
					{
						unset(  $buff[$type][$value] );
						$update = 1;
					}
				}
				
				if(  empty( $buff[$type] ))
				{
					unset(  $buff[$type] );
				}
			}
		}
		
		if( $update == 1 )
		{
			$this->data['buff'] = json_encode( $buff );
			$this->updateToDb( 'user_info' , self::DATA_ACTION_UPDATE ,  $this->data  );
		}
		
	}
	
	public function getBuffs()
	{
		return json_decode( $this->data['buff'] , true );
	}
	
	
}
