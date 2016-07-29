<?php
	include "header.php";
	
	$sStartDay =  $startDay ? $startDay : $_GET['startDay'];
	$sEndDay = $endDay ? $endDay :  $_GET['endDay'];
	
	$sUid = $_GET['sUid'] ? $_GET['sUid'] : '用户UID';
	$sPayType= $_GET['sPayType'] ? $_GET['sPayType'] : '支付类型';
	
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
	
	$( "#search input[name=sPayType]" ).focus(function(){
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
			else if( this.name == 'sPayType' )
			{
				this.value = "支付类型";
			}
		}
	})
});
</script>


		<div id="newPlayerCode">
			<div role="title" class="title">
				<strong class="l">新增玩家数</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				
				<div class="search" id="search" >
					<form method="get" action="" >
						<input type="hidden" name="f" value="rechargeRecord" >
						<input type="text" name="sUid"  value="<?php echo $sUid;?>" >
						<input type="text" name="sPayType" value="<?php echo $sPayType; ?>" >
						<input type="text" name="startDay"  value="<?php echo $sStartDay;?>" onclick="new Calendar().show(this);" >
						<input type="text" name="endDay" value="<?php echo $sEndDay;?>"  onclick="new Calendar().show(this);"  >
						
						<input type="submit" name="submit" value="提交查询" >
						<span style="padding:10px 50px;">查询结果:共<?php echo $recordCount;?>笔订单，总金额为<?php echo $showData['money'].$_SESSION['serverInfo']['currencyUnit'];?>，用户数<?php echo $showData['uids'];?>人</span>
					</form>
				</div>
				
				<div id="newPlayerCode-table" >
					<div class="picbox" style="height:860px" > 
					<div class="dataTables_wrapper">
					
					<!--分页start-->
					<div class="pages" style="padding:5px 10px;">	
						<a href="admin.php?f=rechargeRecord&sUid=<?php echo $_GET['sUid']?>&sPayType=<?php echo $_GET['sPayType']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=1">1</a>
						<?php 
							for( $i = 2 ; $i < $pages ; $i++ )
							{
								if( $i < $page - 5 || $i > $page + 5 )
								{
									continue;
								}
								
						?>
						<a href="admin.php?f=rechargeRecord&sUid=<?php echo $_GET['sUid']?>&sPayType=<?php echo $_GET['sPayType']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=<?php echo $i;?>" ><?php echo $i;?></a>
						<?php 
							}
						?>
						<a href="admin.php?f=rechargeRecord&sUid=<?php echo $_GET['sUid']?>&sPayType=<?php echo $_GET['sPayType']?>&startDay=<?php echo $_GET['startDay']?>&endDay=<?php echo $_GET['endDay']?>&page=<?php echo $pages;?>" ><?php echo $pages;?></a>
						
						
					</div>
					<!--分页END-->
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr style="height:30px">
							<th  rowspan="1" colspan="1" style="width:200px;">订单编号</th>
							<th  rowspan="1" colspan="1">用户昵称</th>
							<th  rowspan="1" colspan="1">UID</th>
							<th  rowspan="1" colspan="1">金额</th>
							<th  rowspan="1" colspan="1">冲值币</th>
							<th  rowspan="1" colspan="1">支付类型</th>
							<th  rowspan="1" colspan="1">时间</th>
							
						</tr>
					</thead>
	
					<tbody>
						<?php 
							foreach ( $records as $info )
							{
						?>
						<tr>
							<td><?php echo $info['orderId'];?></td>
							<td><?php echo $info['nickName'];?></td>
							<td><?php echo $info['uid'];?></td>
							<td><?php echo $info['rmb']*$_SESSION['serverInfo']['rmbRate'];?></td>
							<td><?php echo $info['coin'];?></td>
							<td><?php echo $info['payType'];?></td>
							<td><?php echo date("Y-m-d H:i:s" , $info['serverTime'] );?></td>
							
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