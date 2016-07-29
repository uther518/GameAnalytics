<?php

if( !defined( 'IN_INU' ) )
{
	return;
}

/**
 * 运行日志模块
 * @author	wzhzhang , Luckyboys
 */
class Helper_RunLog
{
	/**
	 * 日志
	 * @var	string
	 */
	protected static $log = array();
	
	/**
	 * 日志客户端
	 * @var	Helper_RunLog
	 */
	protected static $client = null;
	
	/**
	 * 日志状态
	 * @var int
	 */
	protected static $logStatus = 0;
	
	/**
	 * 日志保存地址
	 * @var	string
	 */
	protected static $logPath;
	
	/**
	 * 构建运行日志
	 */
	protected function __construct()
	{
		;
	}
	
	/**
	 * 获取执行效率日志实例
	 * @return	Helper_RunLog
	 */
	public static function getInstance()
	{
		if( !self::$client )
		{
			$config = & Common::getConfig( 'runLog' );
			self::$logStatus = $config['status'];
			self::$logPath = $config['logPath'];
			self::$client = new Helper_RunLog();
		}
		
		return self::$client;
	}
	
	/**
	 * 记录
	 * @param string $method	//模块
	 * @param string $message	//日志信息
	 */
	public function addLog( $method , $message = '' )
	{
		if( !self::$logStatus )
			return ;
			
		self::$log[] = array
		(
			'time' => microtime( true ) ,
			'message' => $message ,
			'module' => $method ,
		);
	}
	
	/**
	 * 将运行日志写入文件
	 *
	 */
	public function __destruct()
	{
		if( !self::$logStatus )
			return ;
		
		$data = $this->getRunData();
		
		$logDir = self::$logPath .'runLog/'. date( "Y-m-d" ) .'/';
		if( !file_exists( $logDir ) )
		{
			@mkdir( $logDir , 0777 , true );
		}
		
		if( isset( $GLOBALS['startTime'] ) && isset( $GLOBALS['endTime'] ) )
		{
			$data .= "\tTotal Run Time: ". sprintf( "%d us.\n" , ( $GLOBALS['endTime'] - $GLOBALS['startTime'] ) * 1000000 );
		}
		
		file_put_contents( $logDir . date( "H" ) , $data , FILE_APPEND );
	}
	
	public function & getRunData()
	{
		if( !self::$logStatus )
			return ;
			
		$lastTime = 0;
		$data = array();
		foreach ( self::$log as $item )
		{
			if( $lastTime > 0 )
			{
				$runTime = sprintf( '%dus. ' , ( $item['time'] - $lastTime ) * 1000000 );
				$data[] = "\t{$item['time']}\t{$runTime}\t{$item['module']}:{$item['message']}";
			}
			else
			{
				$data[] = "\t{$item['time']}\t{$item['module']}:{$item['message']}";
			}
			$lastTime = $item['time'];
		}
		
		$data = date( "i:s" ) . "\t{$_GET['method']}\n" . implode( "\n" , $data ) . "\n";
		
		return $data;
	}
}