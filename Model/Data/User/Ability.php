<?php
/**
 * 重构User Model
 * 实现用户基本属性，经验相关的所有功能
 * 
 * @name Ability.php
 * @author Lucky
 * @modifier Roy
 * @since 2011-3-9
 */
if( !defined( 'IN_INU' ) )
{
	return;
}

class Data_User_Ability extends Data_Abstract
{
	/**
	 * 基本属性点类型（HP）
	 * @var	int
	 */
	const POINT_TYPE_STRONG = 1;
	
	/**
	 * 基本属性点类型（攻击力）
	 * @var	int
	 */
	const POINT_TYPE_BRAWN = 2;
	
	/**
	 * 基本属性点类型（敏捷度）
	 * @var	int
	 */
	const POINT_TYPE_DEXTERITY = 3;
	
	/**
	 * @var	int
	 */
	const POINT_TYPE_MP = 4;
	
	/**
	 * 单例对象
	 * @var	User_Model[]
	 */
	protected static $singletonObjects;
	
	/**
	 * 是否为新用户
	 */
	private $isNewUser;
	
	/**
	 * 结构化对象
	 * @param	int $userId	用户ID
	 * @param	boolean $lock	是否加锁（需要写的话一定要加锁）
	 */
	public function __construct( $userId , $lock = false , $isNewUser = false  , $isMock = false )
	{
		$this->dbColumns = array(
			'user_ability' => array(
				'columns' => array(
					'exp' , 'addtionExp' , 'strong' , 'brawn' , 'dexterity' , 'canUseAttributePoint'  , 'gold' , 'level' 
				) ,
				'isNeedFindAll' => false ,
			) ,
		);
		$this->isNewUser = $isNewUser;
		parent::__construct( $userId , 'user_ability' , $lock , $isNewUser , $isMock );
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_User_Ability
	 */
	public static function & getInstance( $userId , $lock = false , $isNotReadData = false , $isMock = false )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId , $lock , $isNotReadData , $isMock );
		}
		
		if( $lock && !self::$singletonObjects[$userId]->isLocked() )
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
	 * 更改可使用技能点GM工具专用
	 * @return	int
	 */
	public function setCanUseAttributePoint( $point )
	{
		if( $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_CAN_USE_ATTRIBUTE_POINT_ERROR );
		}
		if( $this->data['canUseAttributePoint'] != $point )
		{
			$this->data['canUseAttributePoint'] = $point;
			$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
		}
	}
	
	/**
	 * 获取可加点数
	 * @return	int
	 */
	public function getCanUseAttributePoint()
	{
		return $this->data['canUseAttributePoint'];
	}
	
	/**
	 * 更改可加点数
	 * @param	int	$point	点数
	 */
	public function changeCanUseAttributePoint( $point )
	{
		if( $this->data['canUseAttributePoint'] + $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_CAN_USE_ATTRIBUTE_POINT_ERROR );
		}
		
		if( $point != 0 )
		{
			$this->data['canUseAttributePoint'] += $point;
			if(  $this->data['canUseAttributePoint'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
			}
		}
	}
	
	/**
	 * 设置强壮属性
	 * @param	int $point	属性值
	 * @throws Data_User_Exception
	 */
	public function setStrong( $point )
	{
		if( $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_NOT_ENOUGH_STRONG );
		}
		
		if( $this->data['strong'] != $point )
		{
			$this->data['strong'] = $point;
			$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
		}
	}
	
	/**
	 * 更改强壮属性
	 * @param	int $point	属性值
	 * @throws Data_User_Exception
	 */
	public function changeStrong( $point )
	{
		if( $this->data['strong'] + $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_NOT_ENOUGH_STRONG );
		}
		
		if( $point != 0 )
		{
			$this->data['strong'] += $point;
			if( $this->data['strong'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
			}
		}
	}
	
	/**
	 * 获取强壮属性
	 * @return	int
	 */
	public function getStrong()
	{
		return $this->data['strong'];
	}
	
	/**
	 * 设置腕力属性
	 * @param	int $point	属性值
	 * @throws Data_User_Exception
	 */
	public function setBrawn( $point )
	{
		if( $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_NOT_ENOUGH_BRAWN );
		}
		
		if( $this->data['brawn'] != $point )
		{
			$this->data['brawn'] = $point;
			$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
		}
	}
	
	/**
	 * 更改腕力属性
	 * @param	int $point
	 */
	public function changeBrawn( $point )
	{
		if( $this->data['brawn'] + $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_NOT_ENOUGH_BRAWN );
		}
		
		if( $point != 0 )
		{
			$this->data['brawn'] += $point;
			if( $this->data['brawn'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
			}
		}
	}
	
	/**
	 * 获取腕力属性
	 * @return	int
	 */
	public function getBrawn()
	{
		return $this->data['brawn'];
	}
	
	/**
	 * 设置敏捷属性
	 * @param	int $point	属性值
	 * @throws Data_User_Exception
	 */
	public function setDexterity( $point )
	{
		if( $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_NOT_ENOUGH_DEXTERITY );
		}
		
		if( $this->data['dexterity'] != $point )
		{
			$this->data['dexterity'] = $point;
			$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
		}
	}
	
	/**
	 * 更改敏捷属性
	 * @param	int $point
	 */
	public function changeDexterity( $point )
	{
		if( $this->data['dexterity'] + $point < 0 )
		{
			throw new Data_User_Exception( Data_User_Exception::STATUS_NOT_ENOUGH_DEXTERITY );
		}
		
		if( $point != 0 )
		{
			$this->data['dexterity'] += $point;
			if( $this->data['dexterity'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
			}
		}
	}
	
	/**
	 * 获取敏捷属性
	 * @return	int
	 */
	public function getDexterity()
	{
		return $this->data['dexterity'];
	}
	
	/**
	 * 改变附加经验
	 * @param	int	$exp	附加经验值
	 */
	public function changeAdditionExp( $exp )
	{
		if( $exp != 0 )
		{
			$this->data['addtionExp'] += $exp;
			if( $this->data['addtionExp'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
			}
		}
	}
	
	/**
	 * 获取附加经验
	 * @return	int
	 */
	public function getAdditionExp()
	{
		return $this->data['addtionExp'];
	}
	
	/**
	 * 改变经验
	 * @param	int	$exp	经验
	 */
	public function changeExp( $exp )
	{
		if( $exp != 0 )
		{
			$this->data['exp'] += $exp;
			if ( $this->data['exp'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
			}
		}
	}
	
	/**
	 * 获取金币
	 * @return	int
	 */
	public function getGold()
	{
		if( !isset( $this->data['gold'] ) ) 
		{
			return 0;
		}
		return $this->data['gold'];
	}
	
	/**
	 * 设置金币
	 * @param	int $gold
	 */
	public function setGold( $gold )
	{
		$beSetGold = $gold;
		if( $gold < 0 )
		{
			$beSetGold = 0;
		}
		
		if( $this->data['gold'] != $beSetGold )
		{
			$this->data['gold'] = $beSetGold;
			$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data );
		}
	}
	
	/**
	 * 改变金币
	 * @param	int	$gold	金币
	 */
	public function changeGold( $gold )
	{
		if( $gold != 0 )
		{
			$this->setGold( $this->data['gold'] + $gold );
		}
	}

	/**
	 * 改变等级
	 * @param	int	$level	等级
	 */
	public function changeLevel( $level )
	{
		if( $level != 0 )
		{
			$this->data['level'] += $level;
			if( $this->data['level'] >= 0 )
			{
				$this->updateToDb( 'user_ability' , self::DATA_ACTION_UPDATE , $this->data);
			}
		}
	}
	
	/**
	 * 获取等级
	 * @return	int
	 */
	public function getLevel()
	{
		if( !isset( $this->data['level'] ) ) 
		{
			return 0;
		}
		return $this->data['level'];
	}
	
	/**
	 * 获取用户当前经验值
	 * @return	int
	 */
	public function getExp()
	{
		if( !isset( $this->data['exp'] ) ) 
		{
			return 0;	
		}
		return $this->data['exp'];
	}
	
	protected function formatFromDBData( $table , $data )
	{
		return $data;
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
			'exp' => $data['exp'],
			'addtionExp' => $data['addtionExp'],
			'strong' => $data['strong'],
		    'brawn' =>  $data['brawn'],
			'dexterity' => $data['dexterity'],
			'canUseAttributePoint' => $data['canUseAttributePoint'],
			'gold' => $data['gold'],
			'level' => $data['level'],
		);
		return $returnData;
	}
	
	protected function emptyDataWhenloadFromDB( $table )
	{
		
		if( !$this->isNewUser )
		{
			if( $this->isLocked() )
			{
				$this->__destruct();
			}
			throw new User_Exception( User_Exception::STATUS_USER_NOT_EXIST );
		}
		//初始化用户数据
		$configData = Common::getConfig( "initialUserInfo" );
		$data = array(
			'exp' => $configData['exp'] ,
			'addtionExp' => 0 ,
			'strong' => $configData['strong'] ,
			'brawn' => $configData['brawn'] ,
			'dexterity' => $configData['dexterity'] ,
			'canUseAttributePoint' => $configData['canUseAttributePoint'] ,
			'gold' => $configData['gold'] ,
			'level' => $configData['level'] ,
		);
		$this->updateToDb( 'user_ability' , self::DATA_ACTION_ADD , $data );
		return $data;
	}
}