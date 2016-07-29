<?php
/*
 * Copyright 2012, Changbing Liu.  All rights reserved.
 * https://free.svnspot.com/lchb.cppdev/trunk/phpframework
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the License file.
 */

header("Content-Type: text/html;charset=utf-8");

if(extension_loaded('zlib'))
{
	ini_set('zlib.output_compression', 'On');
	ini_set('zlib.output_compression_level', '-1');
}

$startTime = microtime( true );
define( 'DEBUG' , true );
define( 'IN_INU' , true );
define( "ROOT_DIR" , dirname( dirname( dirname( __FILE__ ) ) ) );
define( "LIB_DIR" , ROOT_DIR ."/Lib" );
define( "MOD_DIR" , ROOT_DIR ."/Model" );
define( "CON_DIR" , ROOT_DIR ."/Controller/Api" );
define( "CONFIG_DIR" , ROOT_DIR . "/Config" );

$_POST = $_GET = $_REQUEST;

//调试设置
if( DEBUG && isset( $_GET["debug"] )  )
{
	error_reporting( E_ALL ^ E_NOTICE );
	ini_set( 'display_errors' , 'On' );
}
else 
{
	error_reporting( 0 );
	ini_set( 'display_errors' , 'Off' );
}

include LIB_DIR .'/Common.php';
Helper_RunLog::getInstance()->addLog( "Api" , "request start..." );

//性能分析器
$config = Common::getConfig();
if( $config['xhprof']['isOpen'] && function_exists( 'xhprof_enable' ) )
{
	include_once MOD_DIR .'/XHProf/xhprof_lib.php';
	include_once MOD_DIR .'/XHProf/xhprof_runs.php';
	xhprof_enable();
}

/*
$allowIp = array(
	'211.144.87.88',
	'211.144.68.31',
	'211.144.68.46',
);

$ip = Helper_IP::getCurrentIP();
if( in_array( $ip , $allowIp ))
{
	header("http/1.1 404 Not Found");
	exit;
}
*/

if( !empty( $_GET['method'] )  )
{
	$method = explode( '.' , $_GET['method'] );
	$controller = ucfirst( strtolower( $method[0] ) ) . 'Controller';
	$action = $method[1];
	

	if( file_exists( CON_DIR . "/{$controller}.php" ) )
	{
		$conObject = new $controller;
		if( method_exists( $conObject , $action ) )
		{
			try
			{
				$info = $conObject->$action();
				
				
				$result = 0;
				$msg = '';
				
				if( isset( $info['errorCode'] ) && $info['errorCode'] > 0 )
				{
					$result = $info['errorCode'];
					$info = array();
					$msg = '';
				}
				else 
				{
					ObjectStorage::save();
				}
			}
			catch ( Exception $e )
			{
				$info = array();
				$result = $e->getCode();
				$msg = $e->getMessage();
				
				$errorLog  = new ErrorLog( "traceException" , $uId  );
				$errorContent = "\nParam:".json_encode( array_merge( $_GET , $_POST ) )."\nErrorLog:".$e;
				$errorLog->addLog(  $errorContent );
			}
			
			$result = array
			(
				'status' => $result ,
				'method' => $_GET['method'] ,
				//'msg' => $msg ,
				'msg' => (string)$result ,
				//'info' => $info ,
			);
			
			if( $info )
			{
				$result = array_merge( $result , $info );
			}
			$endTime = microtime( true );
			if( DEBUG && isset( $_GET["debug"] ) )
			{
				echo "<pre>";print_r( $result );printf( "\n%dus.\n" , ( $endTime - $startTime ) * 1000000 );echo Helper_RunLog::getInstance()->getRunData();echo "</pre>";
			}
			echo json_encode( $result );
			$traceTime = ( $endTime - $startTime ) * 1000 ;
			if( $traceTime > 10 )
			{
				file_put_contents( "/tmp/logEdacSlow", "usetime:{$traceTime}ms Data:".json_encode( $_REQUEST )."\n", FILE_APPEND );
			}
			
			
			if( $config['xhprof']['isOpen'] && function_exists( 'xhprof_disable' ) )
			{
				$xhprof_data = xhprof_disable();
				$xhprof_runs = new XHProfRuns_Default( $config['xhprof']['logDir'] );
				$xhprof_runs->save_run( $xhprof_data , $_GET['method'] , $uId );
			}
			return;
		}
	}
}
echo json_encode( array( 'status' => 3 , 'msg' => 'method not exist' ) );
