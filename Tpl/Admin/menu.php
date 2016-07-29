 <body>
		<div id="header">
			<div class="inner-container clearfix">
				<h1 id="logo">
					
					<a class="home" href="#"  title="Go to admin's homepage">
						My Hero Admin	<!-- your title -->
						<span class="ir"></span>
					</a><br />
								
					<a class="button"  target="_blank"  href="http://sg.ecngame.com">游戏官网&nbsp;»</a>
					
				</h1>
				<div id="userbox">
					<div class="inner">
						<strong><?php echo $_SESSION['admin']['loginName'];?></strong>
						<ul class="clearfix">
							<li><a href="#">profile</a></li>
							<li><a href="#">settings</a></li>
						</ul>
					</div>
					<a id="logout"  href="?f=logout<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=logout" : '' ) ?>">log out<span class="ir"></span></a>
				</div><!-- #userbox -->
			</div><!-- .inner-container -->
		</div><!-- #header -->
      	<div id="nav">
			<div class="inner-container clearfix">
				<div id="h-wrap">
					<div class="inner">
						<h2>
							<?php 
								if( $this->adminType != 1  )
								{
							?>
							<span class="h-ico ico-dashboard"><span>控制面板</span></span>
							<span class="h-arrow"></span>
							<?php  } ?>
						</h2>
						<ul class="clearfix">
							<!-- Admin sections - feel free to add/modify your own icons are located in "css/img/h-ico/*" -->

<li><a class="h-ico ico-edit" href="?f=user<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>用户信息</span></a></li>
<li><a class="h-ico ico-media" href="?f=friend<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>好友管理</span></a></li>
<li><a class="h-ico ico-order" href="?f=order<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>订单管理</span></a></li>
<li><a class="h-ico ico-edit" href="?f=statsMenu<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>数据统计</span></a></li>
<li><a class="h-ico ico-comments" href="?mod=Config&act=showExcelList"><span>配置管理</span></a></li>
<li><a class="h-ico ico-edit" href="?f=unitTest<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>测试工具</span></a></li>
<li><a class="h-ico ico-cash" href="?f=tools<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>运维工具</span></a></li>
<li><a class="h-ico ico-users" href="?f=admin<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=get" : '' ) ?>"><span>管理员</span></a></li>
<li><a class="h-ico ico-advanced" href="?f=logout<?php echo ( $_GET['input_uid'] ? "&input_uid={$_GET['input_uid']}&do=logout" : '' ) ?>"><span>退出</span></a></li>	

						</ul>
					</div>
				</div><!-- #h-wrap -->

				<form action="" method="get"><!-- Search form -->
					<fieldset>
						<?php 
							$searchValue = $_GET["input_uid"] > 0 ? $_GET["input_uid"] : "search&hellip;";
						?>
						<label class="a-hidden" for="search-q">Search query:</label>
						<input id="search-q" class="text fl" type="text" name="input_uid" size="20" value="<?php echo $searchValue;?>" />
						<input class="hand fr" type="image" src="css/img/search-button.png" alt="Search" />
					</fieldset>
				</form>
	
			</div><!-- .inner-container -->
      	</div><!-- #nav -->
