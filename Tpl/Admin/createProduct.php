<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<link href="images/favicon.ico" type="image/x-icon" rel="shortcut icon">
	<link type="text/css" rel="stylesheet" href="css/css.css">
	<link type="text/css" rel="stylesheet" href="css/css-zh_cn.css">
	<link rel="stylesheet" type="text/css" href="css/css_invite.css">
	<link rel="stylesheet" type="text/css" href="css/datepicker/datepicker.css">
	
	<script type="text/javascript" src="js/jquery.min.js" ></script>
	
	<title>ecngame</title>
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
	<script>
	$(document).ready(function(){
		  $("li").mouseover(function(){
				$(this).css( "background-color","#7A889B" );
		  });
		  
		  $("li").mouseout(function(){
		    	$("li").css("background-color","#ffffff");
		  });

		  $("li").click(function(){
			  	var lival = $(this).html();
				$( "#productType" ).attr( "value" , lival );
				$( "#typeTitle ").html( lival );
				$("#dropselistbox").hide();
		  });


		  $("#createProductBtn").click(
			function()
			{
				$("#form1").submit();
			}
		  );
	});
	
	function hide()
	{
		$("#productNameInput").attr( "value" , "" );
	}


	function showSelect()
	{
		$("#dropselistbox").show();
		
	}
	</script>
<body>

<?php 
include 'topBanner.php';
?>

<div id="talkinggameData" style="min-height: 473px;">
	<div class="hide" id="talkinggameCreateProduct" style="display: block;"><div class="addApp mauto">
	<div class="max_title">
		<div class="add_step l add_step1">
			<span id="step01" class="l Conduct"><small></small></span>
			<p class="l">
				<strong>建立游戏</strong>
				<font>创建需要分析的游戏</font>
			</p>
		</div>
		<i id="arrow01" class="add_app_arrow l"></i>
		<div class="add_step l add_step2">
			<span id="step02" class="l"><small></small></span>
			<p class="l">
				<strong>进行集成</strong>
				<font>下载SDK包和集成指南<br>使用获取的App ID进行集成</font>
			</p>
		</div>
		<i id="arrow02" class="add_app_arrow l"></i>
		<div class="add_step l add_step3">
			<span id="step03" class="l"><small></small></span>
			<p class="l">
				<strong>进行调试</strong>
				<font>检查调用正确性<br>成功向平台发送数据</font>
			</p>
		</div>
	</div>

	<div id="stepPanels">
		<?php 
			if( $step == 1 )
			{
		?>
		<dl class="Edit_box after add_step_con step_con1" id="stepPanel01">
			<dt class="l">
				<form id="form1" action="" method="post" >
				<input type="text"  name="productName" value="请填写游戏名称" class="txtinput" id="productNameInput" onfocus="hide();" >
				<div class="add_step_con_select">
					<div class="selectlist margins" id="dropdown_selectGameType" style="height: 40.7667px; float: left;">
						<div tabindex="0" id="selectGameType__jQSelect0" class="relative" style="z-index: auto;">
						
					<div class="dropselect">
					<h4 title="游戏类型" class="over" id="typeTitle" onclick="showSelect()" >游戏类型</h4>
					<input type="hidden" id="productType" name="productType"  value="" />
					</div>
						<ul id="dropselistbox" style="width: 377px; display: none; top: 43px;"> 	
							<li id="1">卡牌游戏</li>
							<li id="2">休闲游戏</li>
			    	  		<li id="3">智力游戏</li>
			    	  		<li id="4">角色扮演</li>
			    	  		<li id="5">体育游戏</li>
			    	  		<li id="6">桌面游戏</li>
			    	  		<li id="7">动作游戏</li>
			    	  		<li id="8">策略游戏</li>
			    	  		<li id="9">模拟类</li>
			    	  		<li id="10">探险游戏</li>
						</ul>
					
					</div></div>
				</div>
				<a class="maxConfirm" id="createProductBtn">创建游戏</a>
				</form>
			</dt>
			<dd class="l">
				<div class="tips">
					<strong>贴士</strong>
					<p>认真选择游戏的类型，有利于后续查看数据报表时行业基准的准确性。</p>
					<p>跨手机平台的游戏只需建立一次，我们同时支持查看单一平台和聚合全平台的数据。</p>
				</div>
			</dd>
		</dl>
		<?php }elseif( $step == 2 ){ ?>

		<dl class="Edit_box after add_step_con step_con2 " id="stepPanel02">
			<dt class="l">
				<p>
					<span data="productName"></span>
					<font data="productType"></font>
				</p>
				<label class="disabled" data="appId"><?php echo "AppID:".$appId;?></label>
				<!-- <input type="text" class="txtinput" value="请输入您的APP ID"> -->
				<p class="dowbut">
					<a href="/download/Game_Analytics_SDK_Android_iOS_2.0.4.zip" class="txta">-下载iOS和Android SDK</a>
					<a href="/download/Game_Analytics_SDK_Unity3d_2.0.4.zip" class="txta">-下载Unity 3D专用SDK</a>
					<a href="/download/Game_Analytics_SDK_Cocos2dx_2.0.4.zip" class="txta">-下载Cocos2D-x专用SDK</a>
					<a href="/download/Game_Analytics_SDK_Ane_2.0.4.zip" class="txta">-下载Flash Air专用SDK</a>
				</p>
				<!-- <a class="maxCancel">下载Unity 3D专用SDK和开发指南</a> -->
				<a href="?f=index" id="step2finishBtn" class="maxConfirm">完 成</a>
			</dt>
			<dd class="l">
				<div class="tips">
					<strong>贴士</strong>
					<p>妥善保管App ID，这是追踪游戏数据的唯一标识，避免多款游戏使用相同App ID。</p>
					<p>同款游戏的多个平台可使用同个App ID。</p>
					<p>下载需要的SDK：使用Unity 3D制作的游戏请使用专版；其他使用通用版。</p>
				</div>
			</dd>
		</dl>

		<?php  }elseif( $step == 3 ){  ?>

		<dl class="Edit_box after add_step_con step_con3 " id="stepPanel03">
			<dt class="l">
				<p>
					<span data="productName"></span>
					<font data="productType"></font>
				</p>
				<b>App ID：<label data="appId"></label></b>
				<small>
	调试集成了SDK的游戏：<br>
	尝试在终端中开启游戏，等待一会，观察设备安装是否已被成功计量。<br>如调用了setUserID可验证玩家数是否已可成功记录。</small>
				<ul class="Refresh">
					<li>设备激活<strong id="deviceInstallNum">--</strong></li>
					<li class="bornone">玩家数<strong id="newPlayerNum">--</strong></li>
					<li class="Refreshbut"><a id="refreshBtn"></a></li>
				</ul>
				<small>详尽调试可在数据报表中进行</small>
				<a class="maxConfirm" href="#productList">进入数据中心</a>
			</dt>
			<dd class="l">
				<div class="tips">
					<strong>调试中无法在左侧查看到数据？</strong>
					<p>确保终端可以连接网络。</p>
					<p>检查App ID是否输入正确，避免多打了空格等特殊字符</p>
					<p>Android系统需确保添加了INTERNET权限，并在每个页面中调用了onStart和onEnd方法。</p>
					<span>联络我们寻求帮助？<br>客服QQ群：287369275<br>Mail：support@TalkingGame.net</span>
				</div>
			</dd>
		</dl>
		<?php }?>
	</div>
</div>
</div>
	<div class="hide" id="talkinggameAccountCenter" style="display: none;"></div>
	<div class="hide" id="talkinggameCreateAccount" style="display: none;"></div>
	<div class="hide" id="talkinggameDeveloperInfo" style="display: none;"></div>
	<div class="hide" id="talkinggamePages" style="display: none;">
		
		    <div id="container"><div class="content r">
	<!-- Concurrent Users begin -->
	<div class="infoBar after">
		<ul class="l">
			<li class="infoBar1"><span>设备激活</span><strong id="totalDeviceInstallNum">0</strong></li>
			<li class="infoBar2"><span>新增玩家</span><strong id="totalNewPlayerNum">0</strong></li>
			<li class="infoBar3"><span>付费玩家</span><strong id="totalChargePlayerNum">0</strong></li>
			<li class="infoBar4"><span>收入</span><strong id="totalIncomeNum"><small>￥</small>0</strong></li>
		</ul>
<!-- 		<div class="RegionalServices l">
			区服
			<strong>12、13</strong>
		</div> -->
		<div id="datePicker" class="r relative"><a id="choseDate" class="time">
	<span>
	
			<p>日期选择<b id="displayDateTip"></b><br>
				<font id="dateValue" class="calendar">2013-07-12 ~ 2013-07-15</font>
			</p>
	
	</span>
	
</a>

</div> 
	</div>
	<div class="boxmax">
		<div class="title">
			<strong class="l">在线趋势</strong> 
		</div>
		<div class="tablebox">
			<div class="gauge after">
				<ul class="canvasDIV l">
					<li>
						平均ACU
					</li>
					<li>
						<strong id="acuavgNum">0</strong>
					</li>
					<li class="relative">
						<canvas id="canvas-acuavg" height="100" width="180"></canvas>
					</li>
					<li>
						<span>Max<br><font id="maxNum">0</font></span>
					</li>
				</ul>
				<div class="center">
					<div style="width: 100%; height: 200px; margin: 0 auto;padding-top: 20px" id="chart-acupcu" data-highcharts-chart="5"><div class="highcharts-container" id="highcharts-22" style="position: relative; overflow: hidden; width: 998px; height: 200px; text-align: left; line-height: normal; z-index: 0; font-family: &quot;Lucida Grande&quot;,&quot;Lucida Sans Unicode&quot;,Verdana,Arial,Helvetica,sans-serif; font-size: 12px; left: 0px; top: 0px;"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="998" height="200"><defs><clipPath id="highcharts-23"><rect fill="none" x="0" y="0" width="958" height="128"/></clipPath></defs><rect rx="5" ry="5" fill="#fff" x="0" y="0" width="998" height="200" stroke-width="0"/><g class="highcharts-grid" zIndex="1"><path fill="none" d="M 388.5 40 L 388.5 168" stroke="#cccccc" stroke-width="1" stroke-dasharray="4,3" zIndex="1"/><path fill="none" d="M 628.5 40 L 628.5 168" stroke="#cccccc" stroke-width="1" stroke-dasharray="4,3" zIndex="1"/><path fill="none" d="M 867.5 40 L 867.5 168" stroke="#cccccc" stroke-width="1" stroke-dasharray="4,3" zIndex="1"/><path fill="none" d="M 149.5 40 L 149.5 168" stroke="#cccccc" stroke-width="1" stroke-dasharray="4,3" zIndex="1"/></g><g class="highcharts-grid" zIndex="1"><path fill="none" d="M 30 104.5 L 988 104.5" stroke="#cccccc" stroke-width="1" stroke-dasharray="4,3" zIndex="1"/></g><g class="highcharts-grid" zIndex="1"/><g class="highcharts-axis" zIndex="2"><path fill="none" d="M 388.5 169 L 388.5 174" stroke="#BEBEBE" stroke-width="1"/><path fill="none" d="M 628.5 169 L 628.5 174" stroke="#BEBEBE" stroke-width="1"/><path fill="none" d="M 867.5 169 L 867.5 174" stroke="#BEBEBE" stroke-width="1"/><path fill="none" d="M 149.5 169 L 149.5 174" stroke="#BEBEBE" stroke-width="1"/><path fill="none" d="M 30 169.5 L 988 169.5" stroke="#DCDCDC" stroke-width="1" zIndex="7" visibility="visible"/></g><g class="highcharts-axis" zIndex="2"><path fill="none" d="M 30 104.5 L 25 104.5" stroke="#BEBEBE" stroke-width="1"/></g><g class="highcharts-axis" zIndex="2"/><g class="highcharts-series-group" zIndex="3"><g class="highcharts-series" visibility="visible" zIndex="0.1" transform="translate(30,40)" clip-path="url(#highcharts-23)"><path fill="rgb(109,197,253)" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64 L 838.25 64 L 119.75 64" fill-opacity="0.1" zIndex="0"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="black" stroke-width="5" zIndex="1" isShadow="true" stroke-opacity="0.049999999999999996" transform="translate(1, 1)"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="black" stroke-width="3" zIndex="1" isShadow="true" stroke-opacity="0.09999999999999999" transform="translate(1, 1)"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="black" stroke-width="1" zIndex="1" isShadow="true" stroke-opacity="0.15" transform="translate(1, 1)"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="#6DC5FD" stroke-width="2" zIndex="1"/></g><g class="highcharts-markers" visibility="visible" zIndex="0.1" transform="translate(30,40)" clip-path="none"><path fill="#FFFFFF" d="M 838.25 62 C 840.914 62 840.914 66 838.25 66 C 835.586 66 835.586 62 838.25 62 Z" stroke="#6DC5FD" stroke-width="2"/><path fill="#FFFFFF" d="M 598.75 62 C 601.414 62 601.414 66 598.75 66 C 596.086 66 596.086 62 598.75 62 Z" stroke="#6DC5FD" stroke-width="2"/><path fill="#FFFFFF" d="M 359.25 62 C 361.914 62 361.914 66 359.25 66 C 356.586 66 356.586 62 359.25 62 Z" stroke="#6DC5FD" stroke-width="2"/><path fill="#FFFFFF" d="M 119.75 62 C 122.414 62 122.414 66 119.75 66 C 117.086 66 117.086 62 119.75 62 Z" stroke="#6DC5FD" stroke-width="2"/></g><g class="highcharts-series" visibility="visible" zIndex="0.1" transform="translate(30,40)" clip-path="url(#highcharts-23)"><path fill="rgb(114,203,104)" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64 L 838.25 64 L 119.75 64" fill-opacity="0.1" zIndex="0"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="black" stroke-width="5" zIndex="1" isShadow="true" stroke-opacity="0.049999999999999996" transform="translate(1, 1)"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="black" stroke-width="3" zIndex="1" isShadow="true" stroke-opacity="0.09999999999999999" transform="translate(1, 1)"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="black" stroke-width="1" zIndex="1" isShadow="true" stroke-opacity="0.15" transform="translate(1, 1)"/><path fill="none" d="M 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64" stroke="#72CB68" stroke-width="2" zIndex="1"/></g><g class="highcharts-markers" visibility="visible" zIndex="0.1" transform="translate(30,40)" clip-path="none"><path fill="#FFFFFF" d="M 838.25 62 C 840.914 62 840.914 66 838.25 66 C 835.586 66 835.586 62 838.25 62 Z" stroke="#72CB68" stroke-width="2"/><path fill="#FFFFFF" d="M 598.75 62 C 601.414 62 601.414 66 598.75 66 C 596.086 66 596.086 62 598.75 62 Z" stroke="#72CB68" stroke-width="2"/><path fill="#FFFFFF" d="M 359.25 62 C 361.914 62 361.914 66 359.25 66 C 356.586 66 356.586 62 359.25 62 Z" stroke="#72CB68" stroke-width="2"/><path fill="#FFFFFF" d="M 119.75 62 C 122.414 62 122.414 66 119.75 66 C 117.086 66 117.086 62 119.75 62 Z" stroke="#72CB68" stroke-width="2"/></g></g><text x="499" y="25" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:12px;color:#3E576F;font-weight:bold;fill:#3E576F;" text-anchor="middle" class="highcharts-title" zIndex="4"><tspan x="499">ACU &amp; PCU</tspan></text><g class="highcharts-axis-labels" zIndex="7"><text x="149.75" y="183" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:11px;width:220px;color:#444;line-height:14px;fill:#444;" text-anchor="middle"><tspan x="149.75">07/12</tspan></text><text x="389.25" y="183" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:11px;width:220px;color:#444;line-height:14px;fill:#444;" text-anchor="middle"><tspan x="389.25">07/13</tspan></text><text x="628.75" y="183" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:11px;width:220px;color:#444;line-height:14px;fill:#444;" text-anchor="middle"><tspan x="628.75">07/14</tspan></text><text x="868.25" y="183" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:11px;width:220px;color:#444;line-height:14px;fill:#444;" text-anchor="middle"><tspan x="868.25">07/15</tspan></text></g><g class="highcharts-axis-labels" zIndex="7"><text x="22" y="108.78359375" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:11px;width:459px;color:#444;line-height:14px;fill:#444;" text-anchor="end"><tspan x="22">0</tspan></text></g><g class="highcharts-axis-labels" zIndex="7"/><g class="highcharts-tooltip" zIndex="8" style="padding:0;white-space:nowrap;" visibility="hidden"><rect rx="5" ry="5" fill="none" x="0" y="0" width="10" height="10" stroke-width="5" fill-opacity="0.65" isShadow="true" stroke="black" stroke-opacity="0.049999999999999996" transform="translate(1, 1)"/><rect rx="5" ry="5" fill="none" x="0" y="0" width="10" height="10" stroke-width="3" fill-opacity="0.65" isShadow="true" stroke="black" stroke-opacity="0.09999999999999999" transform="translate(1, 1)"/><rect rx="5" ry="5" fill="none" x="0" y="0" width="10" height="10" stroke-width="1" fill-opacity="0.65" isShadow="true" stroke="black" stroke-opacity="0.15" transform="translate(1, 1)"/><rect rx="5" ry="5" fill="rgb(0,0,0)" x="0" y="0" width="10" height="10" stroke-width="2" fill-opacity="0.65"/><text x="5" y="18" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Verdana, Arial, Helvetica, sans-serif;font-size:12px;color:#F0F0F0;fill:#F0F0F0;" zIndex="1"/></g><g class="highcharts-tracker" zIndex="9"><g visibility="visible" zIndex="1" transform="translate(30,40)" clip-path="url(#highcharts-23)"><path fill="none" d="M 109.75 64 L 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64 L 848.25 64" isTracker="true" stroke-linejoin="round" visibility="visible" stroke-opacity="0.0001" stroke="rgb(192,192,192)" stroke-width="22" style="cursor:pointer;"/></g><g visibility="visible" zIndex="1" transform="translate(30,40)" clip-path="url(#highcharts-23)"><path fill="none" d="M 109.75 64 L 119.75 64 L 359.25 64 L 598.75 64 L 838.25 64 L 848.25 64" isTracker="true" stroke-linejoin="round" visibility="visible" stroke-opacity="0.0001" stroke="rgb(192,192,192)" stroke-width="22" style="cursor:pointer;"/></g></g></svg></div></div>
				</div>
			</div>
		</div>
	</div>

	



</div></div>
		</div>
	</div>
	<div class="hide" id="talkinggameProductList" style="display: none;" state="complete">
		<div class="product mauto">
			<div class="textbox">
				<div class="after table_product_top">
					
						
						<a href="#createProduct" class="maxConfirmbut l"><font class="add">创建新游戏</font></a> 
						
					
					<!-- <a id="downloadSDK" class="maxCancelbut r"><font class="text">在线文档</font></a>  -->
					
					<a target="_blank" href="../document/zh_cn/index.html#DownloadSDK" class="maxCancelbut r"><font class="dow">下载SDK</font></a>
					
				</div>
				<div id="product-table"><div class="product_title">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_product_title" id="producttitle-table">
		<tbody>
			<tr>
				<td width="16%" style="text-align:right"><strong>总计</strong></td>
				<td width="10%">&nbsp;</td>
				<td width="16%">
					<b id="totalInstallNum">0</b>
					<small>
						<font>今日</font>
						<span id="todayInstallNum">0</span>
					</small>
				</td>
				<td width="16%">
					<b id="totalRegisterNum">0</b>
					<small>
						<font>今日</font>
						<span id="todayRegisterNum">0</span>
					</small>
				</td>
				<td width="16%">
					<b id="totalStartupNum">0</b>
					<small>
						<font>今日</font>
						<span id="todayStartupNum">0</span>
					</small>
				</td>
				<td width="16%">
					<b id="totalChargeSum"><font>￥</font>0</b>
					<small>
						<font>今日</font>
						<span id="todayChargeSum">0</span>
					</small>
				</td>
				<td width="10%" style="overflow:visible">
					<a style="z-index:9;width:13px; margin:0 auto;'" class="Cancel relative minCancel" id="productCenterIndicator">
						<font class="indicator">?</font>
					<div class="norm" style="display: block;">
	<i></i>
	<div class="normTitle">数据指标说明</div>
	<ul>
		
			<li class="after">
				<font>指安装了游戏客户端并开启游戏可连接网络的设备，每台设备只计算一次。</font>
				<span>设备激活</span>
			</li>
		
			<li class="after">
				<font>游戏中玩家的唯一识别ID（游戏帐户）的数量。</font>
				<span>玩家</span>
			</li>
		
			<li class="after">
				<font>玩家进行游戏的总次数，单个玩家帐户从打开游戏至退出游戏的全过程记为一次游戏。</font>
				<span>游戏次数</span>
			</li>
		
			<li class="after">
				<font>玩家为获取虚拟币而支付的货币总额。</font>
				<span>收入</span>
			</li>
		
	</ul>
</div>
</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="table_style_product">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="productlist-table">
		<thead>
			<tr>
				<th width="16%" class="border_r_none">游戏名称</th>
				<th width="10%" class="border_l_none border_r_none"></th>
				<th width="16%" class="border_l_none border_r_none">设备激活</th>
				<th width="16%" class="border_l_none border_r_none">玩家</th>
				<th width="16%" class="border_l_none border_r_none">游戏次数</th>
				<th width="16%" class="border_l_none border_r_none">收入</th>
				<th width="10%" class="border_l_none">报表</th>
				<!-- <th width="10%">操作</th> -->
			</tr>
		</thead>
		<tbody><tr><td class="border_r_none"><a title="串烧三国" class="toViewChart">串烧三国</a><a class="edit"></a></td>
<td class="border_l_none border_r_none">&nbsp;
	
	
</td>
<td class="border_l_none border_r_none"><b>0</b><small><font>今日</font>0</small></td>
<td class="border_l_none border_r_none"><b>0</b><small><font>今日</font>0</small></td>
<td class="border_l_none border_r_none"><b>0</b><small><font>今日</font>0</small></td>
<td class="border_l_none border_r_none"><b><font>￥</font>0</b><small><font>今日</font>0</small></td>
<td class="border_l_none"><a class="toViewChart"><img width="34" height="34" src="images/state.png"></a></td>
<!-- <td><a class="edit">编辑</a><a class="delete">删除</a></td> -->
</tr></tbody>
	</table>
</div></div>
			</div>
		</div>
		<div class="hide" id="productDetailBox">
		</div>
	</div>
</div>
<!--底部悬浮条-->
<div id="inviteTip"><div class="invite_friend">
    <span><a id="inviteButton"></a></span><font><a id="inviteText">邀请朋友使用游戏版，获得永久免费使用权！</a><small style="font-size:12px; margin:0 10px 0 50px">更多推荐方式：</small></font>
		            <!-- JiaThis Button BEGIN -->
<div style="padding-top:6px;  float:left" class="jiathis_style_24x24">
<a class="jiathis_button_tsina" title="分享到新浪微博"><span class="jiathis_txt jtico jtico_tsina"></span></a>
<a class="jiathis_button_weixin" title="分享到微信"><span class="jiathis_txt jtico jtico_weixin"></span></a>
</div>


<!-- JiaThis Button END -->
    <strong><a id="removeInviteBtn"></a></strong>
</div></div>


<div class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="ui-datepicker-div"></div></body></html>
