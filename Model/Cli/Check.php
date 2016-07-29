<?php
class Cli_Check
{
	
	protected $dbRecord = 
	array(
	 'arena' => 0 ,
	 'bag' => 0,
	 'bag_equipment' => 0,
	 'bag_item' => 0, 
	 'buff' => 0,
	 'building' => 0, 
	 'map' => 0, 
	 'map_unlock' => 0, 
	 'order' => 0, 
	 'ship' => 0 , 
	 'ship_avatar' => 0 ,
     'ship_npc' => 0 ,
	 'skill' => 0,
	 'skill_info' => 0 , 
	 'skill_order' => 0,
	 'social' => 0,
	 'tackle' => 0 , 
	 'tackle_drug' => 0, 
	 'user_ability' => 0 , 
	 'user_profile'	 => 0,
	);
	
	
	public function check( $users = array() , $debug = false , $fix = false )
	{
		ini_set( 'display_errors' , 'on' );
		error_reporting( E_ALL ^ E_NOTICE );
		ini_set( 'memory_limit' , '512M' );
		ini_set( 'max_execution_time' , 1200 );
		$logDir = Common::getConfig();
		$partStart = $_GET['partStart'] ? $_GET['partStart'] : 0;
		if( $partStart == 0 )
		{
			file_put_contents( '/tmp/dataError' , "" );
		}
		
		$memcache_keys = array(
			'arena' ,
			'bag' ,
			'buff' ,
			'building' ,
			'map' ,
			'ship' ,
			'skill' ,
			'social' ,
			'user_ability' ,
			'user_profile' ,
		);
		
		$cache = Common::getCache();
		
		if(  $users == array() )
		{
			$result = self::_iterateUserId( $partStart );
		}
		else 
		{
			$result['users']  = $users;
		}
	
		$needFixDatas = array();
		$fixedData = array();
		foreach( $result['users'] as $user )
		{
		
			$userId = is_array( $user['userId'] ) ?  $user['userId'] :  $user ;
			$dbEngine = $this->_getDbEngine( $userId );
			foreach( $memcache_keys as $key )
			{
				$cacheData = $cache->get( $userId .'_'. $key );
				if( $cacheData === false )
				{
					continue;
				}
				switch( $key )
				{
					case 'arena':
						
						$this->_compareArenaData( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						break;
						
					case 'bag':
						
						$this->_compareBagInfo( $userId , $dbEngine , $cacheData , $needFixDatas );

						$this->_compareEquipmentList( $userId , $dbEngine , $cacheData , $needFixDatas );

						$this->_compareItemList( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						break;
						
					case 'buff':
						
						$this->_compareBuffList( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						break;
						
					case 'building':
						
						$this->_compareBuilding( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						$this->_compareBuildingOrder( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						break;
						
					case 'map':
						
						$this->_compareMapInfo( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						$this->_compateUnlockMap( $userId , $dbEngine , $cacheData , $needFixDatas );
						break;
						
					case 'ship':
						
						$this->_compareShipInfo( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						$this->_compareShipNPC( $userId , $dbEngine , $cacheData , $needFixDatas );
						break;
						
					case 'skill':
						
						$this->_compareSkill( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						$this->_compareSkillInfo( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						$this->_compareSkillOrder( $userId , $dbEngine , $cacheData , $needFixDatas );
						break;
						
					case 'social':
						
						$this->_compareSocial( $userId , $dbEngine , $cacheData , $needFixDatas );
						
						break;
						
					case 'user_ability':
						
						$this->_compareUserAbility( $userId , $dbEngine , $cacheData , $needFixDatas );
						break;
						
					case 'user_profile':
						
						$this->_compareUserProfile( $userId , $dbEngine , $cacheData , $needFixDatas );
						break;
				}
			}

		
		 	echo "comparing :".$userId."\n";
			
            if( isset( $_GET['fix'] ) || $fix == true  )
            {
            	foreach( $needFixDatas as $key => $sql )
				{
					if( $dbEngine->query( $sql ) )
                    {                          	
				        $fixedData[] = $sql;
	                    unset( $needFixDatas[$key] );                    
					}
					else
					{
						file_put_contents( '/tmp/unfixedDataError' , $sql."\n" , FILE_APPEND );
					}
                 }
             	file_put_contents( '/tmp/fixedDataError'. date( 'Y-m-d' ) , "userId: {$userId} fixed\n" , FILE_APPEND );
            }
		}
		/*
		if( $debug == false )
		{
			$partStart = $result['next']['partStart'];
			if( $result['next']['end'] == false && !isset( $_GET['only'] ) )
			{
				echo "<head><meta HTTP-EQUIV='Refresh' CONTENT='2; URL=?f=check&partStart={$partStart}". ( isset( $_GET['fix'] ) ? '&fix' : '' ) ."' /></head>";
			}
			else 
			{
				echo "如果需要修复数据，则添加&fix参数";
			}
		}
		*/
		//echo join( "\n<br />\n" , $needFixDatas );
		
		if( $debug )
		{
			$report = '';
			$reportCount = 0;
			foreach (  $this->dbRecord as $dbName => $dbCount )
			{
				if( $dbCount > 0  )
				{
					$report .=  $dbName.":".$dbCount."\n" ;
					$reportCount += 1;
				}
			}
			$report .=  "count:".$reportCount;
	
			file_put_contents(  $logDir['cli']['checkDataDir'] . date( 'Y-m-d' ) , $report."\n" , FILE_APPEND );
				/*
				echo "共发现".count( $fixedData )."条数据\n";
				print_r( $fixedData);
				echo "下以数据未能修复：\n";
				print_r(  $needFixDatas );
				*/
			}
			else 
			{
						echo count( $needFixDatas );
			}		
	}
	
	private function _getDbEngine( $userId )
	{
		static $dbConfigList = array();
		if( count( $dbConfigList ) == 0 )
		{
			$mysqlConfig = Common::getConfig( 'mysqlDb' );
			$indexDb = new MysqlDb( $mysqlConfig ['index'] );
			$result = $indexDb->fetchArray( "SELECT * FROM `db_config` ORDER BY `id`" );
			foreach( $result as $record )
			{
				$dbConfigList[$record['ID']] = $record;
			}
		}
		
		$mysqlConfig = Common::getConfig( 'mysqlDb' );
		$indexDb = new MysqlDb( $mysqlConfig ['index'] );
		$result = $indexDb->fetchOneAssoc( "SELECT `db_id` AS `dbId` FROM `index_0` WHERE `userid` = {$userId}" );
		
		static $dbEngines = array();
		if( !isset( $dbEngines[$result['dbId']] ) )
		{
			$dbEngines[$result['dbId']] = new MysqlDb( array('host' => $dbConfigList[$result['dbId']]['master_ip'], 'port' => $dbConfigList[$result['dbId']]['master_port'], 'user' => $dbConfigList[$result['dbId']]['username'], 'passwd' => $dbConfigList[$result['dbId']]['pwd'], 'name' => $dbConfigList[$result['dbId']]['db_name'] ) );
		}
		return $dbEngines[$result['dbId']];
	}
	
	/**
	 * 用户信息
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareUserProfile( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `user_profile` WHERE `uid` = {$userId}" );
		if( $dbData['clearStage'] != $cacheData['clearStage']
			|| $dbData['titleId'] != $cacheData['titleId']
			|| $dbData['popularity'] != $cacheData['popularity']
			|| $dbData['newbieGuide'] != $cacheData['newbieGuide']
			|| $dbData['registerFriendNum'] != $cacheData['registerFriendNum']
			|| $dbData['baseLocation'] != $cacheData['baseLocation']
			|| $dbData['skillPoint'] != $cacheData['skillPoint']
			|| $dbData['unUsedSkillPoint'] != $cacheData['unUsedSkillPoint']
			|| $dbData['popularityLevel'] != $cacheData['popularityLevel']
			|| $dbData['visitAwardCount'] != $cacheData['visitAwardCount']
			|| $dbData['lastVisitTime'] != $cacheData['lastVisitTime']
			|| $dbData['lastChallengeFriendTime'] != $cacheData['lastChallengeFriendTime']
		)
		{
			$this->dbRecord['user_profile'] += 1;
			$needFixDatas[] = "INSERT INTO `user_profile` ( `uid` , `clearStage` , `titleId` , `popularity` , `newbieGuide` , `registerFriendNum` , `baseLocation` , `skillPoint` , `unUsedSkillPoint` , `popularityLevel` , `visitAwardCount` , `lastVisitTime` , `lastChallengeFriendTime` ) VALUES ( {$userId} , {$cacheData['clearStage']} , {$cacheData['titleId']} , {$cacheData['popularity']} , {$cacheData['newbieGuide']} , {$cacheData['registerFriendNum']} , {$cacheData['baseLocation']} , {$cacheData['skillPoint']} , {$cacheData['unUsedSkillPoint']} , {$cacheData['popularityLevel']} , {$cacheData['visitAwardCount']} , {$cacheData['lastVisitTime']} , {$cacheData['lastChallengeFriendTime']} ) ON DUPLICATE KEY UPDATE `clearStage` = {$cacheData['clearStage']} , `titleId` = {$cacheData['titleId']} , `popularity` = {$cacheData['popularity']} , `newbieGuide` = {$cacheData['newbieGuide']} , `registerFriendNum` = {$cacheData['registerFriendNum']} , `baseLocation` = {$cacheData['baseLocation']} , `skillPoint` = {$cacheData['skillPoint']} , `unUsedSkillPoint` = {$cacheData['unUsedSkillPoint']} , `popularityLevel` = {$cacheData['popularityLevel']} , `visitAwardCount` = {$cacheData['visitAwardCount']} , `lastVisitTime` = {$cacheData['lastVisitTime']} , `lastChallengeFriendTime` = {$cacheData['lastChallengeFriendTime']}";
			file_put_contents( '/tmp/dataError' , "userProfile update userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 用户属性
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareUserAbility( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `user_ability` WHERE `uid` = {$userId}" );

		if( $dbData['exp'] != $cacheData['exp']
			|| $dbData['addtionExp'] != $cacheData['addtionExp']
			|| $dbData['strong'] != $cacheData['strong']
			|| $dbData['brawn'] != $cacheData['brawn']
			|| $dbData['dexterity'] != $cacheData['dexterity']
			|| $dbData['canUseAttributePoint'] != $cacheData['canUseAttributePoint']
			|| $dbData['gold'] != $cacheData['gold']
			|| $dbData['level'] != $cacheData['level']
		)
		{
			$this->dbRecord['user_ability'] += 1;
			$needFixDatas[] = "INSERT INTO `user_ability` ( `uid` , `exp` , `addtionExp` , `strong` , `brawn` , `dexterity` , `canUseAttributePoint` , `gold` , `level` ) VALUES ( {$userId} , {$cacheData['exp']} , {$cacheData['addtionExp']} , {$cacheData['strong']} , {$cacheData['brawn']} , {$cacheData['dexterity']} , {$cacheData['canUseAttributePoint']} , {$cacheData['gold']} , {$cacheData['level']} ) ON DUPLICATE KEY UPDATE `exp` = {$cacheData['exp']} , `addtionExp` = {$cacheData['addtionExp']} , `strong` = {$cacheData['strong']} , `brawn` = {$cacheData['brawn']} , `dexterity` = {$cacheData['dexterity']} , `canUseAttributePoint` = {$cacheData['canUseAttributePoint']} , `gold` = {$cacheData['gold']} , `level` = {$cacheData['level']}";
			file_put_contents( '/tmp/dataError' , "userAbility update userId: {$userId}\n" , FILE_APPEND );
		}
		if( $userId == 230854321 )
		{
		//	print_r(  $needFixDatas );
		}
	}
	
	/**
	 * 互动信息
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareSocial( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `social` WHERE `uid` = {$userId}" );
		
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'isVisited' => $dbRecord['isVisited'] ,
				'lastCommunicateTime' => $dbRecord['lastCommunicateTime'] ,
				'lastCombatTime' => $dbRecord['lastCombatTime'] ,
			);
		}

		$cacheData = isset( $cacheData['social'] ) ? $cacheData['social']  : array() ;
		
		if( empty( $cacheData ) )
		{
			return ;
		}

		
		foreach( $cacheData as $friendId => $socialData )
		{
			//数据插入不存在
			if( !isset( $formatedData[$friendId] ) )
			{
				$this->dbRecord['social'] += 1;
				$needFixDatas[] = "INSERT INTO `social` ( `uid` , `id` , `isVisited` , `lastCommunicateTime` , `lastCombatTime` ) VALUES ( {$userId} , {$friendId} , ". ( $socialData['isVisited'] ? 1 : 0 ) ." , {$socialData['lastCommunicateTime']} , {$socialData['lastCombatTime']} )";
				file_put_contents( '/tmp/dataError' , "social insert userId: {$userId}\n" , FILE_APPEND );
			}
			//数据不一致
			else if( $socialData['isVisited'] != $formatedData[$friendId]['isVisited']
				|| $socialData['lastCommunicateTime'] != $formatedData[$friendId]['lastCommunicateTime']
				|| $socialData['lastCombatTime'] != $formatedData[$friendId]['lastCombatTime']
			)
			{
				$this->dbRecord['social'] += 1;
				$needFixDatas[] = "UPDATE `social` SET `isVisited` = ". ( $socialData['isVisited'] ? 1 : 0 ) ." , `lastCommunicateTime` = {$socialData['lastCommunicateTime']} , `lastCombatTime` = {$socialData['lastCombatTime']} WHERE `uid` = {$userId} AND `id` = {$friendId}";
				file_put_contents( '/tmp/dataError' , "social update userId: {$userId}\n" , FILE_APPEND );
			}
			
			unset( $formatedData[$friendId] );
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $friendId => $socialData )
			{
				$this->dbRecord['social'] += 1;
				$needFixDatas[] = "DELETE FROM `social` WHERE `uid` = {$userId} AND `id` = {$friendId}";
			}
			file_put_contents( '/tmp/dataError' , "social delete userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 技能序列
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareSkillOrder( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `skill_order` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				$dbRecord['skillId1'] ,
				$dbRecord['skillId2'] ,
				$dbRecord['skillId3'] ,
				$dbRecord['skillId4'] ,
				$dbRecord['skillId5'] ,
				$dbRecord['skillId6'] ,
			);
		}
		
		foreach( $cacheData['orderList'] as $orderId => $skillOrder )
		{
			//数据插入不存在
			if( !isset( $formatedData[$orderId] ) )
			{
				$this->dbRecord['skill_order'] += 1;
				$needFixDatas[] = "INSERT INTO `skill_order` ( `uid` , `id` , `skillId1` , `skillId2` , `skillId3` , `skillId4` , `skillId5` , `skillId6` ) VALUES ( {$userId} , {$orderId} , {$skillOrder[0]} , {$skillOrder[1]} , {$skillOrder[2]} , {$skillOrder[3]} , {$skillOrder[4]} , {$skillOrder[5]} )";
				file_put_contents( '/tmp/dataError' , "skillOrder insert userId: {$userId}\n" , FILE_APPEND );
				unset( $formatedData[$orderId] );
			}
			//数据不一致
			else if( $skillOrder[0] != $formatedData[$orderId][0]
				|| $skillOrder[1] != $formatedData[$orderId][1]
				|| $skillOrder[2] != $formatedData[$orderId][2]
				|| $skillOrder[3] != $formatedData[$orderId][3]
				|| $skillOrder[4] != $formatedData[$orderId][4]
				|| $skillOrder[5] != $formatedData[$orderId][5]
			)
			{
				$this->dbRecord['skill_order'] += 1;
				$needFixDatas[] = "UPDATE `skill_order` SET `skillId1` = {$skillOrder[0]} , `skillId2` = {$skillOrder[1]} , `skillId3` = {$skillOrder[2]} , `skillId4` = {$skillOrder[3]} , `skillId5` = {$skillOrder[4]} , `skillId6` = {$skillOrder[5]} WHERE `uid` = {$userId} AND `id` = {$orderId}";
				file_put_contents( '/tmp/dataError' , "skillOrder update userId: {$userId}\n" , FILE_APPEND );
			}
			
			unset( $formatedData[$orderId] );
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $orderId => $skillOrder )
			{
				$this->dbRecord['skill_order'] += 1;
				$needFixDatas[] = "DELETE FROM `skill_order` WHERE `uid` = {$userId} AND `id` = {$orderId}";
			}
			file_put_contents( '/tmp/dataError' , "skillOrder delete userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 技能信息
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareSkillInfo( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `skill_info` WHERE `uid` = {$userId}" );
		if( $dbData['skillOrderSlotCount'] != $cacheData['skillOrderSlotCount']
			|| $dbData['skillOrderCount'] != $cacheData['skillOrderCount']
			|| $dbData['defaultOrderId'] != $cacheData['defaultOrderId']
		)
		{
			$this->dbRecord['skill_info'] += 1;
			$needFixDatas[] = "INSERT INTO `skill_info` ( `uid` , `skillOrderSlotCount` , `skillOrderCount` , `defaultOrderId` ) VALUES ( {$userId} , {$cacheData['skillOrderSlotCount']} , {$cacheData['skillOrderCount']} , {$cacheData['defaultOrderId']} ) ON DUPLICATE KEY UPDATE `skillOrderSlotCount` = {$cacheData['skillOrderSlotCount']} , `skillOrderCount` = {$cacheData['skillOrderCount']} , `defaultOrderId` = {$cacheData['defaultOrderId']}";
			file_put_contents( '/tmp/dataError' , "skillInfo insert userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 比对技能列表
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareSkill( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `skill` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'level' => $dbRecord['level'] ,
				'isMastered' => $dbRecord['isMastered'] ,
			);
		}
		
		foreach( $cacheData['skillList'] as $skillId => $skill )
		{
			//数据插入不存在
			if( !isset( $formatedData[$skillId] ) )
			{
				$this->dbRecord['skill'] += 1;
				$needFixDatas[] = "INSERT INTO `skill` ( `uid` , `id` , `level` , `isMastered` ) VALUES ( {$userId} , {$skillId} , {$skill['level']} , ". ( $skill['isMastered'] ? 1 : 0 ) ." )";
				file_put_contents( '/tmp/dataError' , "skill insert userId: {$userId}\n" , FILE_APPEND );
			}
			//数据不一致
			else if( $skill['level'] != $formatedData[$skillId]['level']
				|| $skill['isMastered'] != $formatedData[$skillId]['isMastered']
			)
			{
				$this->dbRecord['skill'] += 1;
				$needFixDatas[] = "UPDATE `skill` SET `level` = {$skill['level']} , `isMastered` = ". ( $skill['isMastered'] ? 1 : 0 ) ." WHERE `uid` = {$userId} AND `id` = {$skillId}";
				file_put_contents( '/tmp/dataError' , "skill update userId: {$userId}\n" , FILE_APPEND );
			}
			
			unset( $formatedData[$skillId] );
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $skillId => $skill )
			{
				$this->dbRecord['skill'] += 1;
				$needFixDatas[] = "DELETE FROM `skill` WHERE `uid` = {$userId} AND `id` = {$skillId}";
			}
			file_put_contents( '/tmp/dataError' , "skill delete userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 船只NPC列表
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareShipNPC( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `ship_npc` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[] = $dbRecord['id'];  
		}
		$formatedData = array_unique( $formatedData );
		if( $cacheData['npcList'] || $formatedData )
		{
		$diffNPCList = Helper_Array::array_diff_fast( $cacheData['npcList'] , $formatedData );
		}
		if( count( $diffNPCList ) > 0 )
		{
			foreach( $diffNPCList as $npcId )
			{
				$this->dbRecord['ship_npc'] += 1;
				$needFixDatas[] = "INSERT INTO `ship_npc` ( `uid` , `id` ) VALUES ( {$userId} , {$npcId} )";
				file_put_contents( '/tmp/dataError' , "npc insert userId: {$userId}\n" , FILE_APPEND );
			}
		}
		if( $cacheData['npcList'] || $formatedData )
		{	
		$diffNPCList = Helper_Array::array_diff_fast( $formatedData , $cacheData['npcList'] );
		}
		if( count( $diffNPCList ) > 0 )
		{
			foreach( $diffNPCList as $npcId )
			{
				$this->dbRecord['ship_npc'] += 1;
				$needFixDatas[] = "DELETE FROM `ship_npc` WHERE `uid` = {$userId} AND `id` = {$npcId}";
				file_put_contents( '/tmp/dataError' , "npc delete userId: {$userId}\n" , FILE_APPEND );
			}
		}
	}
	
	/**
	 * 比对船只信息
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareShipInfo( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `ship` WHERE `uid` = {$userId}" );
		if( $dbData['damage'] != $cacheData['damage']
			|| $dbData['agility'] != $cacheData['agility']
			|| $dbData['sailor'] != $cacheData['sailor']
			|| $dbData['sailorLimit'] != $cacheData['sailorLimit']
			|| $dbData['baseHP'] != $cacheData['baseHP']
			|| $dbData['baseMP'] != $cacheData['baseMP']
			|| $dbData['baseDamage'] != $cacheData['baseDamage']
			|| $dbData['baseAgility'] != $cacheData['baseAgility']
			|| $dbData['mp'] != $cacheData['mp']
			|| $dbData['mpLimit'] != $cacheData['mpLimit']
			|| $dbData['hpLimit'] != $cacheData['hpLimit']
			|| $dbData['lastRefreshMPTime'] != $cacheData['lastRefreshMPTime']
			|| $dbData['unlockedSlot'] != $cacheData['unlockedSlot']
			|| $dbData['movement'] != $cacheData['movement']
		)
		{
			$this->dbRecord['ship'] += 1;
			$needFixDatas[] = "INSERT INTO `ship` ( `uid` , `damage` , `sailor` , `agility` , `baseHP` , `baseDamage` , `baseAgility` , `baseMP` , `mp` , `hpLimit` , `mpLimit` , `sailorLimit` , `movement` , `playSpeed` , `unlockedSlot` , `lastRefreshMPTime` ) VALUES ( {$userId} , {$cacheData['damage']} , {$cacheData['sailor']} , {$cacheData['agility']} , {$cacheData['baseHP']} , {$cacheData['baseDamage']} , {$cacheData['baseAgility']} , {$cacheData['baseMP']} , {$cacheData['mp']} , {$cacheData['hpLimit']} , {$cacheData['mpLimit']} , {$cacheData['sailorLimit']} , {$cacheData['movement']} , ". ( $cacheData['playSpeed'] ? $cacheData['playSpeed'] : 1 ) ." , {$cacheData['unlockedSlot']} , {$cacheData['lastRefreshMPTime']} ) ON DUPLICATE KEY UPDATE `damage` = {$cacheData['damage']} , `sailor` = {$cacheData['sailor']} , `agility` = {$cacheData['agility']} , `baseHP` = {$cacheData['baseHP']} , `baseDamage` = {$cacheData['baseDamage']} , `baseAgility` = {$cacheData['baseAgility']} , `baseMP` = {$cacheData['baseMP']} , `mp` = {$cacheData['mp']} , `hpLimit` = {$cacheData['hpLimit']} , `mpLimit` = {$cacheData['mpLimit']} , `sailorLimit` = {$cacheData['sailorLimit']} , `movement` = {$cacheData['movement']} , `playSpeed` = ". ( $cacheData['playSpeed'] ? $cacheData['playSpeed'] : 1 ) ." , `unlockedSlot` = {$cacheData['unlockedSlot']} , `lastRefreshMPTime` = {$cacheData['lastRefreshMPTime']}";
			file_put_contents( '/tmp/dataError' , "ship update userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 比对解锁地图
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compateUnlockMap( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `map_unlock` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[] = $dbRecord['id'];
		}
		$formatedData = array_unique( $formatedData );
		
		$diffMapIds = Helper_Array::array_diff_fast( $cacheData['unlockedMap'] , $formatedData );
		
		if( count( $diffMapIds ) > 0 )
		{
			if( $diffMapIds )
			{
			foreach( $diffMapIds as $mapId )
			{
				$this->dbRecord['map_unlock'] += 1;
				$needFixDatas[] = "INSERT INTO `map_unlock` ( `uid` , `id` ) VALUES ( {$userId} , {$mapId} )";
				file_put_contents( '/tmp/dataError' , "unlockMap insert userId: {$userId}\n" , FILE_APPEND );
			}
			}
		}
		
		$diffMapIds = Helper_Array::array_diff_fast( $formatedData , $cacheData['unlockedMap'] );
		
		if( count( $diffMapIds ) > 0 )
		{
			foreach( $diffMapIds as $mapId )
			{
				$this->dbRecord['map_unlock'] += 1;
				$needFixDatas[] = "DELETE FROM `map_unlock` WHERE `uid` = {$userId} AND `id` = {$mapId}";
				file_put_contents( '/tmp/dataError' , "unlockMap delete userId: {$userId}\n" , FILE_APPEND );
			}
		}
	}
	
	/**
	 * 比较地图信息
	 * @param $userId
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareMapInfo( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `map` WHERE `uid` = {$userId}" );
		if( $dbData['mapId'] != $cacheData['mapId']
			|| $dbData['xCoordinate'] != $cacheData['coordinate']['x']
			|| $dbData['yCoordinate'] != $cacheData['coordinate']['y']
			|| $dbData['zCoordinate'] != $cacheData['coordinate']['z']
		)
		{
			$this->dbRecord['map'] += 1;
			$needFixDatas[] = "INSERT INTO `map` ( `uid` , `mapId` , `xCoordinate` , `yCoordinate` , `zCoordinate` ) VALUES ( {$userId} , {$cacheData['mapId']} , {$cacheData['coordinate']['x']} , {$cacheData['coordinate']['y']} , {$cacheData['coordinate']['z']} ) ON DUPLICATE KEY UPDATE `mapId` = {$cacheData['mapId']} , `xCoordinate` = {$cacheData['coordinate']['x']} , `yCoordinate` = {$cacheData['coordinate']['y']} , `zCoordinate` = {$cacheData['coordinate']['z']}";
			file_put_contents( '/tmp/dataError' , "map insert userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 建筑物订单列表
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareBuildingOrder( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `order` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'orderTypeId' => $dbRecord['orderTypeId'] ,
				'finishTime' => $dbRecord['finishTime'] ,
				'orderLevel' => $dbRecord['orderLevel'] ,
			);
		}
		
		foreach( $cacheData['base'] as $buildingId => $building )
		{
			$order = $building['order'];
			//建筑物
			if( $buildingId != Building_Territory_Management::BUILDING_ID )
			{
				if( !$order )
				{
					;
				}
				//数据插入不存在
				else if( !isset( $formatedData[$buildingId] ) )
				{
					$this->dbRecord['order'] += 1;
					$needFixDatas[] = "INSERT INTO `order` ( `uid` , `id` , `orderTypeId` , `finishTime` , `orderLevel` ) VALUES ( {$userId} , {$buildingId} , {$order['orderTypeId']} , {$order['finishTime']} , ". ( $order['orderLevel'] ? $order['orderLevel'] : 1 ) ." )";
					file_put_contents( '/tmp/dataError' , "order insert userId: {$userId}\n" , FILE_APPEND );
				}
				//数据不一致
				else if( $order['orderTypeId'] != $formatedData[$buildingId]['orderTypeId']
					|| $order['finishTime'] != $formatedData[$buildingId]['finishTime']
					|| ( $buildingId == Building_Gift::POSEIDON_TOWER_ID ? 1 : $order['orderLevel'] ) != $formatedData[$buildingId]['orderLevel']
				)
				{
					$this->dbRecord['order'] += 1;
					$needFixDatas[] = "UPDATE `order` SET `orderTypeId` = {$order['orderTypeId']} , `finishTime` = {$order['finishTime']} , `orderLevel` = ". ( $order['orderLevel'] ? $order['orderLevel'] : 1 ) ." WHERE `uid` = {$userId} AND `id` = {$buildingId}";
					file_put_contents( '/tmp/dataError' , "order update userId: {$userId}\n" , FILE_APPEND );
				}
				
				unset( $formatedData[$buildingId] );
			}
			//领地
			else 
			{
				if( $building['territory'])
				{
				foreach( $building['territory'] as $territoryId => $territory )
				{
				
					if( $territory == 1 )
					{
						continue;
					}
					$needFixCache = false;
					if(  $territoryId > 316999  )
					{	
						$cache = Common::getCache();
						$oldCache = $cache->get( $userId."_building" );
						foreach(   $oldCache['base'][310029]['territory'] as $terrId => $terrInfo )
						{ 
							if( $terrId > 316999 )
							{
									$needFixCache = true;
									unset( $building['territory'][$terrId] );
									unset(  $oldCache['base'][310029]['territory'][$terrId] );
							}
						}	
						if( $needFixCache == true )
						{	
							$cache->set( $userId."_building" ,  $oldCache );
						}
						continue;
					}
					//数据插入不存在
					if( !isset( $formatedData[$territoryId] ) )
					{
						$this->dbRecord['order'] += 1;
						$needFixDatas[] = "INSERT INTO `order` ( `uid` , `id` , `orderTypeId` , `finishTime` , `orderLevel` ) VALUES ( {$userId} , {$territoryId} , {$territory['orderTypeId']} , {$territory['finishTime']} , 0 )";
						file_put_contents( '/tmp/dataError' , "order insert userId: {$userId}\n" , FILE_APPEND );
					}
					//数据不一致
					else if( $territory['orderTypeId'] != $formatedData[$territoryId]['orderTypeId']
						|| $territory['finishTime'] != $formatedData[$territoryId]['finishTime']
					//	|| $territory['orderLevel'] != $formatedData[$territoryId]['orderLevel']
					)
					{
						$this->dbRecord['order'] += 1;
						$needFixDatas[] = "UPDATE `order` SET `orderTypeId` = {$territory['orderTypeId']} , `finishTime` = {$territory['finishTime']} , `orderLevel` = 0 WHERE `uid` = {$userId} AND `id` = {$territoryId}";
						file_put_contents( '/tmp/dataError' , "order update userId: {$userId}\n" , FILE_APPEND );
					}
					
					unset( $formatedData[$territoryId] );
				}
			}
			}
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $buildingId => $building )
			{
				$this->dbRecord['order'] += 1;
				$needFixDatas[] = "DELETE FROM `order` WHERE `uid` = {$userId} AND `id` = {$buildingId}";
			}
			file_put_contents( '/tmp/dataError' , "order delete userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 建筑物列表
	 * @param $dbEngine
	 * @param $cacheData
	 * @param $needFixDatas
	 */
	private function _compareBuilding( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `building` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'level' => $dbRecord['level'] ,
			);
		}
		
		foreach( $cacheData['base'] as $buildingId => $building )
		{
			//数据插入不存在
			if( !isset( $formatedData[$buildingId] ) )
			{
					$this->dbRecord['building'] += 1;
				$building['level'] = isset(   $building['level'] ) ? $building['level'] : 0; 
				$needFixDatas[] = "INSERT INTO `building` ( `uid` , `id` , `level` ) VALUES ( {$userId} , {$buildingId} , {$building['level']} )";
				file_put_contents( '/tmp/dataError' , "building insert userId: {$userId}\n" , FILE_APPEND );
			}
		
		
			if( $buildingId == 310029 &&  isset( $building['territory'] ) && !empty(  $building['territory'] ) )
			{
				foreach (    $building['territory']  as $territoryId => $info  )
				{
					$needFixCache = false;
					if(  $territoryId > 316999  )
					{	
						$cache = Common::getCache();
						$oldCache = $cache->get( $userId."_building" );
						foreach(   $oldCache['base'][310029]['territory'] as $terrId => $terrInfo )
						{ 
							if( $terrId > 316999 )
							{
									$needFixCache = true;
									unset( $building['territory'][$terrId] );
									unset(  $oldCache['base'][310029]['territory'][$terrId] );
							}
						}	
						if( $needFixCache == true )
						{	
							$cache->set( $userId."_building" ,  $oldCache );
						}
						continue;
					}
					
					
					if( !isset( $formatedData[$territoryId] ) )
					{
						$this->dbRecord['building'] += 1;
						$infoLevel = empty( $info['level'] ) ? 0 : $info['level'];
						$needFixDatas[] = "INSERT INTO `building` ( `uid` , `id` , `level` ) VALUES ( {$userId} , {$territoryId} , {$infoLevel} )";
						file_put_contents( '/tmp/dataError' , "building insert userId: {$userId}\n" , FILE_APPEND );
					}
					unset( $formatedData[$territoryId] );
				}
			}
			else 
			{
				//数据不一致
				if( $building['level'] != $formatedData[$buildingId]['level'] )
				{
					$building['level'] = isset( $building['level'] ) ? $building['level'] : 0;
					$this->dbRecord['building'] += 1;
					$needFixDatas[] = "UPDATE `building` SET `level` = {$building['level']} WHERE `uid` = {$userId} AND `id` = {$buildingId}";
					file_put_contents( '/tmp/dataError' , "building update userId: {$userId}\n" , FILE_APPEND );
				}
			}
			
			unset( $formatedData[$buildingId] );
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $buildingId => $building )
			{
				$this->dbRecord['building'] += 1;
				$needFixDatas[] = "DELETE FROM `building` WHERE `uid` = {$userId} AND `id` = {$buildingId}";
			}
			file_put_contents( '/tmp/dataError' , "building delete userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * Buff列表
	 * @param $userId
	 * @param $dbEngine
	 * @param $needFixDatas
	 * @param $cacheData
	 */
	private function _compareBuffList( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `buff` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'buffId' => $dbRecord['buffId'] ,
				'startTime' => $dbRecord['startTime'] ,
				'endTime' => $dbRecord['endTime'] ,
				'times' => $dbRecord['times'] ,
			);
		}
		
		foreach( $cacheData['buffList'] as $buffType => $buff )
		{
			//数据插入不存在
			if( !isset( $formatedData[$buffType] ) )
			{
				$this->dbRecord['buff'] += 1;
				$needFixDatas[] = "INSERT INTO `buff` ( `uid` , `id` , `buffId` , `startTime` , `endTime` , `times` ) VALUES ( {$userId} , {$buffType} , {$buff['buffId']} , {$buff['startTime']} , {$buff['endTime']} , {$buff['times']} )";
				file_put_contents( '/tmp/dataError' , "buff insert userId: {$userId}\n" , FILE_APPEND );
			}
			//数据不一致
			else if( $buff['buffId'] != $formatedData[$buffType]['buffId']
			|| $buff['startTime'] != $formatedData[$buffType]['startTime']
			|| $buff['endTime'] != $formatedData[$buffType]['endTime']
			|| $buff['times'] != $formatedData[$buffType]['times']
			)
			{
				$this->dbRecord['buff'] += 1;
				$needFixDatas[] = "UPDATE `buff` SET `buffId` = {$buff['buffId']} , `startTime` = {$buff['startTime']} , `endTime` = {$buff['endTime']} , `times` = {$buff['times']} WHERE `uid` = {$userId} AND `id` = {$buffType}";
				file_put_contents( '/tmp/dataError' , "buff update userId: {$userId}\n" , FILE_APPEND );
			}
			
			unset( $formatedData[$buffType] );
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $buffType => $buff )
			{
				$this->dbRecord['buff'] += 1;
				$needFixDatas[] = "DELETE FROM `buff` WHERE `uid` = {$userId} AND `id` = {$buffType}";
			}
			file_put_contents( '/tmp/dataError' , "buff delete userId: {$userId}\n" , FILE_APPEND );
		}
	}
	
	/**
	 * 道具列表
	 * @param dbEngine
	 * @param needFixDatas
	 * @param cacheData
	 */
	private function _compareItemList( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		//道具列表
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `bag_item` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'number' => $dbRecord['number'] ,
			);
		}
		
		foreach( $cacheData['itemList'] as $itemId => $item )
		{
			//数据插入不存在
			if( !isset( $formatedData[$itemId] ) )
			{
				$item['number'] = round( $item['number']);
				$needFixDatas[] = "INSERT INTO `bag_item` ( `uid` , `id` , `number` ) VALUES ( {$userId} , {$itemId} , {$item['number']} )";
				$this->dbRecord['bag_item'] += 1;
				file_put_contents( '/tmp/dataError' , "bag_item insert userId: {$userId}\n" , FILE_APPEND );
			}
			//数据不一致
			else if( $item['number'] != $formatedData[$itemId]['number']
			)
			{
				$this->dbRecord['bag_item'] += 1;
				$item['number'] = round( $item['number']);
				$needFixDatas[] = "UPDATE `bag_item` SET `number` = {$item['number']} WHERE `uid` = {$userId} AND `id` = {$itemId}";
				file_put_contents( '/tmp/dataError' , "bag_item update userId: {$userId}\n" , FILE_APPEND );
			}
			unset( $formatedData[$itemId] );
		}
		
		//有多余数据
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $itemId => $item )
			{
				$this->dbRecord['bag_item'] += 1;
				$needFixDatas[] = "DELETE FROM `bag_item` WHERE `uid` = {$userId} AND `id` = {$itemId}";
			}
			file_put_contents( '/tmp/dataError' , "bag_item delete userId: {$userId}\n" , FILE_APPEND );
		}
	}

	/**
	 * 装备列表信息
	 * @param dbEngine
	 * @param needFixDatas
	 * @param cacheData
	 */
	private function _compareEquipmentList( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		//装备列表
		$dbData = $dbEngine->fetchArray( "SELECT * FROM `bag_equipment` WHERE `uid` = {$userId}" );
		$formatedData = array();
		foreach( $dbData as $dbRecord )
		{
			$formatedData[$dbRecord['id']] = array(
				'itemId' => $dbRecord['itemId'] ,
				'enchantLevel' => $dbRecord['enchantLevel'] ,
				'isUsing' => $dbRecord['isUsing'] ? true : false ,
				'color' => $dbRecord['color'] ,
			);
		}
		
		foreach( $cacheData['equipmentList'] as $bagId => $equipment )
		{
			//数据插入不存在
			if( !isset( $formatedData[$bagId] ) )
			{
				$this->dbRecord['bag_equipment'] += 1;
				$needFixDatas[] = "INSERT INTO `bag_equipment` ( `uid` , `id` , `itemId` , `enchantLevel` , `isUsing` , `color` ) VALUES ( {$userId} , {$bagId} , {$equipment['itemId']} , {$equipment['enchantLevel']} , ". ( $equipment['isUsing'] ? 1 : 0 ) ." , {$equipment['color']} )";
				file_put_contents( '/tmp/dataError' , "equipmentList insert userId: {$userId}\n" , FILE_APPEND );
			}
			//数据不一致
			else if( $equipment['itemId'] != $formatedData[$bagId]['itemId']
				|| $equipment['enchantLevel'] != $formatedData[$bagId]['enchantLevel']
				|| $equipment['isUsing'] != $formatedData[$bagId]['isUsing']
				|| $equipment['color'] != $formatedData[$bagId]['color']
			)
			{
				$this->dbRecord['bag_equipment'] += 1;
				$needFixDatas[] = "UPDATE `bag_equipment` SET `itemId` = {$equipment['itemId']} , `enchantLevel` = {$equipment['enchantLevel']} , `isUsing` = ". ( $equipment['isUsing'] ? 1 : 0 ) ." , `color` = {$equipment['color']} WHERE `uid` = {$userId} AND `id` = {$bagId}";
				file_put_contents( '/tmp/dataError' , "equipmentList update userId: {$userId}\n" , FILE_APPEND );
			}
			
			unset( $formatedData[$bagId] );
		}
		
		if( count( $formatedData ) > 0 )
		{
			foreach( $formatedData as $bagId => $equipment )
			{
				$this->dbRecord['bag_equipment'] += 1;
				$needFixDatas[] = "DELETE FROM `bag_equipment` WHERE `uid` = {$userId} AND `id` = {$bagId}";
			}
			//有多余数据
			file_put_contents( '/tmp/dataError' , "equipmentList delete userId: {$userId}\n" , FILE_APPEND );
		}
	}

	/**
	 * 背包信息
	 * @param dbEngine
	 * @param needFixDatas
	 * @param cacheData
	 */
	private function _compareBagInfo( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		//背包信息
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `bag` WHERE `uid` = {$userId}" );
		if( $dbData['weightLimit'] != $cacheData['weightLimit'] )
		{
			$this->dbRecord['bag'] += 1;
			$needFixDatas[] = "INSERT INTO `bag` ( `uid` , `weightLimit` ) VALUES ( {$userId} , {$cacheData['weightLimit']} ) ON DUPLICATE KEY UPDATE `weightLimit` = {$cacheData['weightLimit']}";
			file_put_contents( '/tmp/dataError' , "bag update userId: {$userId}\n" , FILE_APPEND );
		}
	}

	
	/**
	 * 比对竞技场数据
	 * @param dbEngine
	 * @param cacheData
	 * @param needFixDatas
	 */
	private function _compareArenaData( $userId , $dbEngine , $cacheData , & $needFixDatas )
	{
		$dbData = $dbEngine->fetchOneAssoc( "SELECT * FROM `arena` WHERE `uid` = {$userId}" );
		if( $dbData['arenaScore'] != $cacheData['arenaScore']
			|| $dbData['fightTimes'] != $cacheData['fightTimes']
			|| $dbData['lootLimit'] != $cacheData['lootLimit']
			|| $dbData['successTimes'] != $cacheData['successTimes']
		)
		{
			$this->dbRecord['arena'] += 1;
			$needFixDatas[] = "INSERT INTO `arena` ( `uid` , `arenaScore` , `fightTimes` , `lootLimit` , `successTimes` ) VALUES ( {$userId} , {$cacheData['arenaScore']} , {$cacheData['fightTimes']} , {$cacheData['lootLimit']} , {$cacheData['successTimes']} ) ON DUPLICATE KEY UPDATE `arenaScore` = {$cacheData['arenaScore']} , `fightTimes` = {$cacheData['fightTimes']} , `lootLimit` = {$cacheData['lootLimit']} , `successTimes` = {$cacheData['successTimes']}";
			file_put_contents( '/tmp/dataError' , "arena update userId: {$userId}\n" , FILE_APPEND );
		}
	}

	
	private function _fixBuildingOrderTime( $userId )
	{
		$dataBuilding = Data_Building_Model::getInstance( $userId , true );
		$order = $dataBuilding->getBuildingOrderInfo( Building_Production::BUILDING_ID_TAVERN );
		$order['finishTime'] -= 28800;
		$dataBuilding->setBaseOrder( Building_Production::BUILDING_ID_TAVERN , $order );
		if( Ship_Model::getInstance( $userId )->getMP() < Ship_Model::getInstance( $userId )->getMPLimit() )
		{
			Ship_Model::getInstance( $userId )->changeMP( Ship_Model::getInstance( $userId )->getMPLimit() - Ship_Model::getInstance( $userId )->getMP() );
		}
	}
	
	/**
	 * 检查一个用户的订单时间
	 * @param	int $userId	用户ID
	 * @return	boolean
	 */
	private function _checkBuildingOrderTime( $userId )
	{
		$building = Building_Factory::createBuilding( $userId , Building_Production::BUILDING_ID_TAVERN );
		if( $building->getStatus() == Building_Production::STATUS_BUSY )
		{
			$dataBuilding = Data_Building_Model::getInstance( $userId );
			$order = $dataBuilding->getBuildingOrderInfo( Building_Production::BUILDING_ID_TAVERN );
			$orderConfig = $this->_getOrderConfig( Building_Production::BUILDING_ID_TAVERN , $order['orderTypeId'] , $order['orderLevel'] );
			if( $orderConfig['produceTime'] + $_SERVER['REQUEST_TIME'] < $order['finishTime'] )
			{
				return true;
			}
		}
		return false;
	}
	
	private function _checkUserLastRefreshMPTime( $userId )
	{
		$dataShip = Data_Ship_Model::getInstance( $userId );
		$configShip = Common::getConfig( 'ShipConfig' );
		if( $dataShip->getLastRefreshMPTime() > $_SERVER['REQUEST_TIME'] + $configShip['mpUnitTime'] )
		{
			return true;
		}
		return false;
	}
	
	private function _fixUserLastRefreshMPTime( $userId )
	{
		$dataShip = Data_Ship_Model::getInstance( $userId , true );
		$dataShip->setLastRefreshMPTime( $dataShip->getLastRefreshMPTime() - 28800 );
	}
	
	/**
	 * 获取订单配置
	 * @param int $orderTypeId
	 */
	private function _getOrderConfig( $buildingId , $orderTypeId , $level )
	{
		$config = Common::getConfig( 'BaseConfig' );
		if( isset( $config[$buildingId]['order'][$level][$orderTypeId] ) )
		{
			return $config[$buildingId]['order'][ $level ][$orderTypeId];
		}
		else
		{
			$this->_logs[] = "Not Order Config: buidlingId: {$buildingId} , orderTypeId:{$orderTypeId} , level: {$level}";
		}
	}
	
	/**
	 * 获取用户ID索引
	 * @return	array(
	 * 				users:array(
	 * 					array(
	 * 						userId:int
	 * 					) ,
	 * 					...
	 * 				)
	 * 				next:array(
	 * 					dbId:int
	 * 					partStart:int
	 * 					partLimit:int
	 * 				)
	 * 			)
	 * @author song.wang
	 */
	private static function _iterateUserId( $partStart = 0 , $partLimit = 10 )
	{
		//读取所有用户信息
		$users = array();
		$mysqlConfig = Common::getConfig( 'mysqlDb' );
		$indexDb = new MysqlDb( $mysqlConfig ['index'] );
		
		$users = $indexDb->fetchArray( "SELECT `userid` AS `userId` FROM `index_0` LIMIT {$partStart} , {$partLimit}" );
		
		$resultCount = $indexDb->fetchOneAssoc( "SELECT count(*) as `count` FROM `index_0`");
		$partPage = ceil( $resultCount['count'] / $partLimit ) ;
	
		$end = false;
		if( intval( ($partStart + $partLimit) / $partLimit ) < $partPage )
		{
			$partStart += $partLimit;
		}
		else
		{
			$end = true;
		}
		
		return array( 'users' => $users, 'next' => array( 'partStart' => $partStart , 'end' => $end ) );
	}
	
	/**
	 * 获取用户ID索引
	 * @return	array(
	 * 				users:array(
	 * 					array(
	 * 						userId:int
	 * 					) ,
	 * 					...
	 * 				)
	 * 				next:array(
	 * 					dbId:int
	 * 					partStart:int
	 * 					partLimit:int
	 * 				)
	 * 			)
	 * @author song.wang
	 */
	private static function _iterateUserIdFromMemcache( $cacheId = 0 , $slabId = 1 , $partStart = 0 )
	{
		$userIds = array();
		$memcachedConfig = Common::getConfig( 'memcache' );
		$memcachedConfig = $memcachedConfig['data'];
		
		$memcache = new Memcache();
		$memcache->connect( $memcachedConfig[$cacheId]['host'] , $memcachedConfig[$cacheId]['port'] );
		
		$cdump = $memcache->getExtendedStats( 'cachedump' , $slabId , 9999999 );
		foreach( $cdump as $entries )
		{
			if( $entries )
			{
				$keys = array_keys( $entries );
				foreach( $keys as $eName )
				{
					if( preg_match( '/(\d+)_user_ability/', $eName , $matches ))
					{
						if( $matches[1] && !in_array( $matches[1], $userIds ) )
						{
							$userIds[] = $matches[1];
						}
					}
				}
			}
		}
		
		$allSlabs = $memcache->getExtendedStats( 'slabs' );
		$slabIds = array_keys( $allSlabs[$memcachedConfig[$cacheId]['host'] .':'. $memcachedConfig[$cacheId]['port']] );
	
		$end = false;
		if( array_search( $slabId , $slabIds ) < count( $slabIds ) )
		{
			$slabId = $slabIds[array_search( $slabId , $slabIds ) + 1];
		}
		else if( ( $cacheId + 1 ) < count( $memcachedConfig ) )
		{
			$cacheId++;
			$partStart = 0;
		}
		else
		{
			$end = true;
		}
		
		return array( 'userIds' => $userIds , 'next' => array( 'cacheId' => $cacheId , 'slabId' => $slabId , 'partStart' => $partStart , 'end' => $end ) );
	}
}
