<?php 

if( !defined( 'IN_INU' ) )
{
	return;
}

/**
 * 所有controller的基类
 * @author	Luckyboys
 * @since	2010.11.02
 */
abstract class CliBaseController
{
	protected $inputData = array();
	protected $config = array();
	
	public function __construct()
	{
		if( get_magic_quotes_gpc() )
		{
			Common::prepareGPCData( $GLOBALS['argv'] );
		}

		$this->inputData = $GLOBALS['argv'];
		$this->config = Common::getConfig();
	}
}
?>