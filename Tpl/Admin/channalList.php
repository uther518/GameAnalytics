<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

	<meta content="IE=8" http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="http://www.talkinggame.com/pages/images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
	<title>ecngame</title>
    
	<style>
		.product{
		    position: relative; 
		    padding:10px 0;
		   
		   }
	</style>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/Calendar3.js"></script>
	

</head>


<body>
<div class="content r">
	<div class="boxmax">
	<div state="complete" style="display: block;" id="talkinggameProductList" class="hide">
		<div class="product mauto">
	<!-- <div class="titlemax">
			<a href="javascript:void(0)" class="return">返回数据中心</a>
            <div class="mauto product_butDIV">
            	<a data="amount" class="product_but l butL hover">我的信息</a>
            	<a data="quality" class="product_but l">权限和账户</a>
            	<a data="income" class="product_but l butR">站内信</a>
            </div>
	</div> -->
	<div class="textbox">
		<div class="after table_product_top">
			<a class="maxConfirmbut l" href="?f=channalMgr&act=add"><font class="add">设定渠道管理员</font></a>
		</div>
		<div class="sub-account_box">  
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style_channel">
				<thead>
					<tr>
						<th width="30%" class="left">帐户</th>
						<th width="20%">下载渠道</th>
						<th width="30%">创建日期</th>
						<th width="20%">操作</th>
					</tr>
				</thead>
				<tbody id="accountList">	
		<?php 
			foreach ( $adminList as $user )
			{
				$channals = $user['channals'][$_SESSION['currAppId']];
				if( empty( $channals ) )
				{
					continue;
				}
		?>	
		<tr>
			<td class="left"><a href="?f=channalMgr&act=add"><?php echo $user['loginName'];?></a></td>
			<td>
				<?php 
					//$channals = $user['channals'][$_SESSION['currAppId']];
					echo implode( "/", $channals );
				?>
			</td>
			<td><?php echo date('Y-m-d H:i:s' , $user['regisTime']);?></td>
			<td>
				<a href="?f=channalMgr&act=add">修改</a>
				<a href="?f=channalMgr&act=del&logName=<?php echo $user['loginName'];?>">删除</a>
			</td>
		</tr>
		<?php }?>
		</tbody>
			</table>
			
		</div>
	</div>
</div>
		
	</div>
</div>
<!--底部悬浮条-->
<div id="inviteTip"></div>
<!--底部悬浮条结束-->
<div id="bottom" class="bottom mainMinwidth">
	<span class="copyright">
	        <span class="l"><a href="./index.html" style="margin-left:0px">首页</a>|<a href="#this">用户协议</a></span>
	        <font class="r">上海秀策网络科技有限公司沪ICP备11005983号-1 </font>
        <span class="mauto Language">
        	<a href="?f=SetLang&l=cn">中文</a>|
        	<a href="?f=SetLang&l=en">English</a></span>
	    </span>
</div>



