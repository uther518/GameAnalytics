<?php
	include "header.php";
	$act = $_GET['act'];
	$chan = "showChan".ucfirst( $act );
	$chanDesc = array(
		'coins' => '游戏币',
		'rmbs' => "金额({$_SESSION['serverInfo']['currencyUnit']})",
		'times' => '次数',
		'uids' => '人数',
	);
	
	$type = "showType".ucfirst( $act );
	$typeDesc = array(
			'coins' => '游戏币',
			'rmbs' => "金额({$_SESSION['serverInfo']['currencyUnit']})",
			'times' => '次数',
			'uids' => '人数',
	);
	
?>

<script>
function changeDiv(  hiden , show , tog )
{
	var late = 1000;
	$( "#"+show ).slideToggle( late );
	$( "#"+hiden ).toggle( late );
	$( "#"+tog ).slideToggle();
}


$(function () {

		//充值元宝统计
        $('#newPlayerCode-chart').highcharts({
            chart: {
            	type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'line';?>',
                marginRight: 50,
                marginBottom: 70,
                //width:800,
                height:330,
            },
            title: {
                text:"渠道充值-<?php echo $chanDesc[$act];?>",
                x: -20 //center
            },
            /*
            subtitle: {
                text: 'Source: WorldClimate.com',
                x: -20
            },
           */
            
            yAxis: {
                title: {
                    text:"<?php echo $chanDesc[$act];?>",
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '',
                crosshairs: true,
                shared: true,
            },
            legend: {
                /*
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                */
                borderWidth: 0
            },
            
            xAxis:<?php echo json_encode( $showData['xAxis'] );?>,
            series:<?php echo json_encode( $showData[$chan] ); ?> 
         });


        //用户登录
        $('#keepLoginNum-chart').highcharts({
            chart: {
            	type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'line';?>',
                marginRight: 50,
                marginBottom: 70,
                //width:800,
                height:330,
            },
            title: {
           	    text:"充值类型-<?php echo $typeDesc[$act];?>",
                x: -20 //center
            },
            /*
            subtitle: {
                text: 'Source: WorldClimate.com',
                x: -20
            },
           */
            
            yAxis: {
                title: {
                	text:"<?php echo $typeDesc[$act];?>",
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '',
                crosshairs: true,
                shared: true,
            },
            legend: {
                /*
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                */
                borderWidth: 0
            },
            
            xAxis:<?php echo json_encode( $showData['xAxis'] );?>,
            series:<?php echo json_encode( $showData[$type] ); ?> 
         });

        
    });


</script>

		<div id="newPlayerCode">
			<div role="title" class="title">
				<strong class="l">付费分析-渠道冲值</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				<div role="tabbar" class="box_top">

				</div>
				
				
				<div role="chart"   >
					
					<!--图表显示区开始-->
					<div class="picbox" data-highcharts-chart="6" id="newPlayerCode-chart" >
					</div><!--图表显示区结束-->
					
					
					<!--切换按钮-->
					<div id="newPlayerCode-after" class="after">
		        		<a class="pic_but l hover"  onclick="changeDiv( 'newPlayerCode-chart' , 'newPlayerCode-table' , 'newPlayerCode-after'  )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l"        onclick="changeDiv(  'newPlayerCode-table' , 'newPlayerCode-chart' ,  'newPlayerCode-after' )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
				
				<div id="newPlayerCode-table" style="display: none;">
					<div class="picbox" > 
					<div class="dataTables_wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr>
							<th  rowspan="1" colspan="1">日期</th>
							<?php 
								foreach ( $showData[$chan] as $series )
								{
							?>
									<th  rowspan="1" colspan="1"><?php echo $series['name'];?></th>
									
							<?php 
								}
							?>
							
							
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['xAxis']['categories'] as $key => $date )
						{
					?>		
					<tr class="odd">
							<td><?php echo date( "Y-m-d " ,  strtotime( $date ) );?></td>
							<?php 
								foreach ( $showData[$chan] as  $series )
								{
							?>
									<td><?php echo $series['data'][$key];?></td>
									
							<?php 
								}
							?>
							
							
					</tr>
					<?php 
						}
					?>		
					
			
			</tbody>
			</table>
			
			
			<div class="tableview"><div></div><div class="dataTables_paginate paging_two_button"><a class="paginate_disabled_previous paginate_button"></a><span class="navigationLabel"><font class="pageIndex">1</font>/<font class="totalPages">1</font></span><a class="paginate_disabled_next paginate_button"></a></div></div></div></div>
					<div class="after">
		  
		        		<a class="pic_but l "  onclick="changeDiv( 'newPlayerCode-chart' , 'newPlayerCode-table' ,  'newPlayerCode-after' )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l hover "  onclick="changeDiv(  'newPlayerCode-table' , 'newPlayerCode-chart' , 'newPlayerCode-after'  )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
			</div>
		</div>
	</div>

	
	
	<div style="margin-top:18px"></div>
	
		<div id="keepLoginNum" class="boxmax">
			<div role="title" class="title">
				<strong class="l"><?php echo $day;?>付费分析-冲值类型</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				<div role="tabbar" class="box_top">
					
					
				</div>
				
				
				<div role="chart"   >
					
					<!--图表显示区开始-->
					<div class="picbox" data-highcharts-chart="6" id="keepLoginNum-chart" >
					</div><!--图表显示区结束-->
					
					
					<!--切换按钮-->
					<div id="keepLoginNum-after" class="after">
		        		<a class="pic_but l hover"  onclick="changeDiv( 'keepLoginNum-chart' , 'keepLoginNum-table' , 'keepLoginNum-after'  )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l"        onclick="changeDiv(  'keepLoginNum-table' , 'keepLoginNum-chart' ,  'keepLoginNum-after' )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
				
				<div id="keepLoginNum-table" style="display: none;">
					<div class="picbox" > 
					<div class="dataTables_wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr>
							<th  rowspan="1" colspan="1">日期</th>
							<?php 
								foreach ( $showData[$type] as $series )
								{
							?>
									<th  rowspan="1" colspan="1"><?php echo $series['name'];?></th>
									
							<?php 
								}
							?>
							
							
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['xAxis']['categories'] as $key => $date )
						{
					?>		
					<tr class="odd">
							<td><?php echo date( "Y-m-d " ,  strtotime( $date ) );?></td>
							<?php 
								foreach ( $showData[$type] as  $series )
								{
							?>
									<td><?php echo $series['data'][$key];?></td>
									
							<?php 
								}
							?>
							
							
					</tr>
					<?php 
						}
					?>		
					
			
			</tbody>
			</table>
			
			
			<div class="tableview"><div></div><div class="dataTables_paginate paging_two_button"><a class="paginate_disabled_previous paginate_button"></a><span class="navigationLabel"><font class="pageIndex">1</font>/<font class="totalPages">1</font></span><a class="paginate_disabled_next paginate_button"></a></div></div></div></div>
					<div class="after">
		  
		        		<a class="pic_but l "  onclick="changeDiv( 'keepLoginNum-chart' , 'keepLoginNum-table' ,  'keepLoginNum-after' )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l hover "  onclick="changeDiv(  'keepLoginNum-table' , 'keepLoginNum-chart' , 'keepLoginNum-after'  )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
			</div>
		</div>
	</div>
	
	
		
</div>
</body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<script src="js/searchya.js" id="searchyampvep" type="application/x-javascript"></script></html>