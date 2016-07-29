<?php
/**
 * 首先对比mongo与mysql的数据条数，如果不相同，就同步一次
 * @author liuchangbin
 *
 */
class Cli_SyncData extends Stats_Base
{
	private static $fd;	
	
	private static $sids = array(
		10 => 'myHero',
		11 => 'myHero_2',
		12 => 'myHero_91',
		13 => 'myHero_dl',
		14 => 'myHero_jf',
		15 => 'myHero_uc',
		16 => 'myHero_uuc',
		17 => 'myHero_wl',
		18 => 'myHero_xm',
		20 => 'myHero_91_2',
		21 => 'myHero_91_ios',
		22 => 'myHero_br',
		23 => 'myHero_pp',
		25 => 'myHero_ios',	
	);
	
	private static $refers = array
	(
		"1"   => array( "id" => "DL" ,     "name" => "当乐" ),
		"4"   => array( "id" => "crossmo" ,"name" => "十字猫" ),
		"24"  => array( "id" => "115" , 	"name" => "115渠道" ),
		"25"  => array( "id" => "ud" , 		"name" => "" ),
		"26"  => array( "id" => "qc" , 		"name" => "" ), //千尺
		"100" => array( "id" => "uge" , 	"name" => "官网" ),
		"101" => array( "id" => "UC" , 		"name" => "" ),
		"102" => array( "id" => "XM" , 		"name" => "小米" ),
		"103" => array( "id" => "WL" , 		"name" => "瓦力" ),
		"120" => array( "id" => "91DJ" , 	"name" => "91点金" ),
		"121" => array( "id" => "91DJ-1" , 	"name" => "91点金1" ),
		"130" => array( "id" => "BaoRuan" ,"name" => "宝软" ),
		"131" => array( "id" => "51" ,	 	"name" => "" ),
		"136" => array( "id" => "tw" ,		"name" => "" ),
		"360" => array( "id" => "360" , 	"name" => "" ),
		"191" => array( "id" => "91_ios" , 	"name" => "" ),
		"200" => array( "id" => "uge_ios" ,"name" => "官网ios" ),
		"192" => array( "id" => "zq" , 		"name" => "掌趣" ),
		"94"  => array( "id" => "JF" , 		"name" => "机锋" ),
		"93"  => array( "id" => "DL" , 		"name" => "当乐" ),
		"189" => array( "id" => "TY" , 		"name" => "天翼" ),
		"117" => array( "id" => "UUC" , 	"name" => "悠悠村" ),	
	);
	
	
	public static function sync()
	{
		self::initBackupData();	
		foreach ( self::$sids as $sid => $info )
		{
			self::syncUser( 1001 , $sid );
			self::syncOrder( 1001 , $sid );
			usleep( mt_rand( 100000, 900000));
		}
	}
	
	
	public static function initBackupData()
	{
		$cmd = "rm -rf /data/edacData*";
		exec( $cmd );
		$cmd = "wget http://211.144.68.31/edacData/edacData.tar.gz -P /data/";
		exec( $cmd );
		$cmd = "tar zxvf /data/edacData.tar.gz ";
		exec( $cmd );
	}
	
	
	public static function syncUser( $appId , $sid )
	{
		$file = 'user_'.$appId."_".$sid;
		self::$appId = $appId;
		self::$sid = $sid;
		
		if( !self::$fd )
		{
			$dataDir = "/home/www/wwwroot/edacData/";
			$file = $dataDir.$file;
			self::$fd = fopen( $file , "r" );
		}
		
		$i = 0;
		while( !feof(self::$fd) )
		{
		  	$line = fgets(self::$fd);
		  	if( $i > 0 )
		  	{
		  		self::syncUserLine( $line );
		  	}
		  	$i++;
		}

		fclose(self::$fd);
		self::$fd = null;
	}

	
	public static function syncOrder( $appId , $sid )
	{
		$file = 'order_'.$appId."_".$sid;
		self::$appId = $appId;
		self::$sid = $sid;
	
		if( !self::$fd )
		{
			$dataDir = "/home/www/wwwroot/edacData/";
			$file = $dataDir.$file;
			self::$fd = fopen( $file , "r" );
		}
	
		$i = 0;
		while( !feof( self::$fd ) )
		{
			$line = fgets( self::$fd );
			if( $i > 0 )
			{
				self::syncOrderLine( $line );
			}
			$i++;
		}
	
		fclose(self::$fd);
		self::$fd = null;
	}
	
	
	/**
	 * 同步每一条记录
	 * @param unknown $line
	 */
	public static function syncUserLine( $line )
	{
		if( empty( $line ))
		{
			return;
		}
		$data = json_decode( $line , true );
		if( !$data['uid'] )
		{
			return;
		}
		
		//查询
		$query = array(
			'uid' => (int)$data['uid'],
		);
		
		self::index( 'newUser' , array( 'uid' => 1 ));
		//self::remove( "newUser" , $query );
		
		$mongoData = self::findOne( 'newUser' , $query );
		if( !$mongoData )
		{
			$record  = array(
				'appId' => self::$appId,
				'sid' => self::$sid,
				'uid' => (int)$data['uid'],
				'nickName' => strval( $data['nickName'] ),
				'downRefer' => self::getRefer( $data['refer']),
				'level' => $data['level'] ? intval( $data['level'] ) : 1,
				'newbie' =>  $data['newbieStep'] ? intval( $data['newbieStep'] ) : 0,
				'mac' => "",
				'ip' => "",
				'registerTime' => (int)$data['registerTime'],
				'serverTime' => (int)$data['registerTime'],
				
			);
			self::add(  "newUser" , $record );
			echo "add";
			print_r( $record );
		}	
	}
	
	
	/**
	 * 同步每一条记录
	 * @param unknown $line
	 */
	public static function syncOrderLine( $line )
	{
		if( empty( $line ))
		{
			return;
		}
		$data = json_decode( $line , true );
		if( !$data['uid'] )
		{
			return;
		}
		
		//查询
		$query = array(
			'orderId' => (string)$data['id'],
		);
	
		self::index( 'recharge' , array( 'orderId' => 1 ));
		//self::remove( "newUser" , $query );
		
		$rmb = $data['price'];
		if( $rmb > 0 && $rmb < 1 )
		{
			$rmb  =  $rmb * 100;
		}
		$coin = $rmb;
		
	
		$mongoData = self::findOne( 'recharge' , $query );
		if( !$mongoData )
		{
			$record  = array(
				'appId' => self::$appId,
				'sid' => self::$sid,
				'uid' => (int)$data['uid'],
				'nickName' => strval( $data['nickName'] ),
				'downRefer' => self::getRefer( $data['refer']),
				'orderId' => $data['id'] ? strval( $data['id'] ) : 1,
				'rmb' => $rmb,
				'coin' => $coin,
				'mac' => "",
				'ip' => "0.0.0.0",
				'payType' => strval($data['payType']),
				'registerTime' => (int)$data['registerTime'],
				'serverTime' => (int)$data['addTime'],
			);
			self::add(  "recharge" , $record );
			echo "add";
			print_r( $record );
		}
	}
	
	
	
	private static function getRefer( $code )
	{
		foreach ( self::$refers as $key => $info )
		{
			if( $code == intval( $key ) )
			{
				return strval( $info['name']);
			}
		}
		return $code;
	}
	
	
	
	
	
}
//Cli_SyncData::sync();