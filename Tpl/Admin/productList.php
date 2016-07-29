<head>
	<meta content="IE=8" http-equiv="X-UA-Compatible" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
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
</head>


<body><script charset="utf-8" src="http://tajs.qq.com/jiathis.php?uid=1626433&amp;dm=www.talkinggame.com"></script><link type="text/css" rel="stylesheet" href="http://v3.jiathis.com/code/css/jiathis_share.css"><iframe style="position: fixed; display: none; opacity: 0;" frameborder="0"></iframe><div style="position: absolute; z-index: 1000000000; display: none; top: 50%; left: 50%; overflow: auto;" class="jiathis_style"></div><div style="position: absolute; z-index: 1000000000; display: none; overflow: auto;" class="jiathis_style"></div><iframe src="http://v3.jiathis.com/code/jiathis_utility.html" style="display: none;" frameborder="0"></iframe>
<!-- top头 -->
<div class="top mainMinwidth" id="topBanner" style="z-index:99999;">
    <div class="header">
        <div class="logo l">
            <a href="/index/index.jsp"><img src="images/logo.png" alt="Talking Game" title="Talking Game"></a>
        </div>
        <div class="nav r">
        	
            	<font class="user">Hi，<a href="#developerInfo"><?php echo $_SESSION['adminInfo']['loginName'];?></a></font>
            	<font><a href="?f=logout" id="logout" class="logout"><?php Lang( 'logout');?></a></font>
			
        </div>
    </div>
    <div class="toptitle"><div class="l">
	<strong><a href="#productList"><?php Lang( 'data_center');?></a> </strong>
    <span></span>
	<strong><?php Lang( 'all_app');?></strong>
</div>


<div class="r user_dropselect">
	<a href="?f=accountCenter"><h4><font><?php Lang( 'account_permission');?></font></h4></a>
</div>
</div>
</div>

<div style="min-height: 417px;" id="talkinggameData">
	<div style="display: none;" id="talkinggameCreateProduct" class="hide"><div class="addApp mauto">
	<div class="max_title">
		<div class="add_step l add_step1">
			<span class="l Conduct" id="step01"><small></small></span>
			<p class="l">
				<strong>建立游戏</strong>
				<font>创建需要分析的游戏</font>
			</p>
		</div>
		<i class="add_app_arrow l" id="arrow01"></i>
		<div class="add_step l add_step2">
			<span class="l" id="step02"><small></small></span>
			<p class="l">
				<strong>进行集成</strong>
				<font>下载SDK包和集成指南<br>使用获取的App ID进行集成</font>
			</p>
		</div>
		<i class="add_app_arrow l" id="arrow02"></i>
		<div class="add_step l add_step3">
			<span class="l" id="step03"><small></small></span>
			<p class="l">
				<strong>进行调试</strong>
				<font>检查调用正确性<br>成功向平台发送数据</font>
			</p>
		</div>
	</div>

	<div id="stepPanels">
		<dl id="stepPanel01" class="Edit_box after add_step_con step_con1">
			<dt class="l">
				<input id="productNameInput" class="txtinput" value="请填写游戏名称" type="text">
				<!-- <div class="selectDIV maxSelectDIV">
					<h4>游戏类型</h4>
					<ol>
						<li val="-1">游戏类型</li>
						<li val="1">休闲游戏</li>
		    	  		<li val="2">智力游戏</li>
		    	  		<li val="3">角色扮演</li>
		    	  		<li val="4">体育游戏</li>
		    	  		<li val="5">桌面游戏</li>
		    	  		<li val="6">动作游戏</li>
		    	  		<li val="7">策略游戏</li>
		    	  		<li val="8">模拟类</li>
		    	  		<li val="9">探险游戏</li>
		    	  		<li val="10">扑克牌</li>
		    	  		<li val="11">股子游戏</li>
		    	  		<li val="12">教育游戏</li>
		    	  		<li val="13">家庭游戏</li>
		    	  		<li val="14">音乐游戏</li>
		    	  		<li val="15">赛车游戏</li>
						<li val="16">小游戏</li>
						<li val="17">文字游戏</li>
					</ol>
				</div> -->
				<div class="add_step_con_select">
					<div style="height: 41px; float: left;" id="dropdown_selectGameType" class="selectlist margins"><div style="z-index: auto;" class="relative" id="selectGameType__jQSelect0" tabindex="0"><select style="display: none;" id="selectGameType">
						<option value="-1">游戏类型</option>
						<option value="18">卡牌游戏</option>
						<option value="1">休闲游戏</option>
		    	  		<option value="2">智力游戏</option>
		    	  		<option value="3">角色扮演</option>
		    	  		<option value="4">体育游戏</option>
		    	  		<option value="5">桌面游戏</option>
		    	  		<option value="6">动作游戏</option>
		    	  		<option value="7">策略游戏</option>
		    	  		<option value="8">模拟类</option>
		    	  		<option value="9">探险游戏</option>
		    	  		<option value="10">扑克牌</option>
		    	  		<option value="11">骰子游戏</option>
		    	  		<option value="12">教育游戏</option>
		    	  		<option value="13">家庭游戏</option>
		    	  		<option value="14">音乐游戏</option>
		    	  		<option value="15">赛车游戏</option>
						<option value="16">小游戏</option>
						<option value="17">文字游戏</option>
						
					</select><div class="dropselect"><h4 class="over" title="游戏类型">游戏类型</h4></div><ul style="width: 377px; display: none; top: 43px;" id="dropselistbox"></ul></div></div>
				</div>
				<a id="createProductBtn" class="maxConfirm">创建游戏</a>
			</dt>
			<dd class="l">
				<div class="tips">
					<strong>贴士</strong>
					<p>认真选择游戏的类型，有利于后续查看数据报表时行业基准的准确性。</p>
					<p>跨手机平台的游戏只需建立一次，我们同时支持查看单一平台和聚合全平台的数据。</p>
				</div>
			</dd>
		</dl>

		<dl id="stepPanel02" class="Edit_box after add_step_con step_con2 hide">
			<dt class="l">
				<p>
					<span data="productName"></span>
					<font data="productType"></font>
				</p>
				<label data="appId" class="disabled"></label>
				<!-- <input type="text" class="txtinput" value="请输入您的APP ID"> -->
				<p class="dowbut">
					<a class="txta" href="/download/Game_Analytics_SDK_Android_iOS_2.0.4.zip">-下载iOS和Android SDK</a>
					<a class="txta" href="/download/Game_Analytics_SDK_Unity3d_2.0.4.zip">-下载Unity 3D专用SDK</a>
					<a class="txta" href="/download/Game_Analytics_SDK_Cocos2dx_2.0.4.zip">-下载Cocos2D-x专用SDK</a>
					<a class="txta" href="/download/Game_Analytics_SDK_Ane_2.0.4.zip">-下载Flash Air专用SDK</a>
				</p>
				<!-- <a class="maxCancel">下载Unity 3D专用SDK和开发指南</a> -->
				<a class="maxConfirm" id="step2finishBtn">完 成</a>
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


		<dl id="stepPanel03" class="Edit_box after add_step_con step_con3 hide">
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
				<a href="#productList" class="maxConfirm">进入数据中心</a>
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
	</div>
</div>
</div>
	<div style="display: none;" id="talkinggameAccountCenter" class="hide"></div>
	<div style="display: none;" id="talkinggameCreateAccount" class="hide"></div>
	<div style="display: none;" id="talkinggameDeveloperInfo" class="hide"></div>
	<div style="display: none;" id="talkinggamePages" class="hide">
		<div style="min-height: 214px;" class="main" id="minweb">
		    <!-- 左侧菜单 -->
		    <div id="menu-banner"><div class="menu l">
	<ul>
		
			<li>
				<a class="navigate Dashboard" url-data="summary-index">
					<span>游戏概况</span>
				</a>
				
			</li>
		
			<li>
				<a class="more_icon Players" url-data="">
					<span>游戏玩家</span>
				</a>
				
					<ol>
					
						<li><a url-data="player-new">新增</a></li>
					
						<li><a url-data="player-active">活跃</a></li>
					
						<li><a url-data="player-retention">留存</a></li>
					
						<li><a url-data="player-convertion">转化</a></li>
					
						<li><a url-data="player-churned">流失</a></li>
					
						<li><a url-data="player-behavior">游戏习惯</a></li>
					
						<li><a url-data="player-device">设备</a></li>
					
					</ol>
				
			</li>
		
			<li>
				<a class="concurrentUser" url-data="concurrent">
					<span>在线分析</span>
				</a>
				
			</li>
		
			<li>
				<a class="LevelAnalysis more_icon" url-data="">
					<span>等级分析</span>
				</a>
				
					<ol>
					
						<li><a url-data="level-detail">等级详解</a></li>
					
						<li><a url-data="level-distribute">等级分布</a></li>
					
						<li><a url-data="level-schedule">新玩家进度</a></li>
					
					</ol>
				
			</li>
		
			<li>
				<a class="TasksCheckpoints more_icon" url-data="">
					<span>任务分析</span>
				</a>
				
					<ol>
					
						<li><a url-data="tasks">任务和关卡</a></li>
					
						<li><a url-data="TasksManagement">任务和关卡管理</a></li>
					
					</ol>
				
			</li>
		
			<li>
				<a class="more_icon revenue" url-data="">
					<span>收入分析</span>
				</a>
				
					<ol>
					
						<li><a url-data="revenue-incomeData">收入数据</a></li>
					
						<li><a url-data="revenue-payPermeate">付费渗透</a></li>
					
						<li><a url-data="revenue-newPlayerValue">新玩家价值</a></li>
					
						<li><a url-data="revenue-payHabit">付费习惯</a></li>
					
					</ol>
				
			</li>
		
			<li>
				<a class="more_icon virtualEconomy" url-data="">
					<span>虚拟消费</span>
				</a>
				
					<ol>
					
						<li><a url-data="virtualEconomy-virtualCoin">虚拟币</a></li>
					
						<li><a url-data="virtualEconomy-consumeAnalysis">消费喜好</a></li>
					
						<li><a url-data="virtualEconomy-consumePoint">消费点</a></li>
					
						<li><a url-data="virtualEconomy-Management">消费点管理</a></li>
					
					</ol>
				
			</li>
		
			<li>
				<a class="WhalesUser" url-data="whale-whaleUser">
					<span>鲸鱼用户</span>
				</a>
				
			</li>
		
			<li>
				<a class="more_icon partnerExtention" url-data="">
					<span>推广渠道</span>
				</a>
				
					<ol>
					
						<li><a url-data="partnerExtention-partnerData">渠道数据</a></li>
					
						<li><a url-data="partnerExtention-partnerManagement">渠道管理</a></li>
					
					</ol>
				
			</li>
		
			<li>
				<a class="more_icon CustomEvent" url-data="">
					<span>自定义事件</span>
				</a>
				
					<ol>
					
						<li><a url-data="customEvent-data">事件数据</a></li>
					
						<li><a url-data="customEvent-eventManagement">事件管理</a></li>
					
					</ol>
				
			</li>
		
	</ul>
</div></div>
		    <div id="container">
		    </div>
		</div>
	</div>
	<div state="complete" style="display: block;" id="talkinggameProductList" class="hide">
		<div class="product mauto">
			<div class="textbox">
				<div class="after table_product_top">
						<a class="maxConfirmbut l" href="?f=createProduct&step=1"><font class="add"><?php Lang( 'create_app');?></font></a> 
					
				</div>
				<div id="product-table"><div class="product_title">
	<table id="producttitle-table" class="table_product_title"  cellpadding="0" cellspacing="0" border="0" width="100%">
		<tbody>
			<tr>
				<td style="text-align:center" width="16%"><strong>总计</strong></td>
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
				<td width="10%" style="overflow:visible" >
					<a style="z-index:9;width:13px; margin:0 auto;'" class="Cancel relative minCancel" id="productCenterIndicator" onClick="show()">
						<font class="indicator" >?</font>
					<div id='test' class="norm" style="display:none;">
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
	<table id="productlist-table" cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th class="border_r_none" width="16%"  style="text-align:center"  >游戏名称</th>
				<th class="border_l_none border_r_none" width="10%"></th>
				<th class="border_l_none border_r_none" width="16%">设备激活</th>
				<th class="border_l_none border_r_none" width="16%">玩家</th>
				<th class="border_l_none border_r_none" width="16%">游戏次数</th>
				<th class="border_l_none border_r_none" width="16%">收入</th>
				<th class="border_l_none" width="10%">报表</th>
				<!-- <th width="10%">操作</th> -->
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach ( $appList as $info )
			{
			
				//print_r( $_SESSION );
		?>
        <tr>
        <td class="border_r_none"  style="text-align:center"  >
        	<a class="toViewChart" title="串烧三国" href="?f=content&appId=<?php echo $info['appId'];?>&sid=<?php echo $info['servers'][0]['sid'];?>"><?php echo $info['appName'];?></a><a class="edit"></a>
        </td>
		<td class="border_l_none border_r_none">&nbsp;</td>
		<td class="border_l_none border_r_none"><b>0</b><small><font>今日</font>0</small></td>
		<td class="border_l_none border_r_none"><b>0</b><small><font>今日</font>0</small></td>
		<td class="border_l_none border_r_none"><b>0</b><small><font>今日</font>0</small></td>
		<td class="border_l_none border_r_none"><b><font>￥</font>0</b><small><font>今日</font>0</small></td>
		<td class="border_l_none"><a class="toViewChart" href="?f=content&appId=<?php echo $info['appId'];?>"><img src="images/state.png" height="34" width="34"></a></td>
		<!-- <td><a class="edit">编辑</a><a class="delete">删除</a></td> -->
		</tr>
		<?php }?>
</tbody>
	</table>
</div></div>
			</div>
		</div>
		<div id="productDetailBox" class="hide">
		</div>
	</div>
</div>
<!--底部悬浮条-->
<div id="inviteTip"></div>
<!--底部悬浮条结束-->



