<?php

/**
 * 统计
 * @name Model.php
 * @author admin
 * @since 2013-1-14
 */
if( !defined( 'IN_INU' ) )
{
    return;
}


class Stats_Model extends Stats_Base
{
	
	/**
	 * 检查字段值是否合法
	 * @param unknown $params
	 * @throws Exception
	 */
	private static function _initParam( $params )
	{
		self::$appId = $params['appId'];
		self::$sid = $params['sid'];
		
		if( !self::$appId || !self::$sid )
		{
			throw new Stats_Exception( Stats_Exception::PARAM_ERROR  );
		}
		
	}
	public static function initDB( $admin , $app )
	{
		self::add( "adminUser" , $admin );
		self::add( "appList" , $app );
	}

	
	/**
	 * 初始化应用
	 * @param unknown $params
	 * $coinName 充值币名称
	 * $goldName 游戏币名称
	 * $maxNewbieStep 新手引导最大步骤数
	 */
	public static function initApp( $params )
	{
		
		$appId = $params['appId'];
		$sid = $params['sid'];
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => (int)$params['sid'],
			'serverName' => strval( $params['serverName'] ),
			'coinName' => $params['coinName'],
			'goldName' => $params['goldName'],
			'maxNewbieStep' => (int)$params['maxNewbieStep'],
			'maxLevel' => (int)$params['maxLevel'],
			'currencyUnit' => strval( $params['currencyUnit'] ),
			'rmbRate' => (int)$params['rmbRate'], //人民币比率
			
		);
		
		//查询是否已经创建该应用
		$query = array(
			'appId' => (int)$params['appId'],
		);
		$result = Stats_Model::findOne( "appList" , $query );
		if( !$result )
		{
			throw new Stats_Exception( Stats_Exception::NOT_THIS_APPID );
		}
	
		
		$query = array(
				'appId' => (int)$params['appId'],
				'sid' => (int)$params['sid'],
		);
		$collectName = "serverList";
		$result = Stats_Model::findOne( $collectName , $query );
		
		if( $result['appId'] != $query['appId'] && $result['sid'] != $query['sid'] )
		{
			self::add( $collectName , $record );
		}
	}
	
	
	
	/**
	 * 新增注册用户
	 * @param unknown $uid 用户游戏内唯一ID
	 * @param unknown $sid 平台区服名称，如官网一区
	 * @param unknown $downRefer  下载渠道名称
	 * @param number $level		      初始等级
	 * @param string $ip		  IP地址
	 * @param string $mac		     硬件唯 一标识//由客户端传给服务端
	 * @param int    $registerTime 注册时间
	 */
	public static function newUser( $params , $update = false )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => intval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'nickName' =>  strval( $params['nickName'] ),
			'downRefer' => strval( $params['downRefer'] ),
			'level' => $params['level'] ? intval( $params['level'] ) : 1,
			'newbie' =>  $params['newbie'] ? intval( $params['newbie'] ) : 0,
			'mac' => strval(  $params['mac'] ),
			'ip' => $params['ip'],
			'registerTime' => (int)$params['registerTime'],
			'serverTime' => $_SERVER['REQUEST_TIME'],
		);
		

		self::_initParam( $record );
		$query = array(
			'uid' => (int)$params['uid'],
		);

		$result = Stats_Model::findOne( "newUser" , $query );
		

		if( !$result && $update == false )
		{
			self::add(  "newUser" , $record );
		}
		//更新操作
		elseif(  $result['uid'] > 0 ) 
		{
			if( $record['level'] > 1  )
			{
				$result['level'] = $record['level'];
			}
			elseif( $record['newbie'] > 0 )
			{
				$result['newbie'] = $record['newbie'];
			}
			else 
			{
				return false;
			}
			
			$result['serverTime'] = $_SERVER['REQUEST_TIME'];
			self::update(  "newUser"  , $query , $result );
		}
	}
	
	
	/**
	 * 用户登录统计
	 * @param unknown $uid 用户ID
	 * @param unknown $sid 平台区服名称，如官网一区
	 * @param unknown $downRefer  下载渠道名称
	 * @param unknown $level	     初始等级
	 * @param unknown $ip         IP地址
	 * @param string  $mac		     硬件唯 一标识//由客户端传给服务端
	 * @param unknown $loginTime 注册时间
	 *
	 */
	public static function userLogin( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'uid' =>  intval( $params['uid'] ),
			'nickName' =>  strval( $params['nickName'] ),
			'sid' => intval( $params['sid'] ),
			'downRefer' => strval( $params['downRefer'] ),
			'level' => $params['level'] ? intval( $params['level'] ) : 1,
			'ip' => $params['ip'],
			'mac' => strval(  $params['mac'] ),
			'loginTime' => (int)$params['loginTime'],
			'registerTime' => (int)$params['registerTime'],
		);
		
		self::_initParam( $record );
		$collectName = "userLogin";
		self::add( $collectName , $record );
	}
	
	
	/**
	 * 冲值统计
	 * @param unknown $uid 用户ID
	 * @param unknown $orderId 订单ID
	 * @param unknown $rmb	人民币
	 * @param unknown $coin 游戏币
	 */
	public static function recharge( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'uid' =>  intval( $params['uid'] ),
			'sid' => intval( $params['sid'] ),
			'nickName' =>  strval( $params['nickName'] ),
			'downRefer' => strval( $params['downRefer'] ),
			'orderId' => strval( $params['orderId'] ),
			'rmb' => strval( $params['rmb'] ),
			'coin' => intval( $params['coin'] ),
			'mac' => strval(  $params['mac'] ),
			'ip' => $params['ip'],
			'payType' =>  $params['payType'],
			'registerTime' => (int)$params['registerTime'],
			'serverTime' => (int)$params['serverTime'],
		);
		
		self::_initParam( $record );
		$collectName = "recharge";
		
		$query = array(
			'orderId' => $record['orderId'],
		);
		
		$result = Stats_Model::findOne( $collectName , $query );
		if( !$result )
		{
			self::add( $collectName , $record );
		}
	}
	
	/**
	 * 用户在线统计
	 * @param unknown $params
	 */
	public static function userOnline( $params )
	{
		if( $params['num'] <= 0 )
		{
			return;
		}
		
		$hi = date( "H:i" , $params['serverTime'] );
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => intval( $params['sid'] ),
			'online' => array(
					$hi => $params['num'],
			),
			'date' => date( "Ymd" , $params['serverTime'] ),
			'serverTime' => (int)$params['serverTime'],
		);
		
		if(  date( "i" , $record['serverTime'] )%5 != 0 )
		{
			return;
		}
		
		$collectName =  "userOnline";
		self::_initParam( $record );
		
		$query = array(
			'date' => $record['date'],
		);
		$mongoData = self::findOne( $collectName , $query  );
		if( $mongoData['date'] )
		{
			//update
			$record['online'] = $mongoData['online'];
			$record['online'][$hi] = $params['num'];
			self::update( $collectName, $query , $record );
			
		}
		else 
		{
			self::add( $collectName , $record );
		}
			
	}

	
	/**
	 * 获取冲值币记录
	 * @param unknown $appId 应用ID
	 * @param unknown $sid 服务器ID
	 * @param unknown $uid 用户ID
	 * @param unknown $coin 充值币
	 * @param unknown $evtAct 事件行为  请用中文
	 * @param unknown $evtObj 事件目标  请用中文
	 * @param unknown $evtNum 事件数量  请用中文
	 * 
	 * 例如:通关一次XX副本为：普通副本  副本名称  1  
	 * 		冲值20元宝：冲值 元宝 20
	 */
	public static function gainCoin( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => intval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'coin' => intval( $params['coin'] ),
			'totalCoin' => intval( $params['totalCoin'] ),
			'evtAct' => strval( $params['evtAct'] ), 
			'evtObj' => strval( $params['evtObj'] ),
			'evtNum' =>  strval( $params['evtNum'] ),
			'serverTime' => (int)$params['serverTime'],
			'date' => date( "Ymd" , $params['serverTime'] ),
		);
		self::_initParam( $record );
		$collectName =  "gainCoin";
		
		self::add( $collectName , $record );
	}
	
	
	
	/**
	 * 消费冲值币记录
	 * @param unknown $appId 应用ID
	 * @param unknown $sid 服务器ID
	 * @param unknown $uid 用户ID
	 * @param unknown $coin 充值币
	 * @param unknown $evt 消费点分类固定，不能有变量
	 * @param unknown $evtType 消费点分类固定，不能有变量
	 * @param unknown $evtParam 事件参数
	 */
	public static function consumeCoin( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => intval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'coin' => intval( $params['coin'] ),
			'totalCoin' => intval( $params['totalCoin'] ),
			'evtAct' => strval( $params['evtAct'] ), 
			'evtObj' => strval( $params['evtObj'] ),
			'evtNum' =>  strval( $params['evtNum'] ),
			'serverTime' => (int)$params['serverTime'],
			'date' => date( "Ymd" , $params['serverTime'] ),
		);
		self::_initParam( $record );
		$collectName =  "consumeCoin";
		self::add( $collectName , $record );
	}
	
	
	
	/**
	 * 获取金币记录
	 * @param unknown $appId 应用ID
	 * @param unknown $sid 服务器ID
	 * @param unknown $uid 用户ID
	 * @param unknown $gold 金币
	 * @param unknown $evtAct 事件行为  请用中文
	 * @param unknown $evtObj 事件目标  请用中文
	 * @param unknown $evtNum 事件数量  请用中文
	 * 
	 * 例如:通关一次XX副本为：普通副本  副本名称  1  
	 * 		冲值20元宝：冲值 元宝 20
	 */
	public static function gainGold( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => intval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'gold' => intval( $params['gold'] ),
			'totalGold' => intval( $params['totalGold'] ),
			'evtAct' => strval( $params['evtAct'] ), 
			'evtObj' => strval( $params['evtObj'] ),
			'evtNum' =>  strval( $params['evtNum'] ),
			'serverTime' => (int)$params['serverTime'],
			'date' => date( "Ymd" , $params['serverTime'] ),
		);
		self::_initParam( $record );
		$collectName =  "gainGold";
		
		self::add( $collectName , $record );
	}
	
	
	
	/**
	 * 消费金币记录
	 * @param unknown $appId 应用ID
	 * @param unknown $sid 服务器ID
	 * @param unknown $uid 用户ID
	 * @param unknown $coin 金币
	 * @param unknown $evt 消费点分类固定，不能有变量
	 * @param unknown $evtType 消费点分类固定，不能有变量
	 * @param unknown $evtParam 事件参数
	 */
	public static function consumeGold( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => intval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'gold' => intval( $params['gold'] ),
			'totalGold' => intval( $params['totalGold'] ),
			'evtAct' => strval( $params['evtAct'] ), 
			'evtObj' => strval( $params['evtObj'] ),
			'evtNum' =>  strval( $params['evtNum'] ),
			'serverTime' => (int)$params['serverTime'],
			'date' => date( "Ymd" , $params['serverTime'] ),
		);
		self::_initParam( $record );
		$collectName =  "consumeGold";
		self::add( $collectName , $record );
	}
	
	
	/**
	 * 道具获取记录
	 * @param unknown $uid 用户ID
	 * @param unknown $itemId 道具ID
	 * @param unknown $itemNum	道具数量
	 * @param unknown $itemName 道具名称
	 * @param unknown $evtDesc  获取道具的原因
	 */
	public static function gainItem( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'uid' =>  intval( $params['uid'] ),
			'nickName' =>  strval( $params['nickName'] ),
			'sid' => strval( $params['sid'] ),
			'itemId' => strval( $params['itemId'] ),
			'itemName' => strval( $params['itemName'] ),
			'itemNum' => intval( $params['itemNum'] ),
			'evtDesc' => strval( $params['evtDesc'] ),
			'serverTime' => (int)$params['serverTime'],
		);
		self::_initParam( $record );
		self::add( "gainItem", $record );
	}
	
	/**
	 * 使用道具记录
	 * @param unknown $uid 用户ID
	 * @param unknown $itemId 道具ID
	 * @param unknown $itemNum	道具数量
	 * @param unknown $itemName 道具名称
	 * @param unknown $evtDesc  失去道具的原因
	 */
	public static function lostItem( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'uid' =>  intval( $params['uid'] ),
			'sid' => strval( $params['sid'] ),
			'itemId' => strval( $params['itemId'] ),
			'itemName' => strval( $params['itemName'] ),
			'itemNum' => intval( $params['itemNum'] ),
			'nickName' => strval( $params['nickName'] ),
			'evtDesc' => strval( $params['evtDesc'] ),
			'serverTime' => (int)$params['serverTime'],
		);
		self::_initParam( $record );
		self::add( "lostItem", $record );
	}
	
	
	/**
	 * 同步剩余冲值币数
	 */
	public static function haveCoin( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => strval( $params['sid'] ),
		);
		$query = array(
			'date' => date( 'Ymd' , $params['serverTime'] ),
		);
		self::_initParam( $record );
		$record = self::findOne( "resultData"  , $query );
		if( $record )
		{
			$record['coinTotal'] = (int)$params['num'];
			self::update( "resultData" , $query , $record );
		}
		else
		{
			$record['coinTotal'] = (int)$params['num'];
			$record['date'] =  date( 'Ymd' , $params['serverTime'] );
			self::add(  "resultData" ,  $record  );
		}
			
	}
	
	/**
	 * 同步剩余金币数
	 */
	public static function haveGold( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => strval( $params['sid'] ),
			//'goldTotal' => (int)$params['num'],
			//'serverTime' => (int)$params['serverTime'],
		);
		
		$query = array(
			'date' => date( 'Ymd' , $params['serverTime']  ),
		);
		
		self::_initParam( $record );
		$record = self::findOne( "resultData"  , $query );
		if( $record )
		{
			$record['goldTotal'] = (int)$params['num'];
			self::update( "resultData" , $query , $record );
		}
		else
		{
			$record['goldTotal'] = (int)$params['num'];
			$record['date'] =  date( 'Ymd' , $params['serverTime'] );
			self::add(  "resultData" ,  $record  );
		}
	}
	
	
	/**
	 * 玩法参与统计
	 */
	public static function playMethod( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => strval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'methodName' => strval( $params['methodName'] ),
			'serverTime' => (int)$params['serverTime'],
		);
		self::_initParam( $record );
		self::add( "playMethod", $record );
	}
	
	
	/**
	 * 自定义行为,不合并
	 * 1,用户事件
	 * 2,全局事件,uid为0
	 * @param unknown $params
	 */
	public static function customAction( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => strval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'act' => strval( $params['act'] ),
			'obj' => strval( $params['obj'] ),
			'num' => intval( $params['num'] ),
			'ext' => strval( $params['ext'] ), //扩展字段，可以记录其它信息
			'serverTime' => (int)$params['serverTime'],
		);
		self::_initParam( $record );
		self::add( "customAction", $record );
		
	}
	
	
	/**
	 * 事件记数器,数据合并
	 * 同一事件，每天一条记录，同一天合并
	 */
	public static function evtCounter( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'sid' => strval( $params['sid'] ),
			'uid' =>  intval( $params['uid'] ),
			'act' => strval( $params['act'] ),
			'obj' => strval( $params['obj'] ),
			'num' => intval( $params['num'] ),
			//如果为set则设置绝对值，inc为累加
			'type' => $params['type'] ? trim( $params['type'] ) : 'inc',
			'date' => (int)date( "Ymd" , $params['serverTime'] ),
		);
		self::_initParam( $record );
		
		
		//查询当天该事件是否存在，如果不存在增加，如果存在更新
		$query = array(
			'uid' =>  intval( $params['uid'] ),
			'act' => strval( $params['act'] ),
			'obj' => strval( $params['obj'] ),
			'date' => (int)$record['date'],
		);
		
		//创建索引
		$indexCond = array(
			'uid' => 1,
			'act' => 1,
			'obj' => 1,
			'date' => 1,
		);
		self::index( "evtCounter" , $indexCond );

		
		$result = self::findOne( "evtCounter" , $query );
		if( $result )
		{
			if( $record['type'] == 'inc' )
			{
				$record['num'] += $result['num'];
			}
			self::update( "evtCounter" , $query , $record );
		}
		else 
		{
			self::add( "evtCounter", $record );
		}
	}
	
	
	/**
	 * 接收新任务
	 * @param unknown $appId 应用id
	 * @param unknown $sid 服ID
	 * @param unknown $downRefer 下载来源
	 * @param unknown $uid 用户id
	 * @param unknown $taskId 任务id
	 */
	public static function acceptTask( $params )
	{
		$record  = array(
			'appId' => (int)$params['appId'],
			'uid' =>  intval( $params['uid'] ),
			'sid' => strval( $params['sid'] ),
			'downRefer' => strval( $params['downRefer'] ),
			'taskId' => strval( $params['taskId'] ),
			'taskName' => strval( $params['taskName'] ),
			'serverTime' => (int)$params['serverTime'],
		);
		self::_initParam( $record );
		self::add( "acceptTask", $record );
	}
	
	
	
	/**
	 * 完成任务
	 * @param unknown $appId 应用id
	 * @param unknown $sid 服ID
	 * @param unknown $downRefer 下载来源
	 * @param unknown $uid 用户id
	 * @param unknown $taskId 任务id
	 */
	public static function finishTask( $params )
	{
		$record  = array(
			'appId' =>(int)$params['appId'],
			'uid' =>  intval( $params['uid'] ),
			'sid' => strval( $params['sid'] ),
			'downRefer' => strval( $params['downRefer'] ),
			'taskId' => strval( $params['taskId'] ),
			'taskName' => strval( $params['taskName'] ),
			'serverTime' => (int)$params['serverTime'],
		);
		self::_initParam( $record );
		self::add( "finishTask", $record );
	}
	
	
	
}
