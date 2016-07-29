<?php
/**
 * 命令脚本入口
 */
error_reporting( E_ALL ^ E_NOTICE );
$time = microtime( true );
define( "IN_INU" , true );
define( "ROOT_DIR" , dirname( dirname( dirname( __FILE__ ) ) ) );		#修改成游戏的根目录
define( "CONFIG_DIR" , ROOT_DIR . "/Config" );
define( "MOD_DIR" , ROOT_DIR ."/Model" );
define( "CON_DIR" , ROOT_DIR ."/Controller/Cli" );
define( "TPL_DIR" , ROOT_DIR ."/Tpl/Admin" );
define( "CACHE_DIR" , ROOT_DIR ."/Cache" );
define( "LIB_DIR" , ROOT_DIR ."/Lib" );
include LIB_DIR .'/Common.php';

//Api.doStatData
$method = $argv[1];

if( !empty( $method ) )
{
	$method = explode( '.' , $method );
	$con = ucfirst( strtolower( $method[0] ) ) . 'Controller';
	$act = $method[1];
	if( file_exists( CON_DIR . "/{$con}.php" ) )
	{
		$object = new $con;
		if( method_exists( $object , $act ) )
		{
			$object->$act();
			return ;
		}
	}
}

echo "method not exist\n";
?>