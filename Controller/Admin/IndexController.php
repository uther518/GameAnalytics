<?php

if( !defined( 'IN_INU' ) )
{
	return;
}


class IndexController extends AdminBaseController
{
	
	private $db;
	
	public function __construct()
	{
		ini_set( 'memory_limit' , '512M' );
		ini_set( "max_execution_time", "300" );	
		parent::__construct();
	}
		
	
	public function main()
	{
		if( $_GET['chartType'] )
		{
			$_SESSION['chartType'] = $_GET['chartType'];
		}
		
		$f = empty( $_GET[ 'f' ] ) ? 'index' : $_GET[ 'f' ];
		$this->assign( 'f' , $f );

		if( method_exists( $this , $f ) )
		{
			$this->$f();
		}
		exit();
	}
	
	public function SetLang()
	{
		$lang = $_GET['l'];
		if( $lang == 'en' )
		{
			$_SESSION['lang'] = 'en';
		}
		else
		{
			$_SESSION['lang'] = 'cn';
		}
		$this->index();
	}
	
	public function index()
	{	
		$collectName =  "appList";
		$appList = Stats_Model::find( $collectName );
		foreach ( $appList  as  $key => $appInfo )
		{
			if( !in_array( $appInfo['appId'] , $_SESSION['adminInfo']['appManage']))
			{
				//unset( $appList[$key] );
				//continue;
			}
			$query = array(
					'appId' => $appInfo['appId'],
			);
			$collectName =  "serverList";
			$serverList = Stats_Model::find( $collectName , $query );
			$appList[$key]['servers'] = $serverList;
		}
		$_SESSION['appList'] = $appList;
		$this->assign( 'appList', $appList );
		$this->display( "productList.php" );
		exit;
		
	}
	
	
	
	public function createProduct()
	{
		$step =  $_POST['step'] ?  $_POST['step'] : 1;
		if( $step == 1 && strlen( $_POST['productName']) > 0 && strlen( $_POST['productType']) > 0 )
		{
			$collectName =  "appList";
			//验证是否已经存在
			$query = array(
				'appName' => trim( $_POST['productName'] )
			);
			$result = Stats_Model::find( $collectName , $query );
			if( $result[0]['appName'])
			{
				echo "此应用已经存在";
			}
			else 
			{
				//查找一个最大appId
				$cond = array( 'sort' => array( 'appId' => -1 ), 'limit' => 1  );
				$result = Stats_Model::find( $collectName , array() , $cond  );
				$appId = $result[0]['appId'] ? $result[0]['appId'] + 1 : 1000;
	
				$record = array(
					'appName' => trim( $_POST['productName'] ),
					'appId' => $appId,
					'appType' => trim( $_POST['productType'] ),
					'createTime' => date( "Y-m-d H:i:s" , $_SERVER['REQUEST_TIME'] ),
				);
				//print_r( $record );exit;
				Stats_Model::add( $collectName , $record );
				
				$step = 2;
			}
		}
		
		$this->assign( "appId" , $appId );
		$this->assign( "step", $step );
		$this->display( "createProduct.php" );
		exit;
	}
	
	
	public function content()
	{
		if( !$_SESSION['appList'] )
		{
			$collectName =  "appList";
			$appList = Stats_Model::find( $collectName );
			$serverInfo = array();
			
			foreach ( $appList  as  $key => $appInfo )
			{
				
				$query = array(
					'appId' => $appInfo['appId'],
				);
				$collectName =  "serverList";
				$serverList = Stats_Model::find( $collectName , $query );		
				$appList[$key]['servers'] = $serverList;
			}
			$_SESSION['appList'] = $appList;
		}
		
		
		foreach ( $_SESSION['appList'] as $appInfo )
		{
			foreach ( $appInfo['servers'] as $svrInfo )
			{
				if( $appInfo['appId'] == $_GET['appId'] && $svrInfo['sid'] == $_GET['sid'] )
				{
					$serverInfo = $svrInfo;
				}
			}
		}
		
		
		$_SESSION['serverInfo'] = $serverInfo;
		if( !$_SESSION['serverInfo']['rmbRate'] || !$_SESSION['serverInfo']['currencyUnit'] )
		{
			$_SESSION['serverInfo']['rmbRate'] = 1;
			$_SESSION['serverInfo']['currencyUnit'] = '元';
		}

		
		$_SESSION['currAppId'] = $_GET['appId'];
		$_SESSION['currSid'] = $_GET['sid'];
		
		$this->display( "content.php" );
		exit;
	}
	
	
	/**
	 * 自定义时事件列表
	 */
	public function customActQuery()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-1000*86400 );
	
		
		
		//查询
		$params['notAutoQuery'] = 1;
		
		$action = $_GET['f'];
		$showData = Stats_Display::doDisplay( $action , $startDate , $endDate , $params );
	
		if( $_GET['export'])
		{

		}
		//结果处理
		$recordCount = $showData['count'];
		$pages = ceil( $recordCount / 30 );
		$page = $_GET['page'] ? $_GET['page'] : 1;
		
		$this->assign( 'recordCount', $recordCount );
		$this->assign( 'page', $page );
		$this->assign( 'pages', $pages );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "customActQuery.php" );
		
		
		
	}
	
	
	
	
	/**
	 * 玩法参与度
	 */
	public function playMethod()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$showData = Stats_Display::doDisplay( "playMethod", $startDate, $endDate );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "playMethod.php" );
	}
	
	
	/**
	 * 新手无操作
	 */
	public function newbieNoAct()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$showData = Stats_Display::doDisplay( "newbieNoAct", $startDate, $endDate );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "newbie.php" );
	}
	

	/**
	 * 新手引导
	 */
	public function newbie()
	{
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME'] );
		$endDate = $startDate;
		
		$act = 'newbie';
		$showData = Stats_Display::doDisplay( $act , $startDate, $endDate  );
		
		//print_r( $showData );exit;
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		
		$this->assign( 'showData', $showData );
		$this->display( "newbie.php" );

	}
	
	
	
	
	/**
	 * 等级分布
	 */
	public function level()
	{
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME'] );
		$endDate = $startDate;
		
		$act = 'levelMap';
	
		$showData = Stats_Display::doDisplay( $act , $startDate, $endDate  );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		
		$this->assign( 'showData', $showData );
		$this->display( "{$_GET['act']}.php" );
		
	}
	
	/**
	 * 新增用户
	 */
	public function userData()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$act = $_GET['act'] ?  $_GET['act'] : 'newUser';
		$showData = Stats_Display::doDisplay( "userData", $startDate, $endDate );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "{$act}.php" );
	}
	
	
	/**
	 * DAU/MAU
	 */
	public function dmau()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$showData = Stats_Display::doDisplay( "dmau", $startDate, $endDate );
		$f = $_GET['f'];
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData[$f] );
		$this->display( "wmau.php" );
	}
	
	/**
	 * 周活跃
	 */
	public function mau()
	{
		$this->wau();
	}
	
	/**
	 * 周活跃
	 */
	public function wau()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  strtotime("-7 week") );
		

		$showData = Stats_Display::doDisplay( "wmau", $startDate, $endDate  );
		
		$f = $_GET['f'];
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData[$f] );
		$this->display( "wmau.php" );
	}
	
	/**
	 * 留存
	 */
	public function keepLogin()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$days = $_GET['days'] ?  $_GET['days'] : 2;
		$showData = Stats_Display::doDisplay( "keepLogin", $startDate, $endDate );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "keepLogin.php" );
	}
	
	
	/**
	 * 付费
	 */
	public function mRecharge()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] :  date( "Ymd" ,  strtotime("-8 month") );
	
		$displayName = 'mRechargeRate';
	
		$showData = Stats_Display::doDisplay( $displayName , $startDate, $endDate , array( 'notAutoQuery' => 1 ) );
	
	
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "rechargeRate.php" );
	}
	
	
	
	/**
	 * 付费
	 */
	public function recharge()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
	
		if( $_GET['act'] == 'chargeRate' ||  $_GET['act'] == 'darpu' || $_GET['act'] == 'darppu'  )
		{
			$displayName = 'rechargeRate';
		}
		else 
		{
			$displayName = 'recharge';
		}
		
		$showData = Stats_Display::doDisplay( $displayName , $startDate, $endDate );
		
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "{$displayName}.php" );
	}
	
	
	/**
	 * 查询冲值记录
	 */
	public function rechargeRecord()
	{
	
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-1000*86400 );
		
		//查询
		$params['notAutoQuery'] = 1;
		$showData = Stats_Display::doDisplay( 'rechargeRecord' , $startDate , $endDate , $params );
		
		//结果处理
		$recordCount = $showData['count'];
		$pages = ceil( $recordCount / 30 );
		$page = $_GET['page'] ? $_GET['page'] : 1;
		
		
		
		$this->assign( 'showData', $showData );
		$this->assign( 'recordCount', $recordCount );
		$this->assign( 'page', $page );
		$this->assign( 'pages', $pages );
	
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'records', $showData['records'] );
		$this->display( "rechargeRecord.php" );
	}
	
	
	
	/**
	 * 道具获取记录
	 */
	public function gainItem()
	{
	
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-1000*86400 );
	
		//查询
		$params['notAutoQuery'] = 1;
		
		$action = $_GET['f'];
		$showData = Stats_Display::doDisplay( $action , $startDate , $endDate , $params );
		
		
		//结果处理
		$recordCount = $showData['count'];
		$pages = ceil( $recordCount / 30 );
		$page = $_GET['page'] ? $_GET['page'] : 1;
		
		$this->assign( 'recordCount', $recordCount );
		$this->assign( 'page', $page );
		$this->assign( 'pages', $pages );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "item.php" );
	}
	
	
	
	/**
	 * 道具失去记录
	 */
	public function lostItem()
	{
		$this->gainItem();
	}
	
	
	/**
	 * 道具使用分布
	 */
	public function itemLostRank()
	{
		$this->itemGainRank();
	}
	public function itemGainRank()
	{
	
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-10*86400 );
	
		//查询
		$params['notAutoQuery'] = 1;
	
		$action = $_GET['f'];
		$showData = Stats_Display::doDisplay( $action , $startDate , $endDate , $params );
	
		//print_r( $showData );exit;
	
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "itemGainRank.php" );
	}
	
	
	
	
	/**
	 * 按支付类型分
	 */
	public function payType()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$displayName = "payType";
		$showData = Stats_Display::doDisplay( $displayName , $startDate, $endDate );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "{$displayName}.php" );
		
	}
	
	public function onlineStats()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$showData = Stats_Display::doDisplay( "onlineStats" , $startDate, $endDate , array( 'notAutoQuery' => 1 ) );
		
		
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "onlineStats.php" );
	}
	
	/**
	 * 在线用户
	 */
	public function userOnline()
	{
		$date = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']);
		$hour =  $_GET['hour'] ? $_GET['hour'] : date( "H" , $_SERVER['REQUEST_TIME'] );
		
		
		$dateStr = $date." ".$hour.":00:00";
		
		if( $_GET['offType'] == "curr" )
		{
			$date = date( "Ymd" ,  $_SERVER['REQUEST_TIME']);
			$hour = date( "H" , $_SERVER['REQUEST_TIME'] );
		}
		else 
		{
			$offNum = $_GET['num'];
			$offType =  $_GET['offType'];
		
			$date = date( "Ymd" , strtotime( $offNum." ".$offType , strtotime( $dateStr )  ));
			$hour = date( "H" , strtotime( $offNum." ".$offType , strtotime( $dateStr )  ));
			
		}
		
		$displayName = "userOnline";
		$showData = Stats_Display::doDisplay( $displayName, $date, $date  );
	
		//显示最后两个小时
		$dateStr = $date." ".$hour.":00:00";
		
		$startTime = strtotime( $dateStr )  - 3600*3;
		$endTime =  strtotime( $dateStr ) + 3600*3;
		if( $endTime > $_SERVER['REQUEST_TIME'] )
		{
			$n5 = intval( ( $endTime - $_SERVER['REQUEST_TIME'] )/300 );
			$startTime = $startTime - 300*n5;
		}
		
		
		$show['userOnline'] = array();
		while( $startTime <= $endTime )
		{
			
			if( $startTime > $_SERVER['REQUEST_TIME'] )
			{
				break;
			}
	
			if( !$showData['userOnline'][$startTime]['x'] )
			{
				$show['userOnline'][] = array(
					'x' => floatval( $startTime*1000 ),
					'y' => 0,
					
				);
			}
			else 
			{
				$show['userOnline'][] = array(
						'x' => floatval( $startTime*1000 ),
						'y' => (int)$showData['userOnline'][$startTime]['y'],
				);
			}		
			$startTime += 300;
		}

		$this->assign( 'startDay', $date );
		$this->assign( 'endDay', $date );
		$this->assign( 'date', $date );
		$this->assign( 'hour', $hour );
		$this->assign( 'showData', $show );
		$this->display( "userOnline.php" );
		
	}
	
	
	/**
	 * 剩余冲值币数
	 */
	public function coinTotal()
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		$showData  = Stats_Display::doDisplay( "currencyTotal" , $startDate, $endDate  );
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'showData', $showData );
		$this->display( "currencyTotal.php" );
	}
	
	public function goldTotal()
	{
		$this->coinTotal();
	}
	
	/**
	 * 获取金币
	 */
	public function gainGold()
	{
		$this->gainCoin( 'gainGold' , 'golds' );
	}
	
	/**
	 * 消耗金币
	 */
	public function consumeGold()
	{
		$this->gainCoin( 'consumeGold', 'golds' );
	}
	
	/**
	 * 消耗冲值币
	 */
	public function consumeCoin()
	{
		$this->gainCoin( 'consumeCoin' );
	}
	
	/**
	 * 获取充值币
	 */
	public function gainCoin( $act = 'gainCoin' , $unit = 'coins' )
	{
		$endDate   = $_GET['endDay'] ? $_GET['endDay'] : date( "Ymd" , $_SERVER['REQUEST_TIME']);
		$startDate = $_GET['startDay'] ? $_GET['startDay'] : date( "Ymd" ,  $_SERVER['REQUEST_TIME']-8*86400 );
		
		$showData = Stats_Display::doDisplay( "currencyIO" , $startDate, $endDate  );
		
		$count = count( $showData['xAxis']['categories'] );
		if( $act == 'gainCoin' || $act == 'consumeCoin' )
		{
			$ut = '充值币';
		}
		else
		{
			$ut = '游戏币';
		}
		
		if( $showData[$act] )
		{
			foreach ( $showData[$act] as $type => $info )
			{
				for( $i = 0 ; $i < $count ; $i++ )
				{
					if( !$info[$unit][$i] )
					{
						$showData[$act][$type][$unit][$i] = 0;
						$showData[$act][$type]['times'][$i] = 0;
					}	
				}	
				
				ksort( $showData[$act][$type][$unit] );
				ksort( $showData[$act][$type]['times'] );
				
				$showData['data']["{$act}Num"][] = array(
					'name' => $type."(".$ut.")",
					'data' => $showData[$act][$type][$unit],
				);
				
				$showData['data']["{$act}Times"][] = array(
					'name' => $type."(次数)",
					'data' => $showData[$act][$type]['times'],
				);
				
			}
		}
		
		$this->assign( 'startDay', $startDate );
		$this->assign( 'endDay', $endDate );
		$this->assign( 'act', $act );
		$this->assign( 'showData', $showData );
		$this->display( "currencyIO.php" );
		
	}
	
	
	public function left()
	{
	
		$this->display( "left.php" );
	}
	
	
	public function right()
	{
		$startDate = $endDate = date( "Ymd" , $_SERVER['REQUEST_TIME'] );
		$showData = Stats_Display::doDisplay( "userData", $startDate, $endDate );
		
		$this->assign( 'showData', $showData );
		$this->display( "right.php" );
	}
	
	
	public function accountCenter()
	{
		$collectName = 'adminUser';
		$result = Stats_Model::find( $collectName  );
		$act = $_REQUEST['act'] ? $_REQUEST['act'] : 'add';
		
		$query = array(
				'loginName' => trim( $_POST['loginName'] )
		);
		
		if( $act == 'mod' )
		{
			Stats_Model::update( $collectName , $query , $_POST );
				
		}
		elseif( $act == 'del' )
		{
			Stats_Model::remove( $collectName , $query );	
		}
		elseif( $act == 'add' )
		{
			$flag = 1;
			if( strlen( $_POST['password'] ) < 6 )
			{
				echo "密码太短";
				$flag = 0;
			}
			
		
			
			if( Stats_Model::findOne( $collectName , $query ) )
			{
				$flag = 0;
			}
			
		
			if( $flag == 1 )
			{
				$_POST['regisTime'] = $_SERVER['REQUEST_TIME'];
				Stats_Model::add( $collectName , $_POST );
			}
		}
		
		$result = Stats_Model::find( $collectName  );
		
		$this->assign( 'userList', $result );
		$this->display( "accountCenter.php" );
	}
	
	
	public function createAccount()
	{
		$act = $_REQUEST['act'] ? $_REQUEST['act'] : 'add';
		$collectName = 'adminUser';
		if( $act == 'mod' )
		{
			//验证是否已经存在
			$query = array(
					'loginName' => trim( $_POST['loginName'] )
			);
			$result = Stats_Model::findOne( $collectName , $query );
			
			$this->assign( 'userData', $result );
		}
		
		$this->assign( 'act', $act );
		$this->display( "createAccount.php" );
	}
	
	
	/**
	 * 渠道管理
	 */
	public function channalMgr()
	{
		
		if( $_GET['act'] == 'add' )
		{
			$query = array(
					'userType' => '2',
			);
			$adminList = Stats_Model::find( 'adminUser' , $query );
			$this->assign( 'adminList', $adminList );
			
			
			//查找渠道
			$query = array(
					'appId' => (int)$_SESSION['currAppId'],
					'sid' => (int)$_SESSION['currSid'],
			);
			Stats_Model::setAppId( $query['appId'] );
			Stats_Model::setSid( $query['sid'] );
			$refers = Stats_Model::distinct( 'newUser', 'downRefer' );
			
			$this->assign( 'refers', $refers );
			$this->display( "createChannalMgr.php" );
			exit;
		}
		elseif( $_GET['act'] == 'save' )
		{
		
			$loginName = trim( $_POST['logName'] );
			$query = array(
				'loginName' => strval( $loginName ),
			);
			$record = Stats_Model::findOne( 'adminUser' , $query );
			if(  $record && !empty( $_POST['channals'] ))
			{
				$record['channals'] = array(
						$_SESSION['currAppId'] =>  $_POST['channals'],
				);
				Stats_Model::update( 'adminUser' , $query, $record );
			}
			
		}
		elseif( $_GET['act'] == 'del' )
		{
		
			$loginName = trim( $_GET['logName'] );
			$query = array(
					'loginName' => strval( $loginName ),
			);
			$record = Stats_Model::findOne( 'adminUser' , $query );
			$record['channals'] = array(
					$_SESSION['currAppId'] =>  array(),
			);
			Stats_Model::update( 'adminUser' , $query, $record );
		}
	
		$query = array(
				'userType' => '2',
		);
		$adminList = Stats_Model::find( 'adminUser' , $query );
		$this->assign( 'adminList', $adminList );
		
		$this->display( "channalList.php" );
		
	}
	
	/**
	 * 将数据导出为CSV
	 */
	public function exportCsv()
	{
		$act = $_GET['act'] ? $_GET['act'] : 'stat';
		header("Content-Disposition:attachment;filename={$act}.csv" );
		header('Content-type: text/csv; charset=UTF-16LE');
		echo mb_convert_encoding($_GET['data'],"GB2312","UTF-8");
		
		
	}
	
	
	public function exportExcel()
	{
		include LIB_DIR."/PHPExcel.php";
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $data = array(
            array( "a" => 111, "b" => 333, "c" => "444" ),
            array( "a" => 3333, "b" => 1111, "c" => "xxx" ),
            array( "a" => "ffff", "b" => "xxx", "c" => "444" ),
            array( "a" => 111, "b" => 333, "c" => "444" ),
        );
        //$data是要导出的数据，可以从mongodb查询出来
        //类似$adminList = Stats_Model::find( 'adminUser' , $query );
        
		
		
        $excelData = array(
            "test",  //这是excel文件名称
            array( "用户ID" , "人数" , "时间"), //excel第一行字段名称
            array( "a" , "b" , "c" ),	//这是对应于$data中每行的key,使顺序与第一行字段名称一致。
            $data,	//这是要导出的二维数组数据
        );
        
        $objPHPExcel->exportArray(   $excelData );
        exit;
	}
	
}

?>
