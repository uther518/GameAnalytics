<?php
	include "header.php";
	$day = ( $_GET['days'] == 2 ) ? '次' : $_GET['days'];
	
	
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
                // //area spline area  line column pie scatter bubble
            	type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'line';?>',
                marginRight: 50,
                marginBottom: 70,
                //width:800,
                height:330,
            },
            title: {
                text:"当日<?php echo $showData['todayNewbie']['series'][0]['name'];?>",
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
                    text:"<?php echo $showData['todayNewbie']['yName'];?>",
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
                  borderWidth:0
            },
          
            
            xAxis:<?php echo json_encode( $showData['todayNewbie']['xAxis'] );?>,
            series:<?php echo json_encode( $showData['todayNewbie']['series'] ); ?> 
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
                text:"所有<?php echo $showData['newbie']['series'][0]['name'];?>",
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
                    text:"<?php echo $showData['newbie']['yName'];?>",
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
            
            xAxis:<?php echo json_encode( $showData['newbie']['xAxis'] );?>,
            series:<?php echo json_encode( $showData['newbie']['series'] ); ?> 
         });
        
    });


</script>

		
		
		<div id="keepLoginRate">
			<div role="title" class="title">
				<strong class="l">当日<?php echo $showData['todayNewbie']['series'][0]['name'];?></strong>
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
							<th  rowspan="1" colspan="1">新手步骤</th>
							<th  rowspan="1" colspan="1">用户数</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['todayNewbie']['xAxis']['categories'] as $key => $step )
						{
					?>		
					<tr class="odd">
							<td><?php echo $step;?></td>
							<?php 
								foreach ( $showData['todayNewbie']['series'] as  $series )
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
				<strong class="l">所有<?php echo $showData['newbie']['series'][0]['name'];?></strong>
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
							<th  rowspan="1" colspan="1">新手进度</th>
							<th  rowspan="1" colspan="1">用户数</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['newbie']['xAxis']['categories'] as $key => $step )
						{
					?>		
					<tr class="odd">
							<td><?php echo $step;?></td>
							<?php 
								foreach ( $showData['newbie']['series'] as  $series )
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