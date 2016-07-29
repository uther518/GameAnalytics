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
abstract class BaseController
{
	protected $post = array();
	protected $get = array();
	protected $userId = 0;
	protected $config = array();
	
	public function __construct()
	{
		if( get_magic_quotes_gpc() )
		{
			Common::prepareGPCData( $_GET );
			Common::prepareGPCData( $_POST );
		}

		$this->get = $this->post = array_merge( $_POST , $_GET );
		$this->config = Common::getConfig();
	}
	
	public function setUser( $userId )
	{
		$this->userId = $userId;
	}
}
?>