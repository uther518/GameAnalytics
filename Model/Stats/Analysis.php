<?php

/**
 * 分析系统
 * @name Model.php
 * @author admin
 * @since 2013-1-14
 */
if( !defined( 'IN_INU' ) )
{
    return;
}


class Stats_Analysis extends Stats_Base
{
	
	//服务器时间
	protected static $serverTime;
	
	//查询日期开始时间
	protected static $dayStart;
	
	//查询日期结束时间
	protected static $dayEnd;
	
	//最终统计结果
	protected static $resultData = array();
	
	//统计
	public static function doStat( $appId , $sid , $serverTime )
	{
		self::$appId = $appId;
		self::$sid = $sid;
		self::$serverTime = $serverTime;
		
		self::$dayStart = strtotime( date( "Ymd"  , $serverTime ) );
		self::$dayEnd = self::$dayStart + 86400;
	
		$day = date( "Ymd" , self::$serverTime );
		self::$resultData = array();
		self::$resultData['date'] = $day; 
	
		
		//数据统计
		self::_makeUserData();
		
		//付费统计
		self::_makeRechargeData();
		
		//元宝消收入，支出统计
		self::_makeCoinAndGoldIO();
		
		//等级分布
		self::_makeLevel();
		
		//玩法参与分析
		self::_makePlayMethod();
		
		//新手引导分析
		self::_makeNewbie();
		
		//保存统计结果
		self::_saveResultData();
	}
	
	
	/**
	 * 玩家参与度分析
	 * 参与玩家数，参与次数
	 */
	private static function _makePlayMethod()
	{
		//今天有多少个玩家参与了 各种玩法
		//全部分布
		$items = array( 'all' , 'today' );
		
		foreach ( $items as $item )
		{
			$keys = array( "methodName" => 1 );
			$initial = array(  "times" => 0 );
			$reduce = '
				function(obj, prev) {
				   prev.times++;
				}';
			
			if( $item == 'today' )
			{
				$condition = array( 'condition' => array(
					'serverTime' => array(
						'$gte' => self::$dayStart,
						'$lt' => self::$dayEnd,
					),
				));
			}
			else 
			{
				$condition = array( 'condition' => array());
			}
			
			$playMethodData[$item] = self::group( "playMethod" , $keys , $initial , $reduce , $condition );
			foreach ( $playMethodData[$item] as $key => $info )
			{
				//查每个玩法有多少玩家参与
				$query = array(
						'methodName' => strval( $info['methodName']),
				);
				
				if( $item == 'today' )
				{
					$query['serverTime'] = array(
								'$gte' => self::$dayStart,
								'$lt' => self::$dayEnd,
						);
				}
				
				
				$playMethodData[$item][$key]['uids'] = count( self::distinct( 'playMethod' , 'uid' , $query ) );
			}
		
		}
		self::$resultData['playMethod'] = $playMethodData;
	}
	
	/**
	 * 新手分布
	 */
	private static function _makeNewbie()
	{
		//查找每个等级有多少人
		
		$items = array( 'todayNewbie' , 'newbie' );
		foreach ( $items as $item )
		{
			//全部分布
			$keys = array( "newbie" => 1 );
			$initial = array(  "uids" => 0 );
			$reduce = '
				function(obj, prev) {
				   prev.uids++;
				}';
			if( $item == 'newbie' )
			{
				$condition = array( 'condition' => array());
			}
			else 
			{
				$condition = array( 'condition' => array( 
					'registerTime' => array(
						'$gte' => self::$dayStart,
						'$lt' => self::$dayEnd,
					),
				));
			}
			$newbieData['date'] = date( "Ymd" , self::$serverTime );
			$newbieData['data'] = self::group( "newUser" , $keys , $initial , $reduce , $condition );
			foreach ( $newbieData['data'] as $key => $info )
			{
				if( !$info['newbie'] )
				{
					$newbieData[$item][0] += $info['uids'];
				}
				else
				{
					$newbieData[$item][$info['newbie']] = $info['uids'];
				}
			}
			
			if( $newbieData[$item]  )
			{
				ksort( $newbieData[$item]  );
			}
			self::$resultData[$item] = $newbieData[$item] ? $newbieData[$item] : array() ;
		}
		unset( $newbieData['data'] );
	}
	
	/**
	 * 等级分布
	 */
	private static function _makeLevel()
	{
		//查找每个等级有多少人
		$keys = array( "level" => 1 );
		$initial = array(  "uids" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.uids++;
			}';
		$condition = array( 'condition' => array(
		));
		$levelData['date'] = date( "Ymd" , self::$serverTime );
		$levelData['list'] = self::group( "newUser" , $keys , $initial , $reduce , $condition );
		
		
		
		//查找今天注册的每个等级有多少人
		$initial = array(  "uids" => 0 );
		$condition = array( 'condition' => array(
			'registerTime' => array(
				'$gte' => self::$dayStart, //大于等于
				'$lt' => self::$dayEnd,    //小于
			),
		));
		$levelData['todaylist'] = self::group( "newUser" , $keys , $initial , $reduce , $condition );
		
		
		//前100名
		$cond = array(
			'sort' => array(
				'level' => -1,
			),
			'limit' => 100,
		);
		$levelData['levelTop'] = self::find( "newUser" , array() , $cond , array( 'uid' , 'nickName' , 'level') );		
		self::_saveResultData( 'levelMapData' , $levelData  );

	}
	
	/**
	 * 元宝/金币收入，支出，
	 */
	private static function _makeCoinAndGoldIO()
	{
		//按事件类型act分类
		//元宝收入统计
		$keys = array( "evtAct" => 1 );
		$initial = array( "coins" => 0 , "times" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.coins += parseInt(obj.coin);
			}';
		$condition = array( 'condition' => array(
			'serverTime' => array(
				'$gte' => self::$dayStart,
				'$lt' => self::$dayEnd,
			),
		));
		self::$resultData['gainCoin'] = self::group( "gainCoin" , $keys , $initial , $reduce , $condition );
		
		//元宝支出统计
		$initial = array( "coins" => 0 , "times" => 0 );
		self::$resultData['consumeCoin'] = self::group( "consumeCoin" , $keys , $initial , $reduce , $condition );
		
		
		//金币收入统计
		$keys = array( "evtAct" => 1 );
		$initial = array( "golds" => 0 , "times" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.golds += parseInt(obj.gold );
			}';
		$condition = array( 'condition' => array(
			'serverTime' => array(
				'$gte' => self::$dayStart,
				'$lt' => self::$dayEnd,
			),
		));
		self::$resultData['gainGold'] = self::group( "gainGold" , $keys , $initial , $reduce , $condition );
		
		//元宝支出统计
		$initial = array( "golds" => 0 , "times" => 0 );
		self::$resultData['consumeGold'] = self::group( "consumeGold" , $keys , $initial , $reduce , $condition );

	}
	
	/**
	 * 分渠道统计:新增用户，当日登录用户，留存率
	 */
	private static function _makeUserData()
	{
		//查询渠道列表
		$queryRefers = self::distinct( 'newUser',  'downRefer'  );
		if( $queryRefers )
		{
			foreach ( $queryRefers as $eachRefer )
			{
				if( $eachRefer )
				{
					$downRefers[] = $eachRefer;
				}
			}
		}
		self::$resultData['channals'] = $downRefers;
		
		//总用户数
		self::$resultData['allUserNum'] = self::count( 'newUser' );
		//总付费用户数
		self::$resultData['rechargeUsers'] = count(  self::distinct( 'recharge', 'uid' , array() ) );
		//总付费笔数，总付费额

		//金币收入统计
		$keys = array( );
		$initial = array( "rmbs" => 0 , "times" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.rmbs += parseInt(obj.rmb);
			}';
			$condition = array( );
		$rs  = self::group( "recharge" , $keys , $initial , $reduce , $condition );
		self::$resultData['rechargeMoneys'] = $rs[0]['rmbs'];
		self::$resultData['rechargeTimes'] = $rs[0]['times'];
	
		
		//Array ( [0] => 官网 [1] => 十字猫 ) 
		
		//分渠道统计
		if( $downRefers )
		{
			foreach ( $downRefers as $refer )
			{
				//新增用户
				$query = array(
					'downRefer' => $refer,
					'registerTime' => array(
						'$gte' => self::$dayStart, //大于等于
						'$lt' => self::$dayEnd,    //小于
					),
				);
				self::$resultData['newUser'][$refer] = self::count( "newUser" , $query );
				self::$resultData['newUser']['total'] += self::$resultData['newUser'][$refer];
				
				//新增设备
				self::$resultData['newUserDevice'][$refer] = count( self::distinct( 'newUser', 'mac' , $query ) );
				self::$resultData['newUserDevice']['total'] += self::$resultData['newUserDevice'][$refer];
				
				
				//登录用户
				$query = array(
					'downRefer' => $refer,
					'loginTime' => array(
							'$gte' => self::$dayStart, //大于等于
							'$lt' => self::$dayEnd,    //小于
					),
				);
				//登录设备次数
				self::$resultData['userLoginDevice'][$refer] = count( self::distinct( 'userLogin', 'mac' , $query ) );
				self::$resultData['userLoginDevice']['total'] += self::$resultData['userLoginDevice'][$refer];
				
				//登录人次
				self::$resultData['userLogin'][$refer] = count( self::distinct( 'userLogin', 'uid' , $query ) );
				self::$resultData['userLogin']['total'] += self::$resultData['userLogin'][$refer];	
			}
		}
		
		//更新次日，3天，4日。。。30天前的留存率
		$keepLoginMenu = array( 2,3,4,5,6,7,8,14,30 );
		foreach ( $keepLoginMenu as $nDays )
		{
			$registerStart = self::$dayStart - 86400*( $nDays - 1 );
			$registerEnd   = $registerStart + 86400;
			
			self::$resultData['keepLogins'][$nDays] = 0;
			
			
			//N天前注册，今天登录的玩家数量
			$query = array(
				'registerTime' => array(
					'$gte' => $registerStart, //大于等于
					'$lt' => $registerEnd,    //小于
				),
				'loginTime' => array(
					'$gte' => self::$dayStart, //大于等于
					'$lt' => self::$dayEnd,    //小于
				),	
			);
			
			$regisDay = date( "Ymd" , $registerStart );
			$keepNdaysNum = count( self::distinct( 'userLogin', 'uid' , $query ) );
		
		
			//更新几天前的N日留存
			self::_updateKeepLogins( $regisDay, $nDays, $keepNdaysNum );	
		}	
	}
	
	
	/**
	 * 当日付费分析
	 * 收入金额/充值次数/充值人数  按渠道统计  sum,count,distinct
	 * 付费等级/付费率/
	 */
	private static function _makeRechargeData()
	{
		//分渠道统计充值数量
		$keys = array( "downRefer" => 1 );
		$initial = array( "coins" => 0 , "rmbs" => 0 , "times" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.coins += parseInt(obj.coin);
			   prev.rmbs += parseInt(obj.rmb);
			}';
		$condition = array( 'condition' => array(
			'serverTime' => array(
				'$gte' => self::$dayStart,
				'$lt' => self::$dayEnd,
			),
		));
		self::$resultData['channalCharge'] = self::group( "recharge" , $keys , $initial , $reduce , $condition );
		
		
		foreach ( self::$resultData['channalCharge'] as $ei => $data )
		{
			//付费用户人数
			$condition['condition']['downRefer'] = $data['downRefer'];
			self::$resultData['channalCharge'][$ei]['uids'] = count( self::distinct( 'recharge', 'uid' , $condition['condition'] ) );
			//当天总付费用户数
			self::$resultData['chargeUidTotal'] += self::$resultData['channalCharge'][$ei]['uids'];
			//充值额
			self::$resultData['chargeRmbTotal'] += self::$resultData['channalCharge'][$ei]['rmbs'];
			
		}
		
		//按支付类型统计统值数(支付宝/财富通)
		$keys = array( "payType" => 1 );
		$initial = array( "coins" => 0 , "rmbs" => 0 , "times" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.coins += parseInt(obj.coin);
			   prev.rmbs += parseInt(obj.rmb);
			}';
		$condition = array( 'condition' => array(
			'serverTime' => array(
				'$gte' => self::$dayStart,
				'$lt' => self::$dayEnd,
			),
		));
		self::$resultData['payTypeChange'] = self::group( "recharge" , $keys , $initial , $reduce , $condition );
		
		//单个用户次数
		foreach ( self::$resultData['payTypeChange'] as $ei => $data )
		{
			$condition['condition']['payType'] = $data['payType'];
			//付费用户人数
			self::$resultData['payTypeChange'][$ei]['uids'] = count( self::distinct( 'recharge', 'uid' , $condition['condition'] ) );
		}
		
		//日付费率( 当天付费人数/日DAU )
		//日ARPU:日消费额除以日DAU   用来衡量每一用户带来的平均收益
		//日ARPPU:日消费额除以日付费用户
		if( self::$resultData['userLogin']['total']  > 0 )
		{
			self::$resultData['chargeRate']= sprintf( '%.4f' , self::$resultData['chargeUidTotal']  / self::$resultData['userLogin']['total'] );
			self::$resultData['darpu']= round( self::$resultData['chargeRmbTotal']  / self::$resultData['userLogin']['total'] , 2 );
			if(  self::$resultData['chargeUidTotal'] > 0 )
			{
				self::$resultData['darppu']= round(  self::$resultData['chargeRmbTotal']  / self::$resultData['chargeUidTotal'] , 2 );
			}
			else 
			{
				self::$resultData['darppu'] = 0;
			}
		}
		else
		{
			self::$resultData['chargeRate'] = self::$resultData['darpu'] =  self::$resultData['darppu'] =  0;
		}	
	
	}
	
	
	
	/**
	 * 更新留存数据
	 * @param unknown $date
	 * @param unknown $nday
	 * @param unknown $num
	 */
	private static function _updateKeepLogins( $date , $nday , $num )
	{
        $query = array(
        	'date' => $date,
        );
        $oldData = self::findOne( "resultData" , $query );        
        if( $oldData )
        {
        	$oldData['keepLogins'][$nday] = $num;
			self::update( "resultData", $query, $oldData );
        }
	}
	
	/**
	 * 保存所有数据
	 */
	private static function _saveResultData( $collect = "resultData" , $data = array() )
	{

		$data = empty( $data ) ? self::$resultData : $data;		
		$query = array(
			'date' => $data['date'],
		);
	
		$result = self::findOne( $collect , $query );
		if( $result )
		{
			$rs = self::update( $collect , $query , $data );
		}
		else 
		{
			$rs = self::add( $collect , $data );
		}
	}
	

	
}
