<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 邀请码
 * @name model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_InviteCode_Model extends Data_Abstract
{
        /**
         * 单例对象
         * @var User_Model[]
         */
        protected static $singletonObjects;
        /**
         * 结构化对象
         * @param       string $userId  用户ID
         * @param       boolean $lock   是否加锁（需要写的话一定要加锁）
         */
        public function __construct( $userId , $lock = false  )
        {

                $this->dbColumns = array(
                        'invite_code' => array(
                                'columns' => array(
                                       'id' , 'isUsed' , 'belongUid'
                                ) ,
                                'isNeedFindAll' => false ,
                        ) ,
                );

                parent::__construct( $userId , 'invite_code' , $lock  );

        }

        /**
         * 获取实例化
         * @param       int $userId     用户ID
         * @return      Data_InviteCode_Model
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
         * @param       array $table    表名
         * @param       array $data             数据
         * @return      array
         */
        protected function formatToDBData( $table , $data )
        {

                $returnData = array(
                        'id' => $data['id'],
                        'isUsed' => $data['isUsed'],
                        'belongUid' => $data['belongUid'],
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
                      
                  $returnData = array(
                      'id' => $data[1],
                      'isUsed' => $data[2],
                      'belongUid' => $data[3],
                   );
                        
                }
                return $returnData;
        }



        protected function emptyDataWhenloadFromDB( $table )
        {
                return $this->data;
        }

	/**
	 * 产生一个邀请码
	 */
	public static function makeCode( $userId )
	{
		
		$dbEngine = Common::getDB( $userId );
		$cache = Common::getCache();
		
		$code = 0;
		while( !$code )
		{
			$code = 10000000 + mt_rand( 1 , 80000000 );
		
			
			if( $cache->get( "invite_code_$code") )
			{
				$code = 0;
			}
			else 
			{
				$sql = "select * from  invite_code where id='{$code}'";
				$record = $dbEngine->findQuery( $sql );
				if( count( $record ) > 0 )
				{
					$code = 0;
				}
				else 
				{
					$cache->set( "invite_code_$code" , $userId );
				}
			} 
			
		}
		self::getInstance( $userId , true )->setUserCode( $code );
		return $code;
	}
	
	
	public function setUserCode( $code )
	{
		$this->data = array(
				'id' => $code,
				'isUsed' => 0,
				'belongUid' => $this->userId,
		);
		$this->updateToDb( 'invite_code' , self::DATA_ACTION_ADD , $this->data );
	}
	
	
	/**
	 * 获取邀请码信息
	 * @param unknown $code
	 */
	public static function getCodeBelong( $code )
	{
		$cache = Common::getCache();
		$userId = $cache->get( "invite_code_$code");
		if( $userId  )
		{
			return $userId;
		}
		
		$dbEngine = Common::getDB( 1 );
		$sql = "select uid from  invite_code where id='{$code}'";
		$record = $dbEngine->findQuery( $sql );
		return $record[0][0];
	}
	
	
}
