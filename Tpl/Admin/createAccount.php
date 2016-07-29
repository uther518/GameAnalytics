<head>
	<meta content="IE=8" http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
	<title>ecngame</title>
	<script type="text/javascript" src="js/jquery.min.js"></script>
    	<script>
	function show()
	{
 		var obj=document.getElementById('test');
		obj.style.display=obj.style.display=='block'?'none':'block';	
	}

	$(function () {

		$("#selectAllApp").click(function(){
			$("#selectApp [type=checkbox]").prop("checked", this.checked );
		});		

	
		$("#selectAllMgr").click(function(){
                        $("#selectMgr [type=checkbox]").prop("checked", this.checked );
                });         

		$("#selectAllModule").click(function(){
                        $("#selectModule [type=checkbox]").prop("checked", this.checked );
                });     

	
		$("#commitBtn").click(function(){
				$( "#form1" ).submit();
		});
	});
	</script>
	
<style type="text/css" charset="utf-8">/* See license.txt for terms of usage */

</style></head>


<body>
<!-- top头 -->
<?php 
include 'topBanner.php';
?>


	<div state="complete" style="display: block;" id="talkinggameProductList" class="hide">
		<div class="product mauto">
	<div class="textbox">
        <div id="accountTitle" class="after table_product_top Competence_top">
           添加账户
        </div>
        <form id="form1" action="?f=accountCenter&act=<?php echo $_GET['act'];?>" method="post"  >
		<div class="Editor Competence">
        	<div class="Editor_li">
            	<div style="display:block" class="Editor_li_con">
                	<b>登录信息</b>
                    <div class="Editor_input l">
                    	<span class="placeholderWrap"><label for="email">邮箱地址</label><input type="text"  name="loginName"  value="<?php echo $userData['loginName'];?>"  id="email"></span>
                    	<span class="placeholderWrap"><label for="password">密码(4~16位)</label><input type="password"  name="password"  value="<?php echo $userData['password'];?>" id="password"></span>
                        <label id="tip"></label>
                    </div>
                </div>
            	<div class="Editor_li_con">
                	<b>帐户类型</b>
                    <div id="userGroup" class="Editor_li_con_txt">	                
                    	<p>
                    		<input type="radio"  
                    		 <?php 
                    		 	if( $userData['userType'] == 1 )
                    		 	{
                    		 ?>
                    		 checked="checked" 
                    		<?php 
								}
                    		 ?>
                    		 name="userType" class="radio" value="1"><label>管理员<small>（拥有对全部应用的所有权限）</small></label>
                    	</p>
                    	<p>
                    		<input type="radio" 
                    			 <?php 
                    		 	if( $userData['userType'] == 2 )
                    		 	{
	                    		 ?>
	                    		 checked="checked" 
	                    		<?php 
									}
	                    		 ?>
                    		
                    		 name="userType" class="radio" value="2" ><label>下载渠道合作方</label>
                    	</p>
                    </div>
                </div>
            </div>
            
            
            <div id="operatePanel">
            	
            	
            	<div class="Editor_li after">
                	<div class="Editor_li_con">
                    	<b class="l">应用授权</b>
                    	<span>
                        	<input type="checkbox" value="-1" id="selectAllApp"><label>全选</label>
                        </span>
                        </div>
                        <div>
                    	
                    	<ul id="selectApp" class="Accoun after">
                    	<?php 
                    	
                    		//print_r( $_SESSION );
                    		foreach ( $_SESSION['appList'] as $appInfo )
                    		{
                    	?>
                            <li>
                                <input type="checkbox"  name="appManage[]"  value="<?php echo $appInfo['appId'];?>" 
                                <?php 
                                	if( in_array( $appInfo['appId'] , $userData['appManage'] ) )
									{
										echo " checked='checked'";
									}
                                ?>
                                
                                
                                class="checkbox">
                                <label><?php echo $appInfo['appName'];?></label>
                            </li>
                           <?php 
                    		
                    		}?>
                       </ul>
                       
                      
                       
                    </div>
                </div>
                
                
                <div id="permisstion">
                	<div id="manageDiv" class="Editor_li">
                    	<div class="Editor_li_con">
                        	<b>管理权限</b>
                        	<span>
                        		<input type="checkbox" value="-1" id="selectAllMgr"><label>全选</label>
                        	</span>
                        </div>
                		<ul id="selectMgr" class="Accoun after">
                            <li>
                                <input type="checkbox" name="writable[]" value="101" 
                                 <?php 
                                	if( in_array( 101 , $userData['writable'] ) )
									{
										echo " checked='checked'";
									}
                                ?>
                                class="checkbox">
                                <label>渠道管理</label>
                            </li>
                   
                            <li>
                                <input type="checkbox" name="writable[]" value="102" 
                                 <?php 
                                	if( in_array( 102 , $userData['writable'] ) )
									{
										echo " checked='checked'";
									}
                                ?>
                                class="checkbox">
                                <label>游戏管理<small>（添加/删除游戏）</small></label>
                            </li>
                            
                            <li>
                                <input type="checkbox" name="writable[]"  value="103" 
                                 <?php 
                                	if( in_array( 103 , $userData['writable'] ) )
									{
										echo " checked='checked'";
									}
                                ?>
                                
                                 class="checkbox">
                                <label>管理员管理<small>（添加/删除管理员）</small></label>
                            </li>
                            
                        </ul>
                    </div>
                    <div id="pageDiv" class="Editor_li">
                    	<div class="Editor_li_con">
                        	<b>页面功能</b>
                        	<span>
                        		<input type="checkbox" value="-1" id="selectAllModule"><label>全选</label>
                        	</span>
                        </div>
                        <div id="selectModule" class="Accoun after">
                    		<ul>
                            
                                    <li>
                                        <input type="checkbox"  name="viewList[]" value="1" 
                                        <?php 
		                                	if( in_array( 1 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        class="checkbox">
                                        <label>游戏概况</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="viewList[]" value="2" 
                                        <?php 
		                                	if( in_array( 2 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        
                                        class="checkbox">
                                        <label>游戏玩家</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="viewList[]" value="3"
                                        <?php 
		                                	if( in_array( 3 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        
                                         class="checkbox">
                                        <label>付费分析</label>
                                    </li>
                                    
                                     <li>
                                        <input type="checkbox" name="viewList[]" value="4" 
                                        <?php 
		                                	if( in_array( 4 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        
                                        class="checkbox">
                                        <label>在线分析</label>
                                    </li>
                                    
                                    <li>
                                        <input type="checkbox" name="viewList[]" value="5" 
                                        <?php 
		                                	if( in_array( 5 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        
                                        class="checkbox">
                                        <label>虚拟币分析</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="viewList[]" value="6" 
                                        
                                         <?php 
		                                	if( in_array( 6 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        class="checkbox">
                                        <label>等级分步</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="viewList[]" value="7" 
                                         <?php 
		                                	if( in_array( 7 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        class="checkbox">
                                        <label>新手分析</label>
                                    </li>
                                   
                                    <li>
                                        <input type="checkbox"  name="viewList[]" value="8"  
                                         <?php 
		                                	if( in_array( 8 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                        class="checkbox">
                                        <label>参与度分析</label>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="viewList[]" value="9" 
                                         <?php 
		                                	if( in_array( 9 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                         class="checkbox">
                                        <label>道具记录</label>
                                    </li>
                                    
                                    <li>
                                        <input type="checkbox"  name="viewList[]" value="10" 
                                         <?php 
		                                	if( in_array( 10 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                         class="checkbox">
                                        <label>渠道查看</label>
                                    </li>
                                    
                                     <li>
                                        <input type="checkbox"  name="viewList[]" value="11" 
                                         <?php 
		                                	if( in_array( 11 , $userData['viewList'] ) )
											{
												echo " checked='checked'";
											}
		                                ?>
                                         class="checkbox">
                                        <label>自定义事件</label>
                                    </li>
                            
                            </ul>
                           
                          
                           
                    	</div>
                    </div>
                </div>
            </div>
        	<div class="Editor_li after Editor_li_left_con">
                <a id="commitBtn" class="Confirm l">提交</a><a href="?f=accountCenter" id="cancelBtn" class="Cancel l">取消</a>
            </div>
        </div>
        </form>
	</div>
</div>
		<div id="productDetailBox" class="hide">
		</div>
	</div>
</div>
<!--底部悬浮条-->
<div id="inviteTip"></div>
<!--底部悬浮条结束-->

