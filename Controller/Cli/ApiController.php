<?php

if( !defined( 'IN_INU' ) )
{
	return;
}

ini_set( 'display_errors' , 'on' );
error_reporting( E_ALL ^ E_NOTICE );
ini_set( 'memory_limit' , '512M' );

class ApiController extends CliBaseController
{
    private $_logs = array();
	
    public function doStats()
    {
	if( $_SERVER['argc'] < 4 )
	{
		echo "参数错误";exit;
	}

	$argv =  $_SERVER['argv'];
	$serverTime = $_SERVER['REQUEST_TIME'];
	$appid = $argv[2];
	$sid = $argv[3];
	Stats_Analysis::doStat( $appid , $sid , $serverTime );
	echo "执行完毕";
    }

          
	
	
}
