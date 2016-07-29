<?php
	include "header.php";
	$items = array(
		'gainCoin' => '冲值币获取',
		'gainGold' => '游戏币获取',
		'consumeCoin' => '冲值币消耗',
		'consumeGold' => '游戏币消耗',
	);
	
	$units = array(
		'gainCoin' => '冲值币',
		'gainGold' => '游戏币',
		'consumeCoin' => '冲值币',
		'consumeGold' => '游戏币',
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

		//用户登录
        $('#keepLoginRate-chart').highcharts({
            chart: {
                //area spline area  line column pie scatter bubble
            	type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'column';?>',
                marginRight: 50,
                marginBottom: 70,
                //width:1200,
                height:330,
            },
            title: {
                text:"<?php echo $items[$act]."数量";?>",
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
                    text:"<?php echo $items[$act];?>",
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
            series:<?php echo json_encode( $showData['data'][$act."Num"] ); ?> 
         });



        //用户登录
        $('#keepLoginNum-chart').highcharts({
            chart: {
            	type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'column';?>',
                marginRight: 50,
                marginBottom: 70,
                //width:800,
                height:330,
            },
            title: {
           	 text:"<?php echo $items[$act]."次数";?>",
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
               	 text:"<?php echo $items[$act];?>",
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
            series:<?php echo json_encode( $showData['data'][$act."Times"] ); ?> 
         });
        
    });


</script>

		
		
		<div id="keepLoginRate">
			<div role="title" class="title">
				<strong class="l"><?php echo $day;?>虚拟币统计</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				<div role="tabbar" class="box_top">
					
					
				</div>
				
				
				<div role="chart"   >
					
					<!--图表显示区开始-->
					<div class="picbox" data-highcharts-chart="6" id="keepLoginRate-chart" >
					</div><!--图表显示区结束-->
					
					
					<!--切换按钮-->
					<div id="keepLoginRate-after" class="after">
		        		<a class="pic_but l hover"  onclick="changeDiv( 'keepLoginRate-chart' , 'keepLoginRate-table' , 'keepLoginRate-after'  )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l"        onclick="changeDiv(  'keepLoginRate-table' , 'keepLoginRate-chart' ,  'keepLoginRate-after' )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
				
				<div id="keepLoginRate-table" style="display: none;">
					<div class="picbox" > 
					<div class="dataTables_wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr>
							<th  rowspan="1" colspan="1">日期</th>
							<?php 
								
								foreach ( $showData['data'][$act."Num"] as $data ){ 
							?>
							<th  rowspan="1" colspan="1"><?php echo $data['name'];?></th>		
							<?php }?>
						
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
								foreach ( $showData['data'][$act."Num"]  as  $series )
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
		  
		        		<a class="pic_but l "  onclick="changeDiv( 'keepLoginRate-chart' , 'keepLoginRate-table' ,  'keepLoginRate-after' )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l hover "  onclick="changeDiv(  'keepLoginRate-table' , 'keepLoginRate-chart' , 'keepLoginRate-after'  )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
			</div>
		</div>
	</div>

	
		<div style="margin-top:18px"></div>
	
		<div id="keepLoginNum" class="boxmax" >
			<div role="title" class="title">
				<strong class="l"><?php echo $day;?>日留存数</strong>
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
								
								foreach ( $showData['data'][$act."Times"] as $data ){ 
							?>
							<th  rowspan="1" colspan="1"><?php echo $data['name'];?></th>		
							<?php }?>
						
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
								foreach ( $showData['data'][$act."Times"]  as  $series )
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