<?php 

if( !defined( 'IN_INU' ) )
{
	return;
}

/**
 * 所有controller的基类
 * @author	liuchangbing
 * @since	2013.7.15
 */
abstract class AdminBaseController
{
	protected $post = array();
	protected $get = array();
	protected $userId = 0;
	protected $config = array();
	private $tplData = array();
	protected $adminObj = array();
	protected $adminType = 0;
	
	public function __construct()
	{
		if( get_magic_quotes_gpc() )
		{
			Common::prepareGPCData( $_GET );
			Common::prepareGPCData( $_POST );
		}
		$_POST = $_GET = $this->get = $this->post = array_merge( $_POST , $_GET );
		$this->config = Common::getConfig();
		$this->login();
	}
	
	public function login()
	{
		$loginName = trim( $_POST['loginName'] );
		$result = Stats_Model::findOne( "adminUser" , array( 'loginName' => $loginName ) );
		

		if( $_POST['loginName'] &&  $result['password'] == $_POST['password'] )
		{
			$_SESSION['adminInfo'] = $result;
		}

		if( $_SESSION['adminInfo'] )
		{
			$this->main();
		}
		else
		{
			$this->display( "login.php");
			exit;
		}
	}
	
	
	public function logout()
	{
		

		header("Location:admin.php");
		session_unset();
		session_destroy();
		//$this->display( 'login.php' );
	}
	
	/**
	 * 设置模板变量
	 */
	protected function assign( $key , $value )
	{
		$this->tplData[$key] = $value;
	}
	
	/**
	 * 展示模板
	 */
	protected function display( $tpl )
	{
		extract( $this->tplData );
		include TPL_DIR.'/'.$tpl;
	}
		
	/**
	 * 主程序
	 */
	abstract protected function main();
}
?>
