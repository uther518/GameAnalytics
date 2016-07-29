<?php
	include "header.php";
	
	$sStartDay =  $startDay ? $startDay : $_GET['startDay'];
	$sEndDay = $endDay ? $endDay :  $_GET['endDay'];
	
	$sUid = $_GET['sUid'] ? $_GET['sUid'] : '用户UID';
	$sAct= $_GET['sAct'] ? $_GET['sAct'] : '事件名称';
	
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
	
	$( "#search input[name=sAct]" ).focus(function(){
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
			else if( this.name == 'sAct' )
			{
				this.value = "事件名称";
			}
		}
	})
});
</script>


		<div id="newPlayerCode">
			<div role="title" class="title">
				<strong class="l">自定义事件查询</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				
				<div class="search" id="search" >
					<form method="get" action="" >
						<input type="hidden" name="f" value="<?php echo $_GET['f'];?>" >
						<input type="hidden" name="act" value="<?php echo $_GET['act'];?>" >
						<input type="text" name="sUid"  value="<?php echo $sUid;?>" >
						<input type="text" name="sAct" value="<?php echo $sAct; ?>" >
						<input type="text" name="startDay"  value="<?php echo $sStartDay;?>" onclick="new Calendar().show(this);" >
						<input type="text" name="endDay" value="<?php echo $sEndDay;?>"  onclick="new Calendar().show(this);"  >
						
						<input type="submit" name="submit" value="提交查询" >
						<span style="padding:10px 50px;">查询结果:共<?php echo $recordCount;?>笔记录,事件总发生次数:<?php echo $showData['evtTotal'];?>次</span>
					</form>
				</div>
				
				<div id="newPlayerCode-table" >
					<div class="picbox" style="height:860px" > 
					<div class="dataTables_wrapper">
					
					<!--分页start-->
					<div class="pages" style="padding:5px 10px;">	
						<a href="admin.php?f=<?php echo $_GET['f'];?>&act=<?php echo $_GET['act'];?>&sUid=<?php echo $_GET['sUid']?>&sAct=<?php echo $_GET['sAct']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=1" >1</a>
						<?php 
							for( $i = 2 ; $i < $pages ; $i++ )
							{
								if( $i < $page - 5 || $i > $page + 5 )
								{
									continue;
								}
								
						?>
						<a href="admin.php?f=<?php echo $_GET['f'];?>&act=<?php echo $_GET['act'];?>&sUid=<?php echo $_GET['sUid']?>&sAct=<?php echo $_GET['sAct']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=<?php echo $i;?>" ><?php echo $i;?></a>
						<?php 
							}
						?>
						<a href="admin.php?f=<?php echo $_GET['f'];?>&act=<?php echo $_GET['act'];?>&sUid=<?php echo $_GET['sUid']?>&sAct=<?php echo $_GET['sAct']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=<?php echo $pages;?>" ><?php echo $pages;?></a>
						
						
					</div>
					<!--分页END-->
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr style="height:30px">
							
							<th  rowspan="1" colspan="1">UID</th>
							<th  rowspan="1" colspan="1">事件名称</th>
							<th  rowspan="1" colspan="1">事件目标</th>
							<th  rowspan="1" colspan="1">事件次数</th>
							<th  rowspan="1" colspan="1" style="width:200px;">事件发生时间</th>
						</tr>
					</thead>
	
					<tbody>
						<?php 
							foreach ( $showData['records'] as $info )
							{
						?>
						<tr>
							<td><?php echo $info['uid'];?></td>
							<td><?php echo $info['act'];?></td>
							<td><?php echo $info['obj'];?></td>
							<td><?php echo $info['num'];?></td>
							<td><?php
							 	if( $info['serverTime'] )
							 	{
							 		echo date("Y-m-d H:i:s" , $info['serverTime'] );
							 	}
							 	else
							 	{
							 		echo $info['date'];
							 	}
							 
							 ?></td>
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