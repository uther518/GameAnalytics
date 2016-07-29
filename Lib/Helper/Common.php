<?php

if( !defined( 'IN_INU' ) )
{
	return;
}

class Helper_Common
{
	
	const CURRENCY_CN2TW = 5;
	
	
	public static function isWK()
	{
		return ( strpos( Common::getConfig( "pf" )  , "wk" ) === 0 );
	}
	
	public static function addLock( $name , $time = 10 )
	{
		$cache = Common::getCache();
		$tryTime = 0;
		while( $tryTime++ < 5 )
		{
			if( !!$lock = $cache->add( "lock_".$name , 1 , $time ) ) break; 
			usleep( 10000 );
		}
		
		return $lock;
	}

	public static function delLock( $name )
	{
		$cache = Common::getCache();
		$cache->delete( "lock_".$name );
	}
	
}