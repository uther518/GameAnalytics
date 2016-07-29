<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
	<title>ecngame</title>
    <script>
	function show()
	{
 		var obj=document.getElementById('test');
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
	

</head>


<body><script charset="utf-8" src="http://tajs.qq.com/jiathis.php?uid=1626433&amp;dm=www.talkinggame.com"></script><link type="text/css" rel="stylesheet" href="http://v3.jiathis.com/code/css/jiathis_share.css"><iframe style="position: fixed; display: none; opacity: 0;" frameborder="0"></iframe><div style="position: absolute; z-index: 1000000000; display: none; top: 50%; left: 50%; overflow: auto;" class="jiathis_style"></div><div style="position: absolute; z-index: 1000000000; display: none; overflow: auto;" class="jiathis_style"></div><iframe src="http://v3.jiathis.com/code/jiathis_utility.html" style="display: none;" frameborder="0"></iframe>
<!-- top头 -->
<?php 
include 'topBanner.php';
?>



<div class="r user_dropselect">
	<?php 
			if( in_array( 103 , $_SESSION['adminInfo']['writable'] ) )
			{
	?>
	<a href="?f=accountCenter"><h4><font><?php Lang( 'account_permission');?></font></h4></a>
	<?php 
			}
	?>
</div>
</div>
</div>


</div>

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
			<a class="maxConfirmbut l" href="?f=createAccount"><font class="add">添加新账户</font></a>
		</div>
		<div class="sub-account_box">  
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style_channel">
				<thead>
					<tr>
						<th width="30%" class="left">帐户</th>
						<th width="20%">角色</th>
						<th width="30%">创建日期</th>
						<th width="20%">操作</th>
					</tr>
				</thead>
				<tbody id="accountList">	
		<?php foreach ( $userList as $user ){?>	
		<tr>
			<td class="left"><a href="?f=createAccount&act=mod&loginName=<?php echo $user['loginName'];?>"><?php echo $user['loginName'];?></a></td>
			<td>
				<?php 
				if( $user['userType'] == 1 )
				{
					echo "管理员";
				}
				elseif( $user['userType'] == 2 )
				{
					echo "下载渠道合作方";
				}
				?>
			</td>
			<td><?php echo date('Y-m-d H:i:s' , $user['regisTime']);?></td>
			<td>
				<a href="?f=createAccount&act=mod&loginName=<?php echo $user['loginName'];?>">修改</a>
				<a href="?f=accountCenter&act=del&loginName=<?php echo $user['loginName'];?>">删除</a>
				
			</td>
		</tr>
		<?php }?>
</tbody>
			</table>
			<!-- <div class="page">
				<div id="grid_length" class="buttonDiv l">
					<b class="l">每页显示</b>
					<div class="selectlist margins" id="dropdown_grid_page" style="height: 17px; float: left;">
						<div tabindex="0" id="grid_page__jQSelect0" class="relative" style="z-index: auto;">
							<select title="pages" class="d" id="grid_page" name="grid_length" size="1" style="display: none;">
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
							<div class="dropselect">
								<div class="selectIcon">
									<font id="grid_page_icon" class="selectt"></font>
									<font id="grid_page_icont" class="selectb"></font>
								</div>
								<h4 title="10">10</h4>
							</div>
							<ul id="dropselistbox" style="width: 64px; display: none;"></ul>
						</div>
					</div>
					<b class="l">条记录</b>
				</div>
				<div class="dataTables_paginate paging_full_numbers" id="grid_paginate">
					<span class="first paginate_button paginate_button_disabled" id="grid_first">第一页</span>
					<span class="previous paginate_button paginate_button_disabled" id="grid_previous"> 上一页 </span>
					<span>
						<span class="paginate_active">1</span>
						<span class="paginate_button">2</span>
						<span class="paginate_button">3</span>
						<span class="paginate_button">4</span>
						<span class="paginate_button">5</span>
					</span>
					<span class="next paginate_button" id="grid_next"> 下一页 </span>
					<span class="last paginate_button" id="grid_last"> 最后一页 </span>
				</div>
			</div> -->
		</div>
	</div>
</div>
		<div id="productDetailBox" class="hide">
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
        <span class="mauto Language"><a href="javascript:changeLocale('zh_cn')">中文</a>|<a href="javascript:changeLocale('en_us')">English</a></span>
	    </span>
</div>



