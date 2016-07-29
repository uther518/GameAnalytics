<?php

class ErrorLog
{
	/**
	 * 使用日志的模块名称
	 * @var	string
	 */
	protected $moduleName;
	
	/**
	 * 日志的目录
	 * @var	string
	 */
	protected $errorLogDir;
	
	/**
	 * 是否开启日志功能
	 * @var boolean
	 */
	protected $isOpenLog = -1;
	
	/**
	 * 虚拟线程ID
	 * @var	integer
	 */
	protected static $virtualThreadId = 0;
	
	/**
	 * 用户ID
	 * @var	int
	 */
	protected $userId = 0;
	
	/**
	 * 需要记录日志的用户ID
	 * @var	array
	 */
	protected static $needLogUserIds = array();
	
	/**
	 * 初始化错误日志模块
	 * @param	string $moduleName	模块名称
	 */
	public function __construct( $moduleName , $userId = 0 )
	{
	
		$config = Common::getConfig( 'errorLog' );
		$this->isOpenLog = /*empty( $config["isOpenLog"] ) ? false : */true;
		$this->errorLogDir = empty( $config["errorLogDir"] ) ? "/data/GameErrorLog/" : $config["errorLogDir"];
		self::$needLogUserIds = $config['errorLogUserIds'];
		
		$this->moduleName = $moduleName;
		$this->userId = $userId;
		
		//获取当前时间
		$time = microtime();
		$time = explode( " " , $time );
		$this->errorLogDir .= date( "Y-m-d" , $time[1] ) ."/";
		
		if( !self::$virtualThreadId )
		{
			self::$virtualThreadId = mt_rand();
		}
		
	}
	
	/**
	 * 写入日志
	 * @param	string $message	日志信息
	 */
	public function addLog( $message )
	{
	
		//判断时间文件夹是否存在
		if( !file_exists( $this->errorLogDir ) )
		{
			@mkdir( $this->errorLogDir , 0777 , true );
		}
		
		$time = microtime();
		$time = explode( " " , $time );
		
		$fp = fopen( "{$this->errorLogDir}{$this->moduleName}-". date( "Y-m-d-H" , $time[1] ) ."H.txt" , "a+" );
		fwrite( $fp , date( "Y-m-d H:i:s" ) . ltrim( sprintf( "%.6f" , $time[0] ) , "0" ) ." | ThreadID: ". self::$virtualThreadId ." | {$message} \n" );
		fclose( $fp );
		
	}
}