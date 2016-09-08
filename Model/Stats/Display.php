<?php
/**
 * 数据统计显示
 * 
 * @name Display.php
 * @author liuchangbing
 * @since 2013-07-25
 */
if( !defined( 'IN_INU' ) )
{
    return;
}

//图表显示配置


class Stats_Display extends Stats_Base
{
	
	//图表显示配置
	static $chartConf;
	
	//模块名
	static $action;
	
	//查询开始日期
	static $startDate;
	
	//查询结束日期
	static $endDate;
	
	//自定义参数数组
	static $params;
	
	//返回数据
	static $returnData = array();
	
	/**
	 * 显示统计数据
	 * @param unknown $action
	 */
	public static function doDisplay( $action , $startDate , $endDate , $params = array() )
	{
		//Stats_Query::run();
		//exit;
		
		self::$appId = $_SESSION['currAppId'];
		self::$sid = $_SESSION['currSid'];
		
		include 'ChartConfig.php';
		self::$chartConf = &$chartConfig;
		
		self::$action = $action;
		
		$startDate = str_replace( "-", "", $startDate );
		$endDate = str_replace( "-", "", $endDate );
		
		self::$startDate = $startDate ? $startDate : date( "Ymd" , strtotime( "-7 day" ) );
		self::$endDate = $endDate ? $endDate : date( "Ymd" );
		
		self::$params = $params;
		
		//查询统计数据
		$action = ucfirst( $action );
		$formatFuncName = "_format{$action}";
	
		/**
		 * 查询数据并格式化输出
		 */
		if( $params['notAutoQuery'] != 1 )
		{
			self::_queryMongoData();
		}
		self::$formatFuncName();
		
		unset( self::$returnData['mongo'] );
		return self::$returnData;
	}
	
	/**
	 * 查询自定义事件
	 */
	private static function _formatCustomActQuery()
	{
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$start = ( $page - 1 ) * 30;
		$queryCond = array();
		if( $_GET['act'] == 'counter' )
		{
			$queryCond['date'] = array(
					'$gte' => (int)self::$startDate ,
					'$lte' => (int)self::$endDate ,
			);
			
			$filter = array(
					'start' => $start,
					'limit' => 30,
					'sort' => array(
						'date' => -1,
					),
			);
			
			$table = "evtCounter";
		}
		else 
		{
			$queryCond['serverTime'] = array(
					'$gte' => strtotime( self::$startDate ),
					'$lte' => strtotime( self::$endDate ) + 86400,
			);
			
			$filter = array(
					'start' => $start,
					'limit' => 30,
					'sort' => array(
							'serverTime' => -1,
					),
			);
			
			$table = "customAction";
		}
		
		if( $_GET['sUid'] && $_GET['sUid'] != '用户UID' )
		{
			$queryCond['uid'] = intval( $_GET['sUid'] );
		}
		
		if( $_GET['sAct'] && $_GET['sAct'] != '事件名称' )
		{
			$queryCond['act'] = trim( $_GET['sAct'] );
			
			//事件发生总次数
			$keys = array();
			$initial = array( 'nums' => 0 );
			$reduce = '
			function(obj, prev) {
			   prev.nums += parseInt(obj.num );
			}';
			$rs = @self::group( $table , $keys, $initial, $reduce , $queryCond );
		}
		
		
		
		
		self::$returnData['records'] = self::find( $table , $queryCond , $filter );
	;
		
		self::$returnData['count'] =   self::count( $table , $queryCond  );
		self::$returnData['evtTotal'] =  $rs[0]['nums'] ? $rs[0]['nums'] : '-' ;
		//print_r( self::$returnData );exit;
		
	}
	
	
	/**
	 * 格式化参与度
	 */
	private static function _formatPlayMethod()
	{
		$xAxis['categories'] = array();
		$items = array( 'all' , 'today' );
		$resultData = array();
		$act = $_GET['act'];
		
		foreach ( $items as $item )
		{
			foreach (  self::$returnData['mongo'] as $dateData )
			{
				if(  $dateData['playMethod'][$item]  )
				{
					foreach ( $dateData['playMethod'][$item] as $info )
					{
						$i = 0;
						$startDate = self::$startDate;
						while( $startDate <= self::$endDate )
						{
							if( !in_array( $startDate , $xAxis['categories'] ))
							{
								$xAxis['categories'][] = $startDate;
							}
							
							//计算每日数量
							//$resultData[$item][$startDate] = 0;
							//echo $startDate;
							//echo "\n";
							$resultData[$item][$info['methodName']]['data'][$i] = 0;
							if( $dateData['date'] == $startDate )
							{
								    $resultData[$item][$info['methodName']]['name'] = $info['methodName'];
									$resultData[$item][$info['methodName']]['data'][$i] = $info[$act] ?  $info[$act] : 0;	
							}
							$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
							$i++;
						}
					}
				}
			}
			@sort(  $resultData[$item] );
		}
		
		self::$returnData = $resultData;
		self::$returnData['xAxis'] = $xAxis;
		
		unset( self::$returnData['mongo'] );
		
	}
	
	
	/**
	 * 月付费统计
	 */
	private static function _formatMRechargeRate()
	{
		$xAxis['categories'] = array();
		
		$monQueryResult = array();
	
			
		$rechargeData = array();
		$i = 0;
		$startDate = self::$startDate;
		while( $startDate <= self::$endDate )
		{
			$ym = date( "Ym" , strtotime( $startDate ) );
			if( !in_array( $ym , $xAxis['categories'] ))
			{
				$xAxis['categories'][] = $ym;
			}
			else 
			{
				continue;
			}

			
			if( !isset( $monQueryResult[$ym] ) )
			{
				$startStr = $ym."01";
				$startTime = strtotime( $startStr );
				$endStr = $ym.date( "t" , $startTime );
				
				$keys = array();
				$initial = array( 'users' => 0 , 'rmbs' => 0 );
				$reduce = '
					function(obj, prev) {
					   prev.users += parseInt(obj.chargeUidTotal);
					   prev.rmbs += parseInt(obj.chargeRmbTotal);
					}';
				$queryCond = array();
				$queryCond['date'] = array(
					'$gte' => $startStr,
					'$lte' => $endStr,
				);
				$rs = @self::group( "resultData" , $keys, $initial, $reduce , $queryCond );
				$monQueryResult[$ym] = $rs[0];
			}
			
			
			$monQueryResult[$ym]['users'] = ( $monQueryResult[$ym]['users'] <= 0 ) ? 0 : $monQueryResult[$ym]['users'];
			$monQueryResult[$ym]['rmbs'] =  ( $monQueryResult[$ym]['rmbs'] <= 0  )  ? 0 : $monQueryResult[$ym]['rmbs'];
			
			
			$mau =  self::getMau( $ym );
			$rechargeData['chargeRate'][$i] = $mau > 0 ? sprintf( "%.4f" ,  $monQueryResult[$ym]['users'] /$mau )*100 : 0;
			$rechargeData['chargeRate'][$i] = floatval( $rechargeData['chargeRate'][$i] );
			
			//月付费额/MAU
			$rechargeData['marpu'][$i] = $mau > 0 ? sprintf( "%.4f" ,  $monQueryResult[$ym]['rmbs'] /$mau ) : 0;
			$rechargeData['marpu'][$i] = floatval( $rechargeData['marpu'][$i] * $_SESSION['serverInfo']['rmbRate']);
		
			//月消费额除以月付费用户
			$rechargeData['marppu'][$i] = ( $monQueryResult[$ym]['users'] ) > 0 ? sprintf( "%.4f" ,  $monQueryResult[$ym]['rmbs'] /$monQueryResult[$ym]['users'] ) : 0;
			$rechargeData['marppu'][$i] = floatval( $rechargeData['marppu'][$i] * $_SESSION['serverInfo']['rmbRate'] );

			$i++;
			$startDate = date( "Ymd" , strtotime( "+1 month" , strtotime( $startDate ) ));
		}
			
		self::$returnData['xAxis'] = $xAxis;
		self::$returnData['chargeRate'][] = array(
				'name' => "月付费率",
				'data' => $rechargeData['chargeRate'],
		);
		
		self::$returnData['marpu'][] = array(
				'name' => "月ARPU(单位:{$_SESSION['serverInfo']['currencyUnit']})",
				'data' => $rechargeData['marpu'],
		);
	
		self::$returnData['marppu'][] = array(
				'name' => "月ARPPU(单位:{$_SESSION['serverInfo']['currencyUnit']})",
				'data' => $rechargeData['marppu'],
		);	
		unset(  self::$returnData['mongo']  );

	}
	
	
	
	/**
	 * DAU/MAU
	 */
	private static function _formatDmau()
	{
		$startDate = self::$startDate;
		$data = array();
		$mauArr = array();
		$dmauArr = array();
		//'xAxis' => array( 'categories' =>  $unitStart ) ,
		
		$xAxis['categories'] = array();
		$i = 0;
		$xAxis = array();
		while( $startDate <= self::$endDate )
		{
			if( !in_array( $startDate , $xAxis ) )
			{
				$xAxis[] = $startDate;
			}
			
			$currMon = date( "Ym" , strtotime( $startDate ) );
			if( !isset( $mauArr[$currMon] ) )
			{
				$mauArr[$currMon] = self::getMau( $currMon );
			}
			$dmauArr[$i] = 0;
			
			foreach ( self::$returnData['mongo'] as $dateData )
			{
				if( $startDate == $dateData['date']  )
				{
					if( $mauArr[$currMon] > 0 )
					{
						$dmauArr[$i] = (float)sprintf( "%.2f" , $dateData['userLogin']['total']/$mauArr[$currMon] );
					}
					else
					{
						$dmauArr[$i] = 0;
					}
				}
			}
			$i++;
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
		}
		

		self::$returnData['dmau'] = array(
					'xAxis' => array( 'categories' =>  $xAxis ) ,
					'yAxis' => array( array(
							'name' => "DAU/MAU",
							'data' =>  $dmauArr ,
					)
				),
		);
		
		unset( self::$returnData['mongo'] );
	}
	
	
	/**
	 * 获取MAU
	 * @param unknown $datestr
	 */
	private static function getMau( $currMon )
	{
		$startStr = $currMon."01";
		$startTime = strtotime( $startStr );
		$endStr = $currMon.date( "t" , $startTime );

		
		
		$keys = array();
		$initial = array( 'nums' => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.nums += parseInt(obj.userLogin.total);
			}';
		$queryCond = array();
		$queryCond['date'] = array(
			'$gte' => $startStr,
			'$lte' => $endStr,
		);
		$rs = @self::group( "resultData" , $keys, $initial, $reduce , $queryCond );
		
		return $rs[0]['nums'] ? $rs[0]['nums'] : 0;
	}
	
	
	
	/**
	 * 周活跃/月活跃
	 */
	private static function _formatWmau()
	{
		$disData = array();
		
		//$tUnit = 'm';
		$act = $_GET['f'];		
		$tUnit = ( $act == 'wau') ? 'W' : 'm';
		
		$startWeek = date( $tUnit , strtotime( self::$startDate ) );
		$endWeek   = date( $tUnit , strtotime( self::$endDate ) );

		$startDate = self::$startDate;
		$dataUnits= array();
		$unitStart = array();
		
		while( $startDate <= self::$endDate )
		{
			$currWeek = date( $tUnit , strtotime( $startDate ) );
			$disData[$act][$currWeek] = 0;
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
		}
		
		$i = 0;
		$startDate = self::$startDate;
		while( $startDate <= self::$endDate )
		{
			$currWeek = date( $tUnit , strtotime( $startDate ) );	
			if( !in_array( $currWeek , $dataUnits ) )
			{
				$unitStart[] = $startDate;
				$dataUnits[] = $currWeek;
			}
			
			foreach ( self::$returnData['mongo'] as $dateData )
			{
				if( $startDate == $dateData['date'] )
				{
					$disData[$act][$currWeek] += $dateData['userLogin']['total'];
				}
			}
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
		}
		
		$disData[$act] = array_values( $disData[$act] );	
		$name = ( $act == 'wau') ? '周活跃玩家数' : '月活跃玩家数';
		
		self::$returnData[$act] = array(
			'xAxis' => array( 'categories' =>  $unitStart ) ,
			'yAxis' => array( array( 
				'name' => $name,
				'data' =>  $disData[$act] ,
					)
			),
		);
		
		unset(   self::$returnData['mongo'] );
	}
	
	/**
	 * 充值IO
	 */
	private static function _formatCurrencyTotal()
	{
		//充值币收入
		$startDate = self::$startDate;
		$xAxis['categories'] = array();

		self::$returnData['coinTotal'][0]['name'] = '剩余充值币数';
		self::$returnData['goldTotal'][0]['name'] = '剩余游戏币数';
		
		$i = 0;
		while( $startDate <= self::$endDate )
		{
			if( !in_array( $startDate , $xAxis['categories'] ))
			{
				$xAxis['categories'][] = $startDate;
			}
			
			self::$returnData['coinTotal'][0]['data'][$i] = 0;
			self::$returnData['goldTotal'][0]['data'][$i] = 0;
			
			foreach ( self::$returnData['mongo'] as $dateData )
			{
				if( $startDate == $dateData['date'] )
				{
					self::$returnData['coinTotal'][0]['data'][$i] =  $dateData['coinTotal'] ? $dateData['coinTotal'] : 0 ;
					self::$returnData['goldTotal'][0]['data'][$i] =  $dateData['coinTotal'] ? $dateData['coinTotal'] : 0 ;
				}
			}
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
			$i++;
		}
		
		self::$returnData['xAxis'] = $xAxis;
		unset(   self::$returnData['mongo'] );
	}
	
	
	
	/**
	 * 道具获取分布
	 */
	private static function _formatItemLostRank()
	{
		self::_formatItemGainRank();
	}
	private static function _formatItemGainRank()
	{
		$keys = array( 'itemId'=> 1 );
		$initial = array( 'times' => 0 , 'nums' => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.nums += parseInt(obj.itemNum);
			}';
		
		
		$queryCond = array();
		$queryCond['serverTime'] = array(
			'$gte' => strtotime( self::$startDate ),
			'$lte' => strtotime( self::$endDate ) + 86400,
		);
		
		if( $_GET['f'] == 'itemLostRank' )
		{
			$table = 'lostItem';
			$sumName = '使用道具数量';
			$timesName = '使用道具次数';
		}
		else 
		{
			$sumName = '道具获得数量';
			$timesName = '道具获得次数';
			$table = 'gainItem';
		}
		
		$rs = @self::group( $table , $keys, $initial, $reduce , $queryCond );	
	
		$sumArr = $timesArr = $cate = array();
		foreach ( $rs as $record )
		{
			$cate[] = $record['itemId'];
			$sumArr[] = $record['nums'];
			$timesArr[] = $record['times'];
			
		}
		
		self::$returnData['xAxis']['categories'] = $cate;
		self::$returnData['sum']['series'][0]['name'] = $sumName;
		self::$returnData['sum']['series'][0]['data'] = $sumArr;
		
		self::$returnData['times']['series'][0]['name'] = $timesName;
		self::$returnData['times']['series'][0]['data'] = $timesArr;
	}
	
	/**
	 * 道具获取，使用记录
	 */
	private static function _formatLostItem()
	{
		self::_formatGainItem();
	}
	
	private static function _formatGainItem()
	{
		$queryCond = array();
		$queryCond['serverTime'] = array(
				'$gte' => strtotime( self::$startDate ),
				'$lte' => strtotime( self::$endDate ) + 86400,
		);
		
		
		if( $_GET['sUid'] && $_GET['sUid'] != '用户UID' )
		{
			$queryCond['uid'] = intval( $_GET['sUid'] );
		}
		
		if( $_GET['sItemId'] && $_GET['sItemId'] != '道具ID' )
		{
			$queryCond['itemId'] = trim( $_GET['sItemId'] );
		}
		
		
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$start = ( $page - 1 ) * 30;
		$filter = array(
			'start' => $start,
			'limit' => 30,
			'sort' => array(
					'serverTime' => -1,
			),
		);
		$action = $_GET['f'];
		
		
		//创建索引
		$indexCond = array(
			'serverTime' => 1,
		);
		self::index( $action , $indexCond );
		
		self::$returnData['records'] = self::find( $action , $queryCond , $filter );
		self::$returnData['count'] =   self::count( $action , $queryCond  );
		//print_r( self::$returnData );exit;
	}
	
	
	/**
	 * 充值记录
	 */
	private static function _formatRechargeRecord()
	{
		$queryCond = array();
		
		$queryCond['serverTime'] = array(
			'$gte' => strtotime( self::$startDate ),
			'$lte' => strtotime( self::$endDate ) + 86400,
		);
		
		if( $_GET['sUid'] && $_GET['sUid'] != '用户UID' )
		{
			$queryCond['uid'] = intval( $_GET['sUid'] );
		}
		
		if( $_GET['sPayType'] && $_GET['sPayType'] != '支付类型' )
		{
			$queryCond['payType'] = trim( $_GET['sPayType'] );
		}
		
		
		//管理员只可见该渠道
		if( $_SESSION['adminInfo']['userType'] == 2 )
		{
			$viewChanals = $_SESSION['adminInfo']['channals'][self::$appId];
			//取交集
			$queryCond['downRefer'] = array(
				'$in' => $viewChanals,
			);
		}
		
		
		//全部分布
		$keys = array();
		$initial = array(  "times" => 0 , "sumRmb" => 0 );
		$reduce = '
			function(obj, prev) {
			   prev.times++;
			   prev.sumRmb += parseInt(obj.rmb);
			}';
		$group = @self::group( "recharge" , $keys , $initial , $reduce , $queryCond );
		
		
		//$_SESSION['serverInfo']['currencyUnit']}
		
		self::$returnData['count'] = $group[0]['times'];
		//判断币种和人民币竞换率
		
		self::$returnData['money'] = $group[0]['sumRmb'] * $_SESSION['serverInfo']['rmbRate'];
		self::$returnData['uids'] = count( self::distinct( "recharge", "uid" , $queryCond ));
		
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$start = ( $page - 1 ) * 30;
		
		
		$filter = array(
			'start' => $start,
			'limit' => 30,
			'sort' => array( 
				'serverTime' => -1,
			 ),
		);
					
		self::$returnData['records'] = self::find( "recharge" , $queryCond , $filter );
	
	}
	

	/**
	 * 新手引导无操作率
	 */
	private static function _formatNewbieNoAct()
	{
		$xAxis = array();
		$startDate = self::$startDate;
		$i = 0;
		$items = array( 'newbie' , 'todayNewbie' );
		$series = array();
		while( $startDate <= self::$endDate )
		{
			if( !in_array( $startDate , $xAxis ))
			{
				$xAxis[] = $startDate;
			}
			
			$series['newbie'][$i] = 0;
			$series['todayNewbie'][$i] = 0;
			
			foreach ( self::$returnData['mongo'] as $dbData  )
			{
				if( $startDate == $dbData['date'] )
				{
					//历史无操作率=
					if( $dbData['allUserNum'] > 0  )
					{
						$series['newbie'][$i]=  sprintf( "%.4f" , $dbData['newbie'][0] / $dbData['allUserNum'] ) * 100;
					}
					else 
					{
						$series['newbie'][$i] = 0;
					}
					
					if( $dbData['newUser']['total'] > 0  )
					{
						$series['todayNewbie'][$i]=  sprintf( "%.4f" , $dbData['todayNewbie'][0] /  $dbData['newUser']['total']  ) * 100;
					}
					else
					{
						$series['todayNewbie'][$i] = 0;
					}
				}
			}
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
			$i++;
		}
		
		self::$returnData['newbie']['xAxis']['categories'] = $xAxis;
		self::$returnData['todayNewbie']['xAxis']['categories'] = $xAxis;
		
		self::$returnData['newbie']['yName'] = '无操作率百分比%';
		self::$returnData['newbie']['series'][0]['name'] = "用户无操作率";
		self::$returnData['newbie']['series'][0]['data'] = $series['newbie'];
		
		self::$returnData['todayNewbie']['yName'] = '新手无操作率百分比%';
		self::$returnData['todayNewbie']['series'][0]['name'] = "新手无操作率";
		self::$returnData['todayNewbie']['series'][0]['data'] = $series['todayNewbie'];
		
	}	
	
	/**
	 * 新手引导分布
	 * 新手转化率
	 * 新手无操作率
	 */
	private static function _formatNewbie()
	{
		/**
		 * xAxis:{"categories":["20130730","20130731","20130801","20130802","20130803","20130804","20130805","20130806","20130807"]},
            series:[{"name":"\u7559\u5b58\u7387","data":[0,0,0,0,0,1586.67,0,0,0]}] 
		 */
		$serverInfo = self::serverInfo();
		$maxNewbie = $serverInfo['maxNewbieStep'];
		$items = array( 'newbie' , 'todayNewbie' );	
		
		foreach ( $items as $item )
		{
			$newbies = self::$returnData['mongo'][0][$item];
			$newbieTurn = $newbiex =  array();
			for ( $i=0; $i<= $maxNewbie ; $i++ )
			{
				if( !$newbies[$i] ) 
				{
					$newbies[$i] = 0;
				}
				
				$newbiex[] = $i;
				/*
				//转化率，当前新手用户数/上一步用户数
				if( $newbies[$i-1] <= 0 )
				{	
					$newbieTurn[$i] = 0;
				}
				else 
				{
					//第一步，为1-6/0-6，第二步2-6/1-6，第三步3-6/2-6
					//$newbieTurn[$i] = sprintf( '%.2f' , $newbies[$i] / $newbies[$i-1] ) *100;
					$newbieTurn[$i] = self::getNewbieTurn( $i , $maxNewbie , 0 , $newbies );
				}
				*/
				 $newbieTurn[$i] = self::getNewbieTurn( $i , $maxNewbie , 0 , $newbies );
			}
			ksort( $newbies ); 
			self::$returnData[$item]['xAxis']['categories'] = $newbiex;
			if( $_GET['act'] == 'newbieMap' )
			{
				self::$returnData[$item]['series'][0]['name'] = "新手引导进度";
				self::$returnData[$item]['yName'] = '进度人数';
				self::$returnData[$item]['series'][0]['data'] = array_values( $newbies );
			}
			else 
			{
				self::$returnData[$item]['yName'] = '转化率百分比%';
				self::$returnData[$item]['series'][0]['name'] = "新手引导转化率";
				self::$returnData[$item]['series'][0]['data'] = array_values( $newbieTurn );
				
			}
		
		}
	}
	
	/**
	 * 获取新手引导转化率
	 * @param unknown $step
	 * @param unknown $maxStep
	 * @param unknown $initStep
	 */
	private static function getNewbieTurn( $step , $maxStep , $initStep , $data )
	{
		////第一步，为1-6/0-6，第二步2-6/1-6，第三步3-6/2-6
		//print_r( $data );exit;
		$x = $y = 0;
		foreach ( $data as $index => $num )
		{
			if( $index >= $step && $index <= $maxStep )
			{
				
				$x += $data[$index];
			}
			
			if( $index >= 0 && $index <= $maxStep )
			{
				
				$y += $data[$index];
			}
		}


//echo  $maxStep ;exit;
		if( $y > 0 )
		{
		return sprintf( "%.4f" , $x/$y ) * 100;
		}
		return 0;
	}

	/**
	 * 等级分部
	 */
	private static function _formatLevelMap()
	{
		$act = $_GET['act'];
		if( $act == 'levelTop')
		{
			$levelTop = array();
			$rank = 1;
			if(  self::$returnData['mongo'][0]['levelTop'] )
			{
				foreach (  self::$returnData['mongo'][0]['levelTop'] as $info )
				{
					$levelTop[] = array(
						'nickName' => $info['nickName'] ? $info['nickName'] : '玩家'.$info['uid'],
						'uid' => $info['uid'],
						'level' => $info['level'],
						'rank' => $rank,
					);
					
					$rank++;
				}
			}
			self::$returnData['showData']['levelTop'] = $levelTop;
		}
		else 
		{
		
			//如果最大等级/5 超过20，则除以10
			$levelList = $todayLevelList =  array();
			$maxLevel = 1;
			if( self::$returnData['mongo'][0]['list'] )
			{
				foreach (  self::$returnData['mongo'][0]['list'] as $info )
				{
					$levelList[$info['level']] = $info['uids'];
					if( $info['level'] > $maxLevel )
					{
						$maxLevel = $info['level'];
					}
				}
			}
			
			$levelUnit = 5;
			if( $maxLevel / 5 > 20 )
			{
				$levelUnit = 10;
			}
			
			$levelMap = array();
			for ( $i = 1 ; $i <= $maxLevel ; $i++ )
			{
				$levelList[$i] = $levelList[$i] ? $levelList[$i] : 0;
			}
			ksort( $levelList );
			self::$returnData['showData']['levelMap'][0]['name'] = "等级分布";
			
			foreach ( $levelList as $level => $num )
			{
				self::$returnData['showData']['x']['categories'][] = strval( $level );
				self::$returnData['showData']['levelMap'][0]['data'][] = $num;
			}
				
	
			//当天注册的的用户等级分布
			if( self::$returnData['mongo'][0]['todaylist'] )
			{
				foreach (  self::$returnData['mongo'][0]['todaylist'] as $info )
				{
					$todayLevelList[$info['level']] = $info['uids'];
				
				}
			}
		
			
			$todayLevelMap = array();
			for ( $i = 1 ; $i <= $maxLevel ; $i++ )
			{
				$todayLevelList[$i] = $todayLevelList[$i] ? $todayLevelList[$i] : 0;
			}
			ksort( $todayLevelList );
			self::$returnData['showData']['todayLevelMap'][0]['name'] = "等级分布";
			
			foreach ( $todayLevelList as $level => $num )
			{
				self::$returnData['showData']['todayLevelMap'][0]['data'][] = $num;
			}
			
			
			//等级段分部
			//self::$returnData['showData']['levelUnit'][0]['name'] = "等级段分布";
			$levelUnitMap = array();
			$levelUnitData = array();
			foreach ( $levelList as $level => $num )
			{
				$index = intval( ($level-1)/$levelUnit );
				
				$min = $index*$levelUnit + 1;
				$max = ( $index+1 ) * $levelUnit;
				if(  !$levelUnitMap[$index] )
				{
					$levelUnitMap[$index] = "{$min}-{$max}级";
				}
			
				$levelUnitData[$index] += $num;
			}
			
			self::$returnData['showData']['xUnit']['categories'] = $levelUnitMap;
			self::$returnData['showData']['levelUnit'][0]['name'] = "用户数量";
			self::$returnData['showData']['levelUnit'][0]['data'] = $levelUnitData;
		}
		
		unset( self::$returnData['mongo'] );
	}
	
	
	/**
	 * 货币支出登录
	 */
	private static function _formatCurrencyIO()
	{
		//充值币收入
		$startDate = self::$startDate;
		$xAxis['categories'] = array();
		$items = array( 
			 'gainCoin' => 'coins',  'consumeCoin' => 'coins' ,
			 'gainGold' => 'golds' , 'consumeGold' => 'golds' ,
		);
		$i = 0;
		while( $startDate <= self::$endDate )
		{
			if( !in_array( $startDate , $xAxis['categories'] ))
			{
				$xAxis['categories'][] = $startDate;
			}
			
			foreach ( self::$returnData['mongo']  as $dateData )
			{
				
				foreach ( $items as $item => $type )
				{
					if( $startDate == $dateData['date'] )
					{
						if( $dateData[$item] )
						{
							foreach ( $dateData[$item] as $info )
							{
								self::$returnData[$item][$info['evtAct']][$type][$i] =  $info[$type]; 
								self::$returnData[$item][$info['evtAct']]['times'][$i] =  $info['times'];
							}
						}
					}
				}
			}
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
			$i++;
		}
	
		self::$returnData['xAxis'] = $xAxis;
		unset(   self::$returnData['mongo'] );

		//print_r( self::$returnData );exit;
	}
	
	/**
	 * 在线统计
	 */
	private static function _formatOnlineStats()
	{
		self::$action = 'userOnline';
		self::_queryMongoData();
	
		$dayAcu = $dayPcu = array();

		//有多少个五分钟
		$xAxis['categories'] = array();
		$onlineSum = 0;
		$startDate = self::$startDate;
		$i = 0;
		while (  $startDate <= self::$endDate )
		{
			if( !in_array( $startDate , $xAxis['categories'] ))
			{
				$xAxis['categories'][] = $startDate;
			
			}
			$dayAcu[$i] = $dayPcu[$i] = 0;
			
			foreach ( self::$returnData['mongo'] as $mongoData )
			{
				if( $mongoData['date'] == $startDate )
				{
					$onlineSum +=  array_sum(  $mongoData['online'] );
					$dayAcu[$i] = (float)sprintf( "%.2f" , array_sum(  $mongoData['online'] )/288 );
					$dayPcu[$i] = (int)max(  $mongoData['online'] );
				}
			}
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
			$i++;
		}
		
	
		$n5min = ( strtotime( self::$endDate ) - strtotime( self::$startDate ) + 86400 ) / 300;
		self::$returnData['acuAll'] = sprintf( "%.2f" , $onlineSum / $n5min );
		self::$returnData['pcuAll'] = max( $dayPcu );
		
		self::$returnData['xAxis'] = $xAxis;
		
		self::$returnData['acu'][0] = array(
			'name' => 'ACU',
			'data' => $dayAcu,
		);
		
		self::$returnData['pcu'][0] = array(
				'name' => 'PCU',
				'data' => $dayPcu,
		);
		
		unset(   self::$returnData['mongo'] );
		
		
	}
	
	/**
	 * 格式化在线用户
	 */
	private static function _formatUserOnline()
	{
		//给这么长的数组起个别名叫$online
		$online = &self::$returnData['mongo'][0]['online'];
		//查当前日期一天，并查询前一天数据与其合并，
		
		$showData = array();
		//转化一下时间单位
		if( $online )
		{
			foreach ( $online as $hi => $num )
			{
				$dateStr = self::$endDate.$hi;
				//转化为微秒
				$us = strtotime( $dateStr )*1000;
				$s = strtotime( $dateStr );
				$showData[$s] = array(
					'x' => $us,
					'y' => $num,
				);
			}
			
		}
	
		self::$returnData['userOnline'] = $showData;
		unset( self::$returnData['mongo']  );
	}
	
	/**
	 * 冲值统计：按支付类型分
	 */
	private static function _formatPayType()
	{
		//按支付类型分
		$types = array( 'total' );
		foreach ( self::$returnData['mongo'] as $data )
		{
			foreach ( $data['payTypeChange'] as $type )
			{
				if( !in_array( $type['payType'] , $types ))
				{
					$types[] = $type['payType'];
				}
			}
		}

		$items = array( 'coins' , 'rmbs' , 'times' , 'uids' );
		foreach ( $items as $item )
		{
			//统计项目变量名称
			$uItem = ucfirst( $item );
			$itemVar = 'showType'.$uItem;
			$itemData  = array();
			
			$xAxis['categories'] = array();
			
			//分渠道记
			foreach ( $types as $type )
			{
				$startDate = self::$startDate;
				$i = 0;
				while( $startDate <= self::$endDate )
				{
					if( !in_array( $startDate , $xAxis['categories'] ))
					{
						$xAxis['categories'][] = $startDate;
						
					}
					$itemData[$type][$i] = 0;
					//付费额
					foreach ( self::$returnData['mongo'] as $data )
					{
						foreach ( $data['payTypeChange'] as $t )
						{
							if( $data['date'] == $startDate )
							{
								if( $type == $t['payType'] )
								{
									if( $item == 'rmbs' )
									{
										$t[$item] = $t[$item] * $_SESSION['serverInfo']['rmbRate'];
									}
									$itemData[$type][$i] = $t[$item];
									$itemData['total'][$i] += $t[$item];
									
									
									
								}
							}
						}
					}
					$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
					$i++;
				}
			}
		
			foreach ( $xAxis['categories']  as $key => $date )
			{
				$itemData['total'][$key] = $itemData['total'][$key] ? $itemData['total'][$key] : 0;
			}
			
			
			foreach ( $itemData as $type => $data )
			{
				ksort( $data );
				self::$returnData[$itemVar][] = array(
					'name' => $type,
					'data' => $data,
				);
			}
		}
		
		
		self::$returnData['xAxis'] = $xAxis;
		unset(  self::$returnData['mongo']  );
		
		//print_r( self::$returnData );exit;
			
	}
	
	
	
	/**
	 * 冲值统计
	 * 时间段内，付费用户数除以活跃用户数。日付费率：日付费用户除以DAU；月付费率：月付费用户除以MAU
	 */
	private static function _formatRechargeRate()
	{
		$xAxis['categories'] = array();
		$items = array( 
			'chargeRate' => '日付费率' , 'darpu' => '日ARPU' , 'darppu' => '日ARPPU',	
		);
		
		foreach ( $items as $item => $name )
		{
			$rechargeData = array();
			$i = 0;
			$startDate = self::$startDate;
			while( $startDate <= self::$endDate )
			{
				if( !in_array( $startDate , $xAxis['categories'] ))
				{
					$xAxis['categories'][] = $startDate;
				}
				
				//付费额
				$rechargeData[$i] = 0;
				foreach ( self::$returnData['mongo'] as $data )
				{
					if( $data['date'] == $startDate )
					{
						if( $item == "chargeRate" )
						{
							$rechargeData[$i] = floatval( $data[$item] * 100 );
						}
						else 
						{
							$rechargeData[$i] = $data[$item] ? ( $data[$item] * $_SESSION['serverInfo']['rmbRate'] ) : 0;
						}
					}
				}
				
				$i++;
				$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
			}
			
			self::$returnData['xAxis'] = $xAxis;
			self::$returnData[$item][] = array(
				'name' => $name,
				'data' => $rechargeData,
			);
		}
		unset(  self::$returnData['mongo']  );
	}
	
	/**
	 * 冲值统计
	 * 付费额/付费人数/付费次数 
	 */
	private static function _formatRecharge()
	{
		//付费额/付费人数/付费次数   按渠道分
		$chanals = array( 'total' );
		foreach ( self::$returnData['mongo'] as $data )
		{
			foreach ( $data['channalCharge'] as $chan )
			{
				if( !in_array( $chan['downRefer'] , $chanals ))
				{
					$chanals[] = $chan['downRefer'];
				}
			}
		}
		
		$items = array( 'coins' , 'rmbs' , 'times' , 'uids' );
		
		foreach ( $items as $item )
		{
			//统计项目变量名称
			$uItem = ucfirst( $item );
			$itemVar = 'showChan'.$uItem;
			$itemData  = array();
			
			$xAxis['categories'] = array();
			
			
			//分渠道记
			foreach ( $chanals as $chan )
			{
				$startDate = self::$startDate;
				$i = 0;
				while( $startDate <= self::$endDate )
				{
					if( !in_array( $startDate , $xAxis['categories'] ))
					{
						$xAxis['categories'][] = $startDate;
						
					}
					$itemData[$chan][$i] = 0;
					//付费额
					foreach ( self::$returnData['mongo'] as $data )
					{
						foreach ( $data['channalCharge'] as $charg )
						{
							if( $data['date'] == $startDate )
							{
								if( $chan == $charg['downRefer'] )
								{
									if( $item == 'rmbs' )
									{
										$charg[$item] = $charg[$item] * $_SESSION['serverInfo']['rmbRate'];
									}
									
									$itemData[$chan][$i] = $charg[$item];
									$itemData['total'][$i] += $charg[$item];
								}
							}
						}
					}
					$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
					$i++;
				}
			}
		
			
			foreach ( $xAxis['categories']  as $key => $date )
			{
				$itemData['total'][$key] = $itemData['total'][$key] ? $itemData['total'][$key] : 0;
			}
			
			
			foreach ( $itemData as $chan => $data )
			{
				ksort( $data );
				self::$returnData[$itemVar][] = array(
					'name' => $chan,
					'data' => $data,
				);
			}
		}

	
		self::$returnData['xAxis'] = $xAxis;
		self::_formatPayType();
		unset(  self::$returnData['mongo']  );
		
	}
	
	/**
	 * 格式化留存数据输出
	 */
	private static function _formatKeepLogin()
	{
		$startDate = self::$startDate;
		$days = array( 2,3,4,5,6,7,8,14,30);
		while( $startDate <= self::$endDate )
		{
			
			self::$returnData['keepLogins']['xAxis']['categories'][] = $startDate;
			$flag = 0;
			
			foreach ( $days as $day )
			{
				self::$returnData["keepLogin{$day}Num"][0]['name'] = "留存数";
				self::$returnData["keepLogin{$day}Rate"][0]['name'] = "留存率";
				foreach ( self::$returnData['mongo'] as $key => $data )
				{
					if( $data['date'] == $startDate )
					{
						self::$returnData["keepLogin{$day}Num"][0]['data'][] = $data['keepLogins'][$day];
						//留存率:留存数/注册人数, 前天注册10人，今天登录的用户中是前天注册的有4人，那就是前天的留存率3日留存40%
						
						if( $data['newUser']['total'] <= 0 )
						{
							self::$returnData["keepLogin{$day}Rate"][0]['data'][] = 0;
						}
						else 
						{
							self::$returnData["keepLogin{$day}Rate"][0]['data'][] = 100*sprintf( "%.4f" , $data['keepLogins'][$day]/$data['newUser']['total'] );
						}
						
						$flag = 1;
					}
				}
				
				if( $flag == 0 )
				{
					self::$returnData["keepLogin{$day}Num"][0]['data'][] = 0;
					self::$returnData["keepLogin{$day}Rate"][0]['data'][] = 0;
				}
			}
			//日期加1天
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
		}	
		
		
		unset( self::$returnData['mongo'] );
		//print_r( self::$returnData );exit;
	}
	
	
	
	/**
	 * 新增用户数据
	 */
	private static function _formatUserData()
	{
		$newUser = $newDevice = $loginUser = $loginDevice = array(
			'xAxis' => array(),
			'series' => array(),
		);
		
		
		$startDate = self::$startDate;
		
		//渠道
		$chanals = array( 'total' );
		//玩家总数
		$totalUser = $totalDevice = $totalLoginUser = $totalLoginDevice = 0;
		//最大用户数
		$maxUser = $maxDevice = $maxLoginUser = $maxLoginDevice = 0;
		
		foreach ( self::$returnData['mongo'] as $data )
		{
			if( $data['channals'] )
			{
				foreach ( $data['channals'] as $chan )
				{
					if( !in_array( $chan , $chanals ))
					{
						$chanals[] = $chan;
					}
				}

			}
			
			if( $data['date'] == date( "Ymd" , $_SERVER['REQUEST_TIME']))
			{
				self::$returnData['allUserNum'] = $data['allUserNum'] ? $data['allUserNum'] : 0 ;
				self::$returnData['rechargeUsers'] = $data['rechargeUsers'] ? $data['rechargeUsers'] : 0;
				self::$returnData['rechargeMoneys'] = $data['rechargeMoneys'] ? $data['rechargeMoneys'] : 0;
				self::$returnData['rechargeTimes'] = $data['rechargeTimes'] ? $data['rechargeTimes'] : 0;
			}
			
		
			//管理员只可见该渠道
			if( $_SESSION['adminInfo']['userType'] == 2 )
			{
			
				$viewChanals = $_SESSION['adminInfo']['channals'][self::$appId];
				//取交集
				$chanals = @array_intersect( $chanals , $viewChanals );
				if( $chanals )
				{
					foreach ( $chanals as $ch )
					{
						//最大用户数
						$maxUser = $data['newUser'][$ch] > $maxUser ? $data['newUser'][$ch] : $maxUser;
						$maxDevice = $data['newUserDevice'][$ch] > $maxDevice ? $data['newUserDevice'][$ch] : $maxDevice;
						$maxLoginUser = $data['userLogin'][$ch] > $maxLoginUser ? $data['userLogin']['total'] : $maxLoginUser;
						$maxLoginDevice = $data['userLoginDevice'][$ch] > $maxLoginDevice ? $data['userLoginDevice'][$ch] : $maxLoginDevice;
						
						//用户总数
						$totalUser += $data['newUser'][$ch];
						$totalDevice += $data['newUserDevice'][$ch];
						$totalLoginUser +=  $data['userLogin'][$ch];
						$totalLoginDevice += $data['userLoginDevice'][$ch];
					}
					
				}
				
			}
			else 
			{
			
				//最大用户数
				$maxUser = $data['newUser']['total'] > $maxUser ? $data['newUser']['total'] : $maxUser;
				$maxDevice = $data['newUserDevice']['total'] > $maxDevice ? $data['newUserDevice']['total'] : $maxDevice;
				$maxLoginUser = $data['userLogin']['total'] > $maxLoginUser ? $data['userLogin']['total'] : $maxLoginUser;
				$maxLoginDevice = $data['userLoginDevice']['total'] > $maxLoginDevice ? $data['userLoginDevice']['total'] : $maxLoginDevice;
				
				//用户总数
				$totalUser += $data['newUser']['total'];
				$totalDevice += $data['newUserDevice']['total'];
				$totalLoginUser +=  $data['userLogin']['total'];
				$totalLoginDevice += $data['userLoginDevice']['total'];
			}
		}
		
		//总数
		self::$returnData['newUserTotal'] = $totalUser;
		self::$returnData['newDeviceTotal'] = $totalDevice;
		self::$returnData['loginUserTotal'] = $totalLoginUser;
		self::$returnData['loginDeviceTotal'] = $totalLoginDevice;
		
		
		self::$returnData['newUserMax'] = $maxUser;
		self::$returnData['newDeviceMax'] = $maxDevice;
		self::$returnData['loginUserMax'] = $maxLoginUser;
		self::$returnData['loginDeviceMax'] = $maxLoginDevice;
		
		//所有日期总数
		self::$returnData['allUsers'] =  $totalUser;
		
		//管理员只可见该渠道
		if( $_SESSION['adminInfo']['userType'] == 2 )
		{
			$viewChanals = $_SESSION['adminInfo']['channals'][self::$appId];
			//取交集
			$chanals = @array_intersect( $chanals , $viewChanals );
		}
		//print_r( $chanals );exit;
		
		/**
			图表显示数据
		 */
		$chanalUser = $chanalDevice =  array();
		$chanalLoginUser = $chanalLoginDevice = array();
		
		$l = 0;
		while( $startDate <= self::$endDate )
		{
			$newUser['xAxis']['categories'][] = $startDate;
			//$chanalUser['total']['data'][$l] = 0;
			if( $chanals )
			{
				foreach ( $chanals as $n => $chan )
				{
					$flag = 0;
					$chanalUser[$chan]['name'] = $chan;
					$chanalDevice[$chan]['name'] = $chan;
					$chanalLoginUser[$chan]['name'] = $chan;
					$chanalLoginDevice[$chan]['name'] = $chan;
					
					foreach (  self::$returnData['mongo'] as $data )
					{
						if( $data['date'] == $startDate )
						{
						
							$chanalUser[$chan]['data'][$l] = $data['newUser'][$chan] ? $data['newUser'][$chan] : 0;
							$chanalDevice[$chan]['data'][$l] = $data['newUserDevice'][$chan] ? $data['newUserDevice'][$chan]  : 0 ;
							
							$chanalLoginUser[$chan]['data'][$l] = $data['userLogin'][$chan] ? $data['userLogin'][$chan] : 0;
							$chanalLoginDevice[$chan]['data'][$l] = $data['userLoginDevice'][$chan] ? $data['userLoginDevice'][$chan] : 0;
							$flag = 1;
						}
					}
					
					if( $flag == 0 )
					{
						$chanalUser[$chan]['data'][$l] = 0;
						$chanalDevice[$chan]['data'][$l] = 0;
						$chanalLoginUser[$chan]['data'][$l] = 0;
						$chanalLoginDevice[$chan]['data'][$l] = 0;
					}
					
					
				}
			}
			//日期加1天
			$startDate = date( "Ymd" , strtotime( $startDate ) + 86400 );
			$l++;
		}
		
		
		foreach ( $chanals as $chan )
		{
			if( array_sum( $chanalUser[$chan]['data'] ) <= 0 )
			{
				unset( $chanalUser[$chan] );
			}
			
			if( array_sum( $chanalDevice[$chan]['data'] ) <= 0 )
			{
				unset( $chanalDevice[$chan] );
			}
			
			if( array_sum( $chanalLoginUser[$chan]['data'] ) <= 0 )
			{
				unset( $chanalLoginUser[$chan] );
			}
			
			if( array_sum( $chanalLoginDevice[$chan]['data'] ) <= 0 )
			{
				unset( $chanalLoginDevice[$chan] );
			}
		}
		
		//图表X轴
		$loginUser['xAxis'] = $loginDevice['xAxis'] = $newDevice['xAxis'] = $newUser['xAxis'];
	
		
		$newUser['series'] = $chanalUser;
		$newDevice['series'] = $chanalDevice;
		$loginUser['series'] = $chanalLoginUser;
		$loginDevice['series'] = $chanalLoginDevice;
		
	
		sort(  $newUser['series'] );
		sort(  $newDevice['series'] );
		sort(  $loginUser['series'] );
		sort(  $loginDevice['series'] );
		
		//平均新用户，新设备
		self::$returnData['newDeviceAvg'] = sprintf( '%.2f' , self::$returnData['newDeviceTotal'] / count( $newUser['xAxis']['categories'] ) );
		self::$returnData['newUserAvg'] = sprintf( '%.2f' , self::$returnData['newUserTotal'] / count( $newUser['xAxis']['categories'] ) );	
		self::$returnData['loginUserAvg'] = sprintf( '%.2f' , self::$returnData['loginUserTotal'] / count( $newUser['xAxis']['categories'] ) );
		self::$returnData['loginDeviceAvg'] = sprintf( '%.2f' , self::$returnData['loginDeviceTotal'] / count( $newUser['xAxis']['categories'] ) );
		
		self::$returnData['newUser'] = $newUser ?  $newUser : 0 ;
		self::$returnData['newDevice'] = $newDevice ? $newDevice : 0;
		self::$returnData['loginUser'] = $loginUser ? $loginUser : 0;
		self::$returnData['loginDevice'] = $loginDevice ? $loginDevice : 0;
		
	
		unset( self::$returnData['mongo'] );
		
		
		
	}
	
	/**
	 * 查询显示数据
	 */
	private static function _queryMongoData( )
	{
		
/**
 * 7月总登录用户数:34532人
7月付费用户数:104人
1372608000==1375286399
 * 
 */
		//初始化查询数据
		$queryCond['date'] = array(
				'$gte' => self::$startDate,
				'$lte' => self::$endDate,
		);
		
		if( self::$action == "userOnline" )
		{
			$table = "userOnline";
		}
		elseif(  self::$action == "levelMap"  ) 
		{
			$table = "levelMapData";
		}
		else 
		{
			$table = "resultData";
		}
		
		
		
		self::$returnData['mongo'] = Stats_Model::find( $table , $queryCond );
		
	}
	
	/*
	public function xx()
	{
		$query = array(
			'loginTime' =>array(  
				'$gte' => self::$startDate,
				'$lte' => self::$endDate,
			)
		);
		
		self::distinct($collectName, 'uid' , )
	}
	
*/
}
