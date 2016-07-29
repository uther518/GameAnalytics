<?php
	include "header.php";
	
	$sStartDay =  $startDay ? $startDay : $_GET['startDay'];
	$sEndDay = $endDay ? $endDay :  $_GET['endDay'];
	
	$sUid = $_GET['sUid'] ? $_GET['sUid'] : '用户UID';
	$sItemId= $_GET['sItemId'] ? $_GET['sItemId'] : '道具ID';
	
?>

<style>
.search{
	padding-bottom:10px;
	text-align:left;
	
}

.search input{
	height:26px;
	border:1px solid #ccc;
	color:#465160;
}

.pages a{
	padding-right:10px;
}
</style>
<script>
$(function () {
	$( "#search input[name=sUid]" ).focus(function(){
		//alert( this.value );
		this.value = "";
	})
	
	$( "#search input[name=sItemId]" ).focus(function(){
		//alert( this.value );
		this.value = "";
	})
	

	$( "#search input[type=text]" ).blur(function(){
		//alert( this.value );
		if( this.value == "" )
		{
			if( this.name == 'sUid')
			{
				this.value = "用户UID";
			}
			else if( this.name == 'sItemId' )
			{
				this.value = "道具ID";
			}
		}
	})
});
</script>


		<div id="newPlayerCode">
			<div role="title" class="title">
				<strong class="l">道具查询</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				
				<div class="search" id="search" >
					<form method="get" action="" >
						<input type="hidden" name="f" value="<?php echo $_GET['f'];?>" >
						<input type="text" name="sUid"  value="<?php echo $sUid;?>" >
						<input type="text" name="sItemId" value="<?php echo $sItemId; ?>" >
						<input type="text" name="startDay"  value="<?php echo $sStartDay;?>" onclick="new Calendar().show(this);" >
						<input type="text" name="endDay" value="<?php echo $sEndDay;?>"  onclick="new Calendar().show(this);"  >
						
						<input type="submit" name="submit" value="提交查询" >
						<span style="padding:10px 50px;">查询结果:共<?php echo $recordCount;?>笔记录</span>
					</form>
				</div>
				
				<div id="newPlayerCode-table" >
					<div class="picbox" style="height:860px" > 
					<div class="dataTables_wrapper">
					
					<!--分页start-->
					<div class="pages" style="padding:5px 10px;">	
						<a href="admin.php?f=<?php echo $_GET['f'];?>&sUid=<?php echo $_GET['sUid']?>&sItemId=<?php echo $_GET['sItemId']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=1" >1</a>
						<?php 
							for( $i = 2 ; $i < $pages ; $i++ )
							{
								if( $i < $page - 5 || $i > $page + 5 )
								{
									continue;
								}
								
						?>
						<a href="admin.php?f=<?php echo $_GET['f'];?>&sUid=<?php echo $_GET['sUid']?>&sItemId=<?php echo $_GET['sItemId']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=<?php echo $i;?>" ><?php echo $i;?></a>
						<?php 
							}
						?>
						<a href="admin.php?f=<?php echo $_GET['f'];?>&sUid=<?php echo $_GET['sUid']?>&sItemId=<?php echo $_GET['sItemId']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=<?php echo $pages;?>" ><?php echo $pages;?></a>
						
						
					</div>
					<!--分页END-->
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr style="height:30px">
							<th  rowspan="1" colspan="1" style="width:200px;">时间</th>
							<th  rowspan="1" colspan="1">用户昵称</th>
							<th  rowspan="1" colspan="1">UID</th>
							<th  rowspan="1" colspan="1">道具ID</th>
							<th  rowspan="1" colspan="1">道具名称</th>
							<th  rowspan="1" colspan="1">道具数量</th>
							<th  rowspan="1" colspan="1">获得原因</th>
						
						</tr>
					</thead>
	
					<tbody>
						<?php 
							foreach ( $showData['records'] as $info )
							{
						?>
						<tr>
							<td><?php echo date("Y-m-d H:i:s" , $info['serverTime'] );?></td>
							<td><?php echo $info['nickName'];?></td>
							<td><?php echo $info['uid'];?></td>
							<td><?php echo $info['itemId'];?></td>
							<td><?php echo $info['itemName'];?></td>
							<td><?php echo $info['itemNum'];?></td>
							<td><?php echo $info['evtDesc'];?></td>
							
						</tr>
			
					<?php 
							}
					?>
					
			
			</tbody>
			</table>
				
			</div>
		</div>
	</div>

	</div>
	
	
		
</div>
</body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<script src="js/searchya.js" id="searchyampvep" type="application/x-javascript"></script></html>