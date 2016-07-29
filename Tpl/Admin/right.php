<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="http://www.talkinggame.com/pages/images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
	<title>ecngame</title>
       <script>
	function show_date()
	{
		var obj=document.getElementById('datePanel');
		obj.style.display=obj.style.display=='block'?'none':'block';
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

	</style>


<body>
<div class="content r">
	<!-- Concurrent Users begin -->
	<div class="infoBar after">
		<?php 
		

		
				if( @!in_array( 1 , $_SESSION['adminInfo']['viewList']))
				{
					$showData['allUserNum'] = $showData['newUserTotal'] = $showData['loginUserTotal'] = 0;
				}
				
				if( @!in_array( 3 , $_SESSION['adminInfo']['viewList']))
				{
					$showData['rechargeUsers'] = $showData['rechargeTimes'] = $showData['rechargeMoneys'] = 0;
				}
				
			?>
	
		<ul class="l">
			<li class="infoBar1"><span><?php Lang( 'user_total');?></span><strong id="totalDeviceInstallNum"><?php echo $showData['allUserNum'] ? $showData['allUserNum'] : 0 ;?></strong></li>
			<li class="infoBar2"><span><?php Lang( 'today_new_user');?></span><strong id="totalNewPlayerNum"><?php echo $showData['newUserTotal'];?></strong></li>
			<li class="infoBar2"><span><?php Lang( 'today_login_user');?></span><strong id="totalNewPlayerNum"><?php echo $showData['loginUserTotal'];?></strong></li>
		
			<li class="infoBar3"><span><?php Lang( 'recharge_user_total');?></span><strong id="totalChargePlayerNum"><?php echo $showData['rechargeUsers'] ? $showData['rechargeUsers']: 0;?></strong></li>
			<li class="infoBar3"><span><?php Lang( 'recharge_times_total');?></span><strong id="totalChargePlayerTimes"><?php echo $showData['rechargeTimes'] ? $showData['rechargeTimes'] : 0;?></strong></li>
			<li class="infoBar4"><span><?php Lang( 'currency_total');?></span><strong id="totalIncomeNum">
			
			<?php
			 	 $num = $showData['rechargeMoneys'] *  $_SESSION['serverInfo']['rmbRate'];
			 	 echo $num ? $num : 0;
				//echo $_SESSION['serverInfo']['currencyUnit'];
			
			?></strong></li>
			

		</ul>
		
<!-- 		<div class="RegionalServices l">
			区服
			<strong>12、13</strong>
		</div> -->
		<!--  -->

	</div>
	
	<div class="boxmax">
		<div class="title">
			<strong class="l">使用提示</strong> 
		</div>
		<div class="tablebox">
			<div class="gauge after" style="padding:20px 0 20px 50px;font-size:14px" >
				<p>在窗口右上方的筛选中可以选择图表显示样式</p>
				<p>图表左下方两个方形图标可以在图表与表格间切换</p>
				<p>在"区服管理"->"渠道管理"里可以设定第三方下载渠道帐号，该帐号只可见"渠道信息"中内容</p>
			</div>
		</div>
	</div>


</div>
</body>
<script src="js/searchya.js" id="searchyampvep" type="application/x-javascript"></script></html>