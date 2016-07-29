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
	
	/**
     * 执行统计
     */
    public function dostats()
    {
        /* 
	 for( $i = 10 ; $i-- ; $i>= 0 )
          {
            $serverTime = $_SERVER['REQUEST_TIME'] - $i*86400 ;
            Stats_Analysis::doStat( 1003 , 18 , $serverTime );
          }
        exit;
 	file_put_contents( "/tmp/logCrontab", "start\n" );
    	*/
		//for( $i = 8 ; $i-- ; $i>= 0 )
    	//{
    	
	        $serverTime = $_SERVER['REQUEST_TIME'] - $i*86400 ;
					
	        //第二天凌晨10分钟内，仍然更新前一天数据
	        if( date( "Ymd" ,  $serverTime ) > date( "Ymd" , $serverTime - 3600 ) )
	        {
	          $serverTime -=  3600;
	        }
	                 
	        $pf = Common::getConfig( "platform" );
	        $serverList = Common::getConfig( "serverList" );
	        $sids = $serverList[$pf];
	                 
	       
	        foreach ( $sids as $appid => $appSids )
	        {         
	          foreach (  $appSids as $sid )
	          {     
	             Stats_Analysis::doStat( $appid , $sid , $serverTime );
	             file_put_contents( "/tmp/logCrontab", "appId:".$appid."sid:".$sid."time:".date( "Y-m-d H:i:s" , $serverTime )."\n" , FILE_APPEND );
	             sleep( rand( 1 ,  5 ));
	          }
	       }

       
    	//}
    }

          
   public function loopStats()
   {
          	
     for( $i = 5 ; $i-- ; $i>= 0 )
     {
	    $serverTime = $_SERVER['REQUEST_TIME'] - $i*86400 ;
	    Stats_Analysis::doStat( 1000 , 1102 , $serverTime );
     }
   }
   
   
   
   /**
    * 从数据库同步数据,每天同步一次
    */
   public function syncData()
   {
   		Cli_SyncData::sync();
   }
   
   
   
   
   
   
	
	
}
