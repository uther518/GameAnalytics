<head>
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
		
		$("#commitBtn").click(function(){
				$( "#form1" ).submit();
		});
	});
	</script>
	
<style type="text/css" >/* See license.txt for terms of usage */
.form table{
	width:80%;
	font:14px Arial, Helvetica, sans-serif; 
	text-align:left;
}
table tr{
	height:40px;
}

table td,table th{
	padding-left:20px;
}

table td span{
	padding-right:40px;
}

table select , table input { 
	width:auto;
	border: 1px solid #D7DEE3;
	font:12px Arial, Helvetica, sans-serif; 
	padding: 5px 10px;
}    

select option
{
	padding-left:10px;
}



</style>
</head>



<body>
<!-- top头 -->


	<div state="complete" style="display: block;" id="talkinggameProductList" class="hide">
		<div class=" mauto">
	<div class="textbox">
        <div id="accountTitle" class="after table_product_top Competence_top">
                            渠道管理员权限分配
        </div>
       
		<div class="Editor Competence">
        	<div class="Editor_li">

                    	 <form  class="form" id="form1" action="?f=channalMgr&act=save" method="post"  >
                    	<table>
	                    	<tr>
	                    		<th style="width:10%;">选择帐号</th>
	                    		<td style="" >
	         
	                    			<select name="logName" >
		                    			<option>请选择帐号</option>
		                    			<?php 
		                    				foreach ( $adminList as $user )
		                    				{
		                    					echo "<option>{$user['loginName']}</option>";
		                    				}
		                    			?>
		                    		</select>
		                    		
		                    		
		                    		</td>
		                    	</tr>
		                    	
		                    	<tr>
	                    		<th style="width:30%;">选择渠道</th>
	                    		<td >
	                    			<?php 
	                    				foreach ( $refers as $refer )
	                    				{
	                    			?>
	                    			<input type="checkbox"  name="channals[]"  value="<?php echo $refer;?>"  ><span><?php echo $refer;?></span>
	                    			
		                    		<?php 
	                    				}
		                    		?>
		                    	</td>
		                    	</tr>
	                    	</table>
                    	</form>   
                    	
             
            </div>
        	<div class="Editor_li after Editor_li_left_con" style="padding-top:10px">
                <a id="commitBtn" class="Confirm l">提交</a><a href="?f=channalMgr" id="cancelBtn" class="Cancel l">取消</a>
            </div>
        </div>
       
	</div>
</div>
		<div id="productDetailBox" class="hide">
		</div>
	</div>
</div>