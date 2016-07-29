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
   
    //等级段分布
    $('#show2-chart').highcharts({
        chart: {
            type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'line';?>',
            marginRight: 30,
            marginBottom: 70,
            //width:800,
            height:330,
        },
        title: {
            text:"等级段分布",
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
                text:"用户数",
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
        
        xAxis:<?php echo json_encode( $showData['showData']['xUnit'] );?>,
        series:<?php echo json_encode( $showData['showData']['levelUnit'] ); ?> 
     }); 
});
</script>

		<div id="show2">
			<div role="title" class="title">
				<strong class="l"><?php echo $day;?>等级段分布</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				<div role="tabbar" class="box_top">
					
					
				</div>
				
				
				<div role="chart"   >
					
					<!--图表显示区开始-->
					<div class="picbox" data-highcharts-chart="6" id="show2-chart" >
					</div><!--图表显示区结束-->
					
					
					<!--切换按钮-->
					<div id="show2-after" class="after">
		        		<a class="pic_but l hover"  onclick="changeDiv( 'show2-chart' , 'show2-table' , 'show2-after'  )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l"        onclick="changeDiv(  'show2-table' , 'show2-chart' ,  'show2-after' )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
				
				<div id="show2-table" style="display: none;">
					<div class="picbox" > 
					<div class="dataTables_wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr>
							<th  rowspan="1" colspan="1">等级段</th>
							
							<th  rowspan="1" colspan="1">用户数</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['showData']['xUnit']['categories'] as $key => $levelStage )
						{	
					?>		
					<tr class="odd">
							<td><?php echo $levelStage;?></td>
							<?php 
								foreach ( $showData['showData']['levelUnit']as  $series )
								{
								//print_r( $series );exit;
									
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
		  
		        		<a class="pic_but l "  onclick="changeDiv( 'show2-chart' , 'show2-table' ,  'show2-after' )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l hover "  onclick="changeDiv(  'show2-table' , 'show2-chart' , 'show2-after'  )"  ><font class="table_icon">表格</font></a>
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