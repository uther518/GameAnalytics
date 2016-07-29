<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta content="IE=8" http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="http://www.talkinggame.com/pages/images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<title>ecngame</title>
    <script>
	function show_div( div )
	{
		$( "#"+div ).slideToggle( 500 );	
	}
	</script>
	<style>
		.placeholderWrap{
		    position: relative; 
		    display: inline-block;}
		.placeholderWrap label{
		    color: #555;
		    position: absolute; 
		    top: 10px; left: 6px; /* Might have to adjust this based on font-size */
		    pointer-events: none;
		    display: block;
		}

		.placeholder-focus label{color: #999;}/* could use a css animation here if desired*/
		.placeholder-changed label{
		    display: none;
		}
		
		ol li{
			
			height:33px;
			
		}
		

	</style>
	<!--[if lt IE 9]>
		<script src="js/libs/excanvas.compiled.js" type="text/javascript"></script>
	<![endif]-->
	




<style type="text/css" charset="utf-8">/* See license.txt for terms of usage */
</style>
</head>


<body><iframe style="position: fixed; display: none; opacity: 0;" frameborder="0"></iframe><div style="position: absolute; z-index: 1000000000; display: none; top: 50%; left: 50%; overflow: auto;" class="jiathis_style"></div><div style="position: absolute; z-index: 1000000000; display: none; overflow: auto;" class="jiathis_style"></div><!-- 左侧菜单 -->
		    <div id="menu-banner"><div class="menu l">
	<ul>
		
			<li class="hover">
				<a class="navigate Dashboard hover" href="?f=right" target="right">
					<span><?php Lang( 'app_info' );?></span>
				</a>
				
			</li>
		
			<?php 
				if( !isset($_SESSION['adminInfo']['viewList'] ))
				{
					$_SESSION['adminInfo']['viewList'] = array();
				}
			
				if( in_array( 2 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			<li class="">
				<a class="more_icon Players" url-data="">
					<span onclick="show_div( 'menu_div1' )"><?php Lang( 'users' );?></span>
				</a>
				
					<ol id='menu_div1' style="display:none;" >
						<li><a href="?f=userData" target="right"><?php Lang( 'new_users' );?></a></li>
						<li><a href="?f=userData&act=newUserDevice" target="right"><?php Lang( 'new_device' );?></a></li>
						<li><a href="?f=userData&act=loginDevice" target="right"><?php Lang( 'login_device' );?></a></li>
						<li><a href="?f=userData&act=loginUser" target="right"><?php Lang( 'dau' );?></a></li>
						<li><a href="?f=wau" target="right"><?php Lang( 'wau' );?></a></li>
						<li><a href="?f=mau" target="right"><?php Lang( 'mau' );?></a></li>
						<li><a href="?f=dmau" target="right"><?php Lang( 'dau_mau' );?></a></li>
						<li><a href="?f=keepLogin&days=2" target="right"><?php Lang( 'keep_login2' );?></a></li>
						<li><a href="?f=keepLogin&days=3" target="right"><?php Lang( 'keep_login3' );?></a></li>
						<li><a href="?f=keepLogin&days=4" target="right"><?php Lang( 'keep_login4' );?></a></li>
						<li><a href="?f=keepLogin&days=5" target="right"><?php Lang( 'keep_login5' );?></a></li>
						<li><a href="?f=keepLogin&days=6" target="right"><?php Lang( 'keep_login6' );?></a></li>
						<li><a href="?f=keepLogin&days=7" target="right"><?php Lang( 'keep_login7' );?></a></li>
						<li><a href="?f=keepLogin&days=14" target="right"><?php Lang( 'keep_login14' );?></a></li>
						<li><a href="?f=keepLogin&days=30" target="right"><?php Lang( 'keep_login30' );?></a></li>
					<!--  
						<li><a href="2.html" target="right">留存</a></li>
						<li><a href="3.html" target="right">转化</a></li>
						<li><a href="4.html" target="right">流失</a></li>
						<li><a href="5.html" target="right">游戏习惯</a></li>
						<li><a href="6.html" target="right">设备</a></li>
					-->
					</ol>
				
			</li>
			<?php 
				}
				if( in_array( 3 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
			<li class="">
				<a class="more_icon revenue" url-data="">
					<span onclick="show_div( 'menu_div2' )"><?php Lang( 'recharge_analysis' );?></span>
				</a>
				<ol id='menu_div2' style="display:none">
					<li><a href="?f=rechargeRecord" target="right"><?php Lang( 'recharge_record' );?></a></li>
					<li><a href="?f=recharge&act=rmbs" target="right"><?php Lang( 'recharge_money' );?></a></li>
					<li><a href="?f=recharge&act=coins" target="right"><?php Lang( 'recharge_game_coin' );?></a></li>
					<li><a href="?f=recharge&act=times" target="right"><?php Lang( 'recharge_times' );?></a></li>
					<li><a href="?f=recharge&act=uids" target="right"><?php Lang( 'recharge_users' );?></a></li>
					
					<li><a href="?f=recharge&act=chargeRate" target="right"><?php Lang( 'recharge_rate_of_day' );?></a></li>
					<li><a href="?f=recharge&act=darpu" target="right"><?php Lang( 'arpu_of_day' );?></a></li>
					<li><a href="?f=recharge&act=darppu" target="right"><?php Lang( 'arppu_of_day' );?></a></li>
					
					<li><a href="?f=mRecharge&act=chargeRate" target="right"><?php Lang( 'recharge_rate_of_month' );?></a></li>
					<li><a href="?f=mRecharge&act=marpu" target="right"><?php Lang( 'arpu_of_month' );?></a></li>
					<li><a href="?f=mRecharge&act=marppu" target="right"><?php Lang( 'arppu_of_month' );?></a></li>
				</ol>
			</li>
		
			<?php 
				}
				if( in_array( 4 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
			<li class="">
			<a class="more_icon virtualEconomy" url-data="">
				<span onclick="show_div( 'menu_online')"><?php Lang( 'online_analysis' );?></span>
			</a>
				<ol id='menu_online' style="display:none">
					<li><a href="?f=userOnline" target="right"><?php Lang( 'online_user' );?></a></li>
					<li><a href="?f=onlineStats" target="right"><?php Lang( 'acu_pcu' );?></a></li>
					
				</ol>
				
			</li>
			<?php 
				}
				if( in_array( 5 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			

		
			<li class="">
				<a class="more_icon virtualEconomy" url-data="">
					<span onclick="show_div( 'menu_div3')"><?php Lang( 'virturl_coin_analysis' );?></span>
				</a>
					<ol id='menu_div3' style="display:none">
						<li><a href="?f=gainCoin" target="right"><?php Lang( 'charge_coin_gain' );?></a></li>
						<li><a href="?f=consumeCoin" target="right"><?php Lang( 'charge_coin_consume' );?></a></li>
						<li><a href="?f=coinTotal" target="right"><?php Lang( 'charge_coin_have' );?></a></li>
						<li><a href="?f=gainGold" target="right"><?php Lang( 'game_gold_gain' );?></a></li>
						<li><a href="?f=consumeGold" target="right"><?php Lang( 'game_gold_consume' );?></a></li>
						<li><a href="?f=goldTotal" target="right"><?php Lang( 'game_gold_have' );?></a></li>
						
					</ol>
				
			</li>
			<?php 
				}
				if( in_array( 6 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
		
			<li class="">
				<a class="LevelAnalysis more_icon" url-data="">
					<span onclick="show_div( 'menu_div4')"><?php Lang( 'level_analysis' );?></span>
				</a>
					<ol id='menu_div4' style="display:none">
						<li><a href="?f=level&act=levelMap" target="right"><?php Lang( 'level_detail' );?></a></li>
						<li><a href="?f=level&act=levelStage" target="right"><?php Lang( 'level_stage' );?></a></li>
						<li><a href="?f=level&act=levelProc" target="right"><?php Lang( 'new_user_progress' );?></a></li>
						<li><a href="?f=level&act=levelTop" target="right"><?php Lang( 'level_top_100' );?></a></li>
					</ol>
				
			</li>
			
			<?php 
				}
				if( in_array( 7 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
			<li class="">
				<a class="LevelAnalysis more_icon" url-data="">
					<span onclick="show_div( 'menu_newbie')"><?php Lang( 'newbie_analysis' );?></span>
				</a>
					<ol id='menu_newbie' style="display:none">
						<li><a href="?f=newbie&act=newbieMap" target="right"><?php Lang( 'newbie_detail' );?></a></li>
						<li><a href="?f=newbie&act=newbieTurn" target="right"><?php Lang( 'newbie_change_rate' );?></a></li>
						<li><a href="?f=newbieNoAct" target="right"><?php Lang( 'newbie_no_action' );?></a></li>
					</ol>
				
			</li>
			
			<?php 
				}
				if( in_array( 8 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
			<li class="">
				<a class="LevelAnalysis more_icon" url-data="">
					<span onclick="show_div( 'menu_active')"><?php Lang( 'participation_analysis' );?></span>
				</a>
					<ol id='menu_active' style="display:none">
						<li><a href="?f=playMethod&act=times" target="right"><?php Lang( 'parti_times' );?></a></li>
						<li><a href="?f=playMethod&act=uids" target="right"><?php Lang( 'parti_users' );?></a></li>
					</ol>
				
			</li>
			
			<?php 
				}
				if( in_array( 9 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
			<li class="">
				<a class="LevelAnalysis more_icon" url-data="">
					<span onclick="show_div( 'menu_item')"><?php Lang( 'item_analysis' );?></span>
				</a>
					<ol id='menu_item' style="display:none">
						<li><a href="?f=gainItem" target="right"><?php Lang( 'item_gain_record' );?></a></li>
						<li><a href="?f=lostItem" target="right"><?php Lang( 'item_consume_record' );?></a></li>
						<li><a href="?f=itemGainRank" target="right"><?php Lang( 'item_gain_nums' );?></a></li>
						<li><a href="?f=itemLostRank" target="right"><?php Lang( 'item_use_nums' );?></a></li>
					</ol>	
			</li>
			
			
			<?php 
				}
				if( in_array( 11 , $_SESSION['adminInfo']['viewList']))
				{
			?>
			
			<li class="">
				<a class="more_icon CustomEvent" url-data="">
					<span onclick="show_div( 'menu_div7')"><?php Lang( 'custom_event' );?></span>
				</a>
					<ol id='menu_div7' style="display:none">
						<li><a href="?f=customActQuery" target="right"><?php Lang( 'event_query' );?></a></li>
						<li><a href="?f=customActQuery&act=counter" target="right"><?php Lang( 'event_counter' );?></a></li>
					</ol>
			</li>
			
			<?php 
				}
			?>
			<li>
				<a class="more_icon partnerExtention"  >
					<span onclick="show_div( 'menu_div6')"><?php Lang( 'chanal_info' );?></span>
				</a>
				
					<ol id='menu_div6' style="display:none">
						<li><a href="?f=userData" target="right"><?php Lang( 'regis_user' );?></a></li>
						<li><a href="?f=userData&act=loginUser" target="right"><?php Lang( 'login_user' );?></a></li>
						<li><a href="?f=rechargeRecord" target="right"><?php Lang( 'recharge_record' );?></a></li>
					</ol>
			</li>
			
			
			<?php 
				$_SESSION['adminInfo']['writable'] = $_SESSION['adminInfo']['writable'] ? $_SESSION['adminInfo']['writable'] : array();
				if( in_array( 101 , $_SESSION['adminInfo']['writable'] ) )
				{
			?>
			<li>
				<a class="more_icon partnerExtention" url-data="">
					<span onclick="show_div( 'menu_svr')"><?php Lang( 'server_manage' );?></span>
				</a>
				
					<ol id='menu_svr' style="display:none">
						<li><a href="?f=channalMgr" target="right"><?php Lang( 'chanal_manage' );?></a></li>
						<li><a href="?f=channalMgr" target="right"><?php Lang( 'server_info' );?></a></li>
					</ol>
			</li>
			<?php }?>
	</ul>
</div></div>