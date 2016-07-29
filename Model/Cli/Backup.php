<?php
/**
 * 首先对比mongo与mysql的数据条数，如果不相同，就同步一次
 * @author liuchangbin
 *
 */
class Cli_Backup
{
	private static $startTime;
	
	private static  $endTime;
	
	private static $appId;
	
	private static $sid;
	
	private static $dbName;
	
	
	private static $sids = array(
		10 => 'myHero',
		11 => 'myhero_2',
		12 => 'myhero_91',
		13 => 'myhero_dl',
		14 => 'myhero_jf',
		15 => 'myhero_uc',
		16 => 'myhero_uuc',
		17 => 'myhero_wl',
		18 => 'myhero_xm',
		20 => 'myhero_91_2',
		21 => 'myhero_91_ios',
		22 => 'myhero_br',
		23 => 'myhero_pp',
		25 => 'myhero_ios',	
	);
	
	
	public static function sync()
	{
		self::$startTime = strtotime( "20130118" );
		self::$endTime = strtotime( date( "Y-m-d") ) + 86400;
		self::$appId = 1001;

		$cmd = "rm -rf edacData/*";
                exec( $cmd );
		$cmd = "rm -rf edacData.tar.gz";
		exec( $cmd );


		foreach ( self::$sids as $sid => $dbname )
		{
			self::$sid = $sid;
			self::$dbName = $dbname;
			self::getUser();
			self::getOrder();
		}
		
		self::tar();
	}	
	
	/**
	 * 压缩打包
	 */
	private static function tar()
	{
		$cmd = "tar zcvf edacData/edacData.tar.gz edacData";
		exec( $cmd );
	}
	
	private static function init( $type )
	{
		$init = array(
				'appId' => self::$appId,
				'sid' => self::$sid,
				'time' => date( "Y-m-d H:i:s" ),
		);
		$file = "{$type}_".self::$appId."_".self::$sid;
		file_put_contents( "/home/www/wwwroot/edacData/$file", json_encode( $init )."\n"  );
	}
	
	private static function getUser()
	{
		
		self::init( 'user' );
		$sql = "select up.uid,loginTime,registerTime,nickName,refer,platform,level,newbieStep
                        from user_profile up inner join user_info ui on up.uid=ui.uid 
						where registerTime >= ".self::$startTime ." and  registerTime < ".self::$endTime."";
		self::fetchSql( $sql , 'user' );
	}
	
	
	private static function getOrder()
	{
		self::init( 'order' );	
		$sql = "select up.uid,nickName,refer,up.platform,id,price,addTime,o.platform as payType,registerTime  
                        from `order` o inner join  user_profile up  on up.uid=o.uid
						where addTime >= ".self::$startTime ." and  addTime < ".self::$endTime."";
		self::fetchSql( $sql , 'order' );
	}
	
	/**
	 * 查询所有数据，保存到web目录，以供下载
	 */
 	private static function fetchSql( $sql , $type )
    {   
	   $db = self::_getDB();
       $rs = mysql_query( $sql , $db );
       $file = "{$type}_".self::$appId."_".self::$sid;
       while(  $row = mysql_fetch_assoc( $rs ) ) 
       {   
       		file_put_contents( "/home/www/wwwroot/edacData/$file", json_encode( $row )."\n" , FILE_APPEND );
       }   
    }
    
	private static function _getDB()
	{
		$host = '192.168.1.2';
		$port = 3306;
		$user = 'root';
		$pass = '3@#5$F#4$F2!@';
		$link = mysql_connect( "$host:$port" , $user , $pass );
		mysql_query( "SET NAMES 'utf8'" );
		mysql_select_db( self::$dbName , $link );
		return $link;
	}	
}
Cli_Backup::sync();
