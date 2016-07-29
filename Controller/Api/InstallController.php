  <?php
if( !defined( 'IN_INU' ) )
{
	return;
}

/**
 * 统计接口API
 * @author liuchangbing
 * 公共字段
 * $uid
 * $serverName
 * $downRefer
 * $ip  
 * 
 */
class InstallController extends BaseController
{
	public function initDB()
        {
               $admin = array(
                        "loginName" => "admin",
                        "password" => "admin",
                        "userType" => 1,
                        'appManage' => array( "1000" ),
                        'writable' => array( 101,102,103 ),
                        'viewList' => array(1,2,3,4,5,6,7,8,9,10,11),
                );
    
	        $app = array(
   			"appName" => "三国测试传",
   			"appId" => 1000,
   			"appType" => "卡牌游戏",
   			"createTime" => date( "Y-m-d H:i:s"),
		);
                Stats_Model::initDB( $admin , $app );
        }

	/**
	 * 设置初始化环境
	 * $coinName 充值币名称
	 * $goldName 游戏币名称
	 * $maxNewbieStep 新手引导最大步骤数
	 *
	**/
	public function initApp()
	{
		$params = array(
				'appId' => 1000,
				'sid' => 10,
				'serverName' => "官网一服",
				'coinName' => "元宝",
				'goldName' => "金币",
				'maxNewbieStep' => 7,
				'maxLevel' => 100,
		);
		Stats_Model::initApp( $params );
	}
	
	
	/**
	 * 新增注册用户
	 * @param unknown $uid 用户游戏内唯一ID
	 * @param unknown $serverName 平台区服名称，如官网一区
	 * @param unknown $downRefer  下载渠道名称
	 * @param number $level		      初始等级
	 * @param string $ip		  IP地址
	 */
	public function newUser()
	{
		$params = $_GET;
		$ip = Helper_IP::getCurrentIP();
		
		for( $l = 0 ; $l < 1000 ; $l++ )
		{
		
			$d = rand( 0 , 1 );
			$downRefer = $d  > 0 ? "官网" : "十字猫";
			$uid = 10000+$l;
			
			$days = rand( 0 , 15 );
			$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
			
			$newbie = rand( 0 , 6 );
			
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => $uid,
				'nickName' => "归海{$uid}刀",
				'downRefer' => $downRefer,
				'level' => $params['level'] ? intval( $params['level'] ) : 1,
				'newbie' => $newbie,
				'ip' => $ip,
				'mac' => '1C-6F-2D-A3-4F-'.rand( 11,40),
				'registerTime' => $regis,
				'addTime' => $regis,
			);
			Stats_Model::newUser( $params );
		}
	}
	
	

	/**
	 * 用户登录统计
	 * @param unknown $uid 用户ID
	 * @param unknown $serverName 平台区服名称，如官网一区
	 * @param unknown $downRefer  下载渠道名称
	 * @param unknown $level	     初始等级
	 * @param unknown $ip         IP地址
	 * @param unknown $registerTime 注册时间
	 * 
	 */
	public function userLogin()
	{
		$params = $_GET;
		for( $l = 0 ; $l < 1000 ; $l++ )
		{
			$days = rand( 1 , 15 );
			$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
			$ip = Helper_IP::getCurrentIP();
			$uid = 10000+$l;
			for( $d=0;$d<33;$d++)
			{
				$login_st =  $regis+86400*$d;
				$params = array(
					'appId' => 1000,
					'sid' => 10,
					'uid' => $uid,
					'nickName' => "归海{$uid}刀",
					'downRefer' => "官网",
					'level' => rand( 1,20 ),
					'ip' => $ip,
					'mac' => '1C-6F-2D-A3-4F-'.rand(11,40),
					'loginTime' => $login_st,
					'registerTime' => $regis,
				);
				Stats_Model::userLogin( $params );
			}
		}
	}
	
	
	public function userOnline()
	{
		$params = $_GET;

		for( $i=0;$i<5000;$i++ )
		{
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'num' => rand( 300 , 700 ),
				'serverTime' => strtotime( date("Y-m-d H:00:00") )-$i*300,
			);
			Stats_Model::userOnline( $params );
		}
	}
	
	
	/**
	 * 冲值统计
	 * @param unknown $uid 用户ID
	 * @param unknown $orderId 订单ID
	 * @param unknown $rmb	人民币
	 * @param unknown $coin 游戏币
	 */
	public function recharge()
	{
		$params = $_GET;
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$ip = Helper_IP::getCurrentIP();
		
			$ds = array( '官网' , '51' ,  '91助手' );
			$ps = array( '支付宝' , '财富通'  );
			
			$rd = rand( 0 , 2 );
			$pd = rand( 0 , 1 );
			
			$days = rand( 1 , 15 );
			$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
			$params = array(
					'appId' => 1000,
					'sid' => 10,
					'uid' =>  rand( 10000 ,11000 ),
					'downRefer' => $ds[$rd],
					'orderId' => "ORDER_ID_".rand( 10000 , 99999 ),
					'rmb' => 30,
					'coin' => 40,
					'mac' => '1C-6F-2D-A3-4F-'.rand(10,30),
					'ip' => $ip,
					'payType' => $ps[$pd],
					'registerTime' => $regis,
					'serverTime' => $regis,
			);
	
			
			Stats_Model::recharge( $params );
		}
	}
	
	
	/**
	 * 充值币获取统计
	 */
	public function gainCoin()
	{
		$params = $_GET;
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$ip = Helper_IP::getCurrentIP();
			
			$days = rand( 0 , 7 );
			$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
			
			$evtNum = rand( 1 , 300 );
			$index = rand( 1 , 4 );
			
			$params[1] = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 ,11000 ),
				'coin' => $evtNum,
				'evtAct' => '购买元宝',
				'evtObj' => '',
				'evtNum' =>  "1个",
				'serverTime' => (int)$regis,	
			);
			
			
			
			$params[2] = array(
					'appId' => 1000,
					'sid' => 10,
					'uid' => rand( 10000 , 11000 ),
					'coin' => $evtNum,
					'evtAct' => '副本奖励',
					'evtObj' => '虎牢关',
					'evtNum' =>  "1次",
					'serverTime' => (int)$regis,
			);
		
			
			$params[3] = array(
					'appId' => 1000,
					'sid' => 10,
					'uid' => rand( 10000 ,11000 ),
					'coin' => $evtNum,
					'evtAct' => '连续登录奖励',
					'evtObj' => '5天',
					'evtNum' =>  "1次",
					'serverTime' => (int)$regis,
			);
			
			
			$params[4] = array(
					'appId' => 1000,
					'sid' => 10,
					'uid' => rand( 10000 ,11000 ),
					'coin' => $evtNum,
					'evtAct' => '新手初始元宝',
					'evtObj' => '',
					'evtNum' =>  "1次",
					'serverTime' => (int)$regis,
			);
			
			Stats_Model::gainCoin( $params[$index] );
		}
		//print_r( $params );exit;
		
		
	}
	
	
	

	/**
	 * 消费冲值币记录
	 * @param unknown $uid
	 * @param unknown $gid
	 * @param unknown $gnum
	 * @param unknown $singleCoin
	 * @param unknown $totalCoin
	 * @param unknown $evtDesc
	 */
	public function consumeCoin()
	{
		$params = $_GET;
		for( $l = 0 ; $l < 100 ; $l++ )
		{
		$days = rand( 0 , 15 );
		$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
		
		
		$index = rand( 1 , 5 );
		
		$params[1] = array(
			'appId' => 1000,
			'sid' => 10,
			'uid' => rand( 10000 ,11000 ),
			'coin' => 6,
			'evtAct' => '扩充军营',
			'evtObj' => '',
			'evtNum' =>  "1次",
			'serverTime' => (int)$regis,	
		);
		
		
		
		$params[2] = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 ,11000 ),
				'coin' => 5,
				'evtAct' => '扩充好友',
				'evtObj' => '',
				'evtNum' =>  "1次",
				'serverTime' => (int)$regis,
		);
	
		
		$params[3] = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 ,11000 ),
				'coin' => 20,
				'evtAct' => '抽神将',
				'evtObj' => '',
				'evtNum' =>  "1次",
				'serverTime' => (int)$regis,
		);
		
		
		$params[4] = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 ,11000 ),
				'coin' => 15,
				'evtAct' => '10连抽',
				'evtObj' => '',
				'evtNum' =>  "1次",
				'serverTime' => (int)$regis,
		);
		
		
		$params[5] = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 ,11000 ),
				'coin' => 10,
				'evtAct' => '恢复体力',
				'evtObj' => '',
				'evtNum' =>  "1次",
				'serverTime' => (int)$regis,
		);
		
		Stats_Model::consumeCoin( $params[$index] );
		}
		//print_r( $params );exit;
		
		
		
	}
	
	
	
	
	/**
	 * 金币获取统计
	 */
	public function gainGold()
	{
		$startTime = microtime( true );
		
		$params = $_GET;
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$ip = Helper_IP::getCurrentIP();
			
			$days = rand( 0 , 7 );
			$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
			
			$evtNum = rand( 1 , 300 );
			$index = rand( 1 , 4 );
			
			$params[1] = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 ,11000 ),
				'gold' => $evtNum,
				'evtAct' => '升级奖励',
				'evtObj' => 'test',
				'evtNum' =>  "1级",
				'serverTime' => (int)$regis,	
			);
			
			
		//print_r( $params[1]);exit;
			Stats_Model::gainGold( $params[1] );
		}
		//print_r( $params );exit;
		$endTime = microtime( true );
		$traceTime = ( $endTime - $startTime ) * 1000 ;
	}
	
	
	

	/**
	 * 消费金币记录
	 * @param unknown $uid
	 * @param unknown $gid
	 * @param unknown $gnum
	 * @param unknown $singleCoin
	 * @param unknown $totalCoin
	 * @param unknown $evtDesc
	 */
	public function consumeGold()
	{
		$params = $_GET;
		for( $l = 0 ; $l < 100 ; $l++ )
		{
		$days = rand( 0 , 7 );
		$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
		
		
		$index = rand( 1 , 3 );
		
		$params[1] = array(
			'appId' => 1000,
			'sid' => 10,
			'uid' => rand( 10000 ,11000 ),
			'gold' => 6,
			'evtAct' => '技能升级',
			'evtObj' => '',
			'evtNum' =>  "1次",
			'serverTime' => (int)$regis,	
		);
		
		
		
		$params[2] = array(
			'appId' => 1000,
			'sid' => 10,
			'uid' => rand( 10000 ,11000 ),
			'gold' => 5,
			'evtAct' => '武将强化',
			'evtObj' => '',
			'evtNum' =>  "1次",
			'serverTime' => (int)$regis,
		);
	
		
		$params[3] = array(
			'appId' => 1000,
			'sid' => 10,
			'uid' => rand( 10000 ,11000 ),
			'gold' => 5,
			'evtAct' => '购买道具',
			'evtObj' => '集钱袋',
			'evtNum' =>  "1次",
			'serverTime' => (int)$regis,
		);
	
		
		Stats_Model::consumeGold( $params[$index] );
		}
		//print_r( $params );exit;
		
		
		
	}
	
	
	public function upLevel()
	{
		for( $l = 0 ; $l < 100 ; $l++ )
		{
		$i = rand( 0 , 7 );
		$j = rand( 0 , 4 );
		
		if( rand( 1 , 100 ) > 80 )
		{
			$level = rand( 80 , 100 );
		}
		else 
		{
			$level = rand( 1, 80 );
		}
		
		$days = rand( 0 , 7 );
		$regis =  $_SERVER['REQUEST_TIME'] - 86400*$days;
		
		
		$params = $_GET;
		$ip = Helper_IP::getCurrentIP();
		
		$d = rand( 0 , 1 );
		$downRefer = $d  > 0 ? "官网" : "十字猫";
		$uid = rand( 10000,11000 );
		$params = array(
			'appId' => 1000,
			'sid' => 10,
			'uid' => $uid,
			'level' => $level,
		);
		
		Stats_Model::newUser( $params , true );
		}
	}
	
	
	/**
	 * 更新新手引导
	 */
	public function upNewbie()
	{
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			
			$uid = rand( 10000 ,11000 );
			$params = array(
					'appId' => 1000,
					'sid' => 10,
					'uid' => $uid,
					'newbie' => rand( 0 , 6 ),
			);
	
			Stats_Model::newUser( $params , true );
		}
	}
	
	
	/**
	 * 道具获取记录
	 * @param unknown $uid 用户ID
	 * @param unknown $itemId 道具ID
	 * @param unknown $itemNum	道具数量
	 * @param unknown $itemName 道具名称
	 * @param unknown $evtDesc  获取道具的原因
	 */
	public function gainItem()
	{
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$itemId = rand( 1001 , 1010 );
			$num = rand( 1 , 10 );
			$uid = rand( 10000 ,11000 );
			$days = rand( 0 , 7 );
			$stime =  $_SERVER['REQUEST_TIME'] - 86400*$days;
			
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => $uid,
				'nickName' => $uid,
				'itemId' => $itemId,
				'itemName' => $itemId,
				'itemNum' => $num,
				'evtDesc' => "",
				'serverTime' => $stime,
			);
		
			Stats_Model::gainItem( $params  );
		}
	}
	
	
	/**
	 * 
	 */
	public function lostItem()
	{
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$itemId = rand( 1001 , 1010 );
			$num = rand( 1 , 10 );
			$uid = rand( 10000 ,11000 );
			$days = rand( 0 , 7 );
			$stime =  $_SERVER['REQUEST_TIME'] - 86400*$days;
				
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => $uid,
				'nickName' => $uid,
				'itemId' => $itemId,
				'itemName' => '道具'.$itemId,
				'itemNum' => $num,
				'evtDesc' => "测试所得",
				'serverTime' => $stime,
			);
			Stats_Model::lostItem( $params );
		}
	}
	
	
	
	public function playMethod()
	{
		
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$itemId = rand( 1001 , 1010 );
			$num = rand( 1 , 10 );
			$uid = rand( 10000 ,11000 );
			$days = rand( 0 , 15 );
			$stime =  $_SERVER['REQUEST_TIME'] - 86400*$days;
		
			
			$methods = array( '竞技场','普通战场' , '活动战场' );
			$key = rand( 0 , 2 );
			
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => $uid,
				'methodName' => $methods[$key],
				'serverTime' => $stime,
			);
			
			Stats_Model::playMethod( $params );
		}
		
		
	}
	
	
	public function customAction()
	{
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$itemId = rand( 1001 , 1010 );
			$num = rand( 1 , 10 );
			$uid = rand( 10000 ,11000 );
			$days = rand( 0 , 7 );
			$stime =  $_SERVER['REQUEST_TIME'] - 86400*$days;
				
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => rand( 10000 , 11000 ),
				'act' => rand( 10001 , 10004 ),
				'obj' => rand( 1 , 10 ),
				'num' => rand( 1, 5 ),
				'serverTime' => $stime,
			);
				
			Stats_Model::customAction( $params );
		}
		
		
	}
	
	/**
	 * 在线统计
	 */
	public function evtCounter()
	{
		for( $l = 0 ; $l < 100 ; $l++ )
		{
			$itemId = rand( 1001 , 1010 );
			$num = rand( 1 , 10 );
			$uid = rand( 10000 ,11000 );
			$days = rand( 0 , 7 );
			$stime =  $_SERVER['REQUEST_TIME'] - 86400*$days;
		
			$params = array(
				'appId' => 1000,
				'sid' => 10,
				'uid' => $uid,
				'act' => rand( 10001 , 10002 ),
				'obj' => rand( 1 , 2 ),
				'num' => rand( 1, 5 ),
				'serverTime' => $stime,
			);
		
			Stats_Model::evtCounter( $params );
		}
		
	}
	
	
	
	
	
	
	/**
	 * 接收任务统计
	 */
	public function acceptTask()
	{
		
		
	}
	
	
	/**
	 * 完成任务统计
	 */
	public function finishTask()
	{
		
	
	}
	
	
	/**
	 * 新手引导统计
	 */
	public function newbieStep()
	{
		
	}
	

	public function run()
	{
		$result = Stats_Model::findOne( "adminUser" , array( "loginName" => "admin") );
		if( $result )
		{
			die( "已经安装，不能重复安装");
		}

		$methods = get_class_methods( "InstallController" );	
		for( $i=0;$i<count($methods)-4;$i++)
		{
			$method = $methods[$i];
			$this->$method();
		}
		$this->dostats();
	}	
	/**
	 * 执行统计
	 */
	public function dostats()
	{
		
		for( $i = 15 ; $i-- ; $i>= 0 )
		{
			$serverTime = $_SERVER['REQUEST_TIME'] - $i*86400 ;
			Stats_Analysis::doStat( 1000 , 10 , $serverTime );
		}
	}
	
	
}
