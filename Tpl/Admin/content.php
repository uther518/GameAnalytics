<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="#">
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

	function show_server_menu()
	{
		var appId = <?php echo $_GET['appId'];?>;
		 $( "#server_menu_li_"+appId ).show();
		 $( "#server_menu div" ).not( "#server_menu_li_"+appId ).hide();
		 $( "#server_menu" ).slideToggle( 500 );
	}

	$(document).ready(function(){ 
		
		var winH = $(window).height();
		$("#frameContent").css( "height" , winH );
		
		$( "#app_menu_li a" ).click(function(){
			 var appId = $(this).attr( "data" );
			 var appName = $(this).html();
			 $( "#server_menu div" ).not( "#server_menu_li_"+appId ).hide( 500 );
			 $( "#server_menu_li_"+appId ).show( 500 );
			// $( "#server_menu" ).show( 500 );
			 $( "#app_h4_name" ).html( appName );
			 
			 $( "#app_menu" ).slideToggle( 500 );
			 $( "#server_menu" ).show( 500 );
			// alert( aval );
		});

	});
	
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
	<!--[if lt IE 9]>
		<script src="js/libs/excanvas.compiled.js" type="text/javascript"></script>
	<![endif]-->


	<script type="text/javascript" src="js/date-utils.js"></script>

</script><style type="text/css" charset="utf-8">/* See license.txt for terms of usage */
</script>

</head>


<body>

<style type="text/css">.WPA3-CONFIRM { z-index:2147483647; width:285px; height:141px; margin:0; background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAR0AAACNCAMAAAC9pV6+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjU5QUIyQzVCNUIwQTExRTJCM0FFRDNCMTc1RTI3Nzg4IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjU5QUIyQzVDNUIwQTExRTJCM0FFRDNCMTc1RTI3Nzg4Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NTlBQjJDNTk1QjBBMTFFMkIzQUVEM0IxNzVFMjc3ODgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NTlBQjJDNUE1QjBBMTFFMkIzQUVEM0IxNzVFMjc3ODgiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6QoyAtAAADAFBMVEW5xdCkvtNjJhzf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) no-repeat;}.WPA3-CONFIRM { *background-image:url(http://combo.b.qq.com/crm/wpa/src/wpa/views/panel.png);}.WPA3-CONFIRM * { position:static; z-index:auto; top:auto; left:auto; right:auto; bottom:auto; width:auto; height:auto; max-height:auto; max-width:auto; min-height:0; min-width:0; margin:0; padding:0; border:0; clear:none; clip:auto; background:transparent; color:#333; cursor:auto; direction:ltr; filter:; float:none; font:normal normal normal 12px "Helvetica Neue", Arial, sans-serif; line-height:16px; letter-spacing:normal; list-style:none; marks:none; overflow:visible; page:auto; quotes:none; -o-set-link-source:none; size:auto; text-align:left; text-decoration:none; text-indent:0; text-overflow:clip; text-shadow:none; text-transform:none; vertical-align:baseline; visibility:visible; white-space:normal; word-spacing:normal; word-wrap:normal; -webkit-box-shadow:none; -moz-box-shadow:none; -ms-box-shadow:none; -o-box-shadow:none; box-shadow:none; -webkit-border-radius:0; -moz-border-radius:0; -ms-border-radius:0; -o-border-radius:0; border-radius:0; -webkit-opacity:1; -moz-opacity:1; -ms-opacity:1; -o-opacity:1; opacity:1; -webkit-outline:0; -moz-outline:0; -ms-outline:0; -o-outline:0; outline:0; -webkit-text-size-adjust:none;}.WPA3-CONFIRM * { font-family:Microsoft YaHei,Simsun;}.WPA3-CONFIRM .WPA3-CONFIRM-TITLE { height:40px; margin:0; padding:0; line-height:40px; color:#2b6089; font-weight:normal; font-size:14px; text-indent:80px;}.WPA3-CONFIRM .WPA3-CONFIRM-CONTENT { height:55px; margin:0; line-height:55px; color:#353535; font-size:14px; text-indent:29px;}.WPA3-CONFIRM .WPA3-CONFIRM-PANEL { height:30px; margin:0; padding-right:16px; text-align:right;}.WPA3-CONFIRM .WPA3-CONFIRM-BUTTON { position:relative; display:inline-block!important; display:inline; zoom:1; width:99px; height:30px; margin-left:10px; line-height:30px; color:#000; text-decoration:none; font-size:12px; text-align:center;}.WPA3-CONFIRM .WPA3-CONFIRM-BUTTON-FOCUS { width:78px;}.WPA3-CONFIRM .WPA3-CONFIRM-BUTTON .WPA3-CONFIRM-BUTTON-TEXT { line-height:30px; text-align:center; cursor:pointer;}.WPA3-CONFIRM-CLOSE { position:absolute; top:7px; right:7px; width:10px; height:10px; cursor:pointer;}</style>
<!-- top头 -->
<div class="top" id="topBanner" style="z-index:99999;">
    <div class="header">
    	<div class="logo l">
           <a href="?f=index"><img src="images/logo.png" alt="Talking Game" title="Talking Game"></a>
        </div>
   
        <div class="nav r">
        	
            	<font class="user">Hi，<a href="#developerInfo"><?php echo $_SESSION['adminInfo']['loginName'];?></a></font>
            	<font><a href="?f=logout" id="logout" class="logout"><?php Lang( 'logout');?></a></font>
			
        </div>
    </div>
    <div class="toptitle"><div class="l">
	<strong><a href="?f=index"><?php Lang( 'data_center');?></a> </strong>
    <span></span>
	<div style="height: 20px; float: left;" id="dropdown_productList" class="selectlist margins">
    <div style="z-index: 999;" class="relative " id="productList__jQSelect0" tabindex="0">
 
 	<!-- 应用列表 -->
 	<div class="timetxt panone"  id="app_menu"  style="width: 105px;left: 0px; display:none;background-color:#E4F9FF;"  >
		<div id="app_menu_li" class="timetop" style="height:auto;" > 
			<?php 
				
				foreach ( $_SESSION['appList'] as $key => $sess )
				{
					if( $sess['appId'] == $_GET['appId'] )
					{
						$currAppName = $sess['appName'];
												
					}
					
			?>
			<p><a data="<?php echo $sess['appId'];?>"><?php echo $sess['appName'];?></a></p>
			<?php 
				}
			?>
			
		</div>
	</div>
	<div class="dropselect" onclick="show_div('app_menu')"  >
		<h4 id="app_h4_name" >
			<?php echo $currAppName;?>
		
		</h4>
	</div>
	<ul style="width: 106px;" id="dropselistbox"></ul></div></div> 
	<span></span>
	
	
	
	<!-- 服务器列表 -->
	
	<div style="height: 18px; float: left;" id="dropdown_platformList" class="selectlist margins">
	<div style="z-index: 999;" class="relative" id="platformList__jQSelect0" tabindex="0">
		
		<div class="timetxt panone"  id="server_menu"  style="width: 105px;left:0px;display:none;"  >
			
			<?php 
				//显示服务器列表
				//print_r( $_SESSION );
				foreach ( $_SESSION['appList'] as $key => $sess )
				{
			?>
			<div id="server_menu_li_<?php echo $sess['appId'];?>" class="timetop " style="height:auto;display:none;" > 
				<?php 
					foreach ( $sess['servers'] as $sserver )
					{
						if( $sess['appId'] == $_GET['appId'] && $sserver['sid'] == $_GET['sid'] )
						{
							$currSidName = $sserver['serverName'];
						}
						
						
						if( $sserver['url'] )
						{
							$jumpUrl = $sserver['url'];
						}
						else 
						{
							$jumpUrl = "?f=content&appId={$sess['appId']}&sid={$sserver['sid']}";
						}
						echo "<p><a href={$jumpUrl} >{$sserver['serverName']}</a></p>";	
					}
				?>
			</div>
			<?php 
				}
			?>	
		</div>
		
	
	<div class="dropselect" onclick="show_server_menu()" >
		<h4 title=""><?php echo $currSidName;?></h4>
		</div>
			<ul style="width:48px; display: none;" id="dropselistbox">
			</ul>
		</div>
	</div>
</div>


<div class="r user_dropselect">
	<?php 
			if( @in_array( 103 , $_SESSION['adminInfo']['writable'] ) )
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
	<div style="display: none;" id="talkinggameProductList" class="hide">
		<div class="product mauto">
			<div class="textbox">
				<div class="after table_product_top">
					
						
						<a class="maxConfirmbut l" href="#createProduct"><font class="add"><?php Lang( 'create_app');?></font></a> 
						
					
					<!-- <a id="downloadSDK" class="maxCancelbut r"><font class="text">在线文档</font></a>  -->
					
					<a class="maxCancelbut r" href="http://www.talkinggame.com/document/zh_cn/index.html#DownloadSDK" target="_blank"><font class="dow">下载SDK</font></a>
					
				</div>
				<div id="product-table">
          
				</div>
			</div>
		</div>
		<div id="productDetailBox" class="hide">
		</div>
        
	</div>
</div>

<div id="frameContent" style="width:100%;margin-top:103px;">
<iframe src="?f=left" style="float:left; width:18%; height:100%"></iframe>   
<iframe src="?f=right" name="right" style="float:right; width:82%; height:100%"></iframe>
</div>




<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div></body></html>