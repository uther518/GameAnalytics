<?php
/**
 * 后台管理入口(只部署在管理机上)
 */

error_reporting( E_ALL ^ E_NOTICE );
ini_set( 'display_errors' , 'Off' );
header("Content-Type: text/html;charset=utf-8");



session_start();
$time = microtime( true );
define( "IN_INU" , true );
define( "ROOT_DIR" , dirname( dirname( dirname( __FILE__ ) ) ) );		#修改成游戏的根目录

define( "CONFIG_DIR" , ROOT_DIR . "/Config" );
define( "MOD_DIR" , ROOT_DIR ."/Model" );
define( "CON_DIR" , ROOT_DIR ."/Controller/Admin" );
define( "LIB_DIR" , ROOT_DIR ."/Lib" );
define( "TPL_DIR" , ROOT_DIR ."/Tpl/Admin" );
define( "CACHE_DIR" , ROOT_DIR ."/Cache" );
include LIB_DIR .'/Common.php';
$con = empty( $_GET['mod'] ) ? 'IndexController' :  ucfirst( strtolower( $_GET['mod'] ) ) . 'Controller';
$act = empty( $_GET['act'] ) ? 'login' : $_GET['act'];
$conFile = CON_DIR . "/{$con}.php";


if( !preg_match( '/Firefox/', $_SERVER['HTTP_USER_AGENT'] ))
{
	//echo "请使用firefox浏览器查看!";//exit;
}
if( file_exists( $conFile ) )
{	
	include $conFile;
	$object = new $con;

	if( method_exists( $object , $act ) )
	{
		$result = $object->$act();
		return ;
	}
}
