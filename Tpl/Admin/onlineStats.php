<?php
	include "header.php";
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
                text:"平均在线人数-ACU",
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
          
            
            xAxis:<?php echo json_encode( $showData['xAxis'] );?>,
            series:<?php echo json_encode( $showData['acu'] ); ?> 
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
            	text:"最高在线人数-PCU",
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
            
            xAxis:<?php echo json_encode( $showData['xAxis'] );?>,
            series:<?php echo json_encode( $showData['pcu'] ); ?> 
         });
        
    });


</script>

		
		
		<div id="keepLoginRate">
			<div role="title" class="title">
				<strong class="l">平均在线人数-ACU</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
			
				<div role="tabbar" class="box_top">
					
					<div id="sumAvgMd" class="info r">
	                	查询时间范围内: 最高在线人数：<font id="newPlayerCode-sum"><?php echo $showData['pcuAll'];?></font>&nbsp;&nbsp;
	                	 平均在线人数：<font id="newPlayerCode-avg"><?php echo $showData['acuAll'];?></font>&nbsp;&nbsp;   
	                	
	                </div>
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
							<th  rowspan="1" colspan="1">平均用户数</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
				
						foreach ( $showData['xAxis']['categories'] as $key => $date )
						{
							
							
					?>		
					<tr class="odd">
							<td><?php echo $date;?></td>
							<?php 
								foreach ( $showData['acu'] as  $series )
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
				<strong class="l">最高在线人数-PCU</strong>
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
							<th  rowspan="1" colspan="1">最高在线人数</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
				
						foreach ( $showData['xAxis']['categories'] as $key => $date )
						{
							
							
					?>		
					<tr class="odd">
							<td><?php echo $date;?></td>
							<?php 
								foreach ( $showData['pcu'] as  $series )
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