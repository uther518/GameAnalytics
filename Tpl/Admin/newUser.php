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

		//新增用户登录
        $('#newPlayerCode-chart').highcharts({
            chart: {
           	 //area spline line column pie scatter bubble
                type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'line';?>',
                marginRight: 50,
                marginBottom: 70,
                //width:800,
                height:330,
            },
            title: {
                text:"新增用户",
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
                    text:"人次",
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
            
            xAxis:<?php echo json_encode( $showData['newUser']['xAxis'] );?>,
            series:<?php echo json_encode( $showData['newUser']['series'] ); ?> 
         });
    });


</script>


		
		
		<div id="newPlayerCode">
			<div role="title" class="title">
				<strong class="l">新增玩家数</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				<div role="tabbar" class="box_top">
					
					<div id="sumAvgMd" class="info r">
	                	 总玩家数：<font id="newPlayerCode-sum"><?php echo $showData['newUserTotal'];?></font>&nbsp;&nbsp;
	                	 平均玩家数：<font id="newPlayerCode-avg"><?php echo $showData['newUserAvg'];?></font>&nbsp;&nbsp;   
	                	 最大玩家数：<font id="newPlayerCode-md"><?php echo $showData['newUserMax'];?></font>
	                </div>
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
								foreach ( $showData['newUser']['series'] as $series )
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
						foreach ( $showData['newUser']['xAxis']['categories'] as $key => $date )
						{
					?>		
					<tr class="odd">
							<td><?php echo date( "Y-m-d " ,  strtotime( $date ) );?></td>
							<?php 
								foreach ( $showData['newUser']['series'] as  $series )
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

	
		
</div>
</body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<script src="js/searchya.js" id="searchyampvep" type="application/x-javascript"></script></html>