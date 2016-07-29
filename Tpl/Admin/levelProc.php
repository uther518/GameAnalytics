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
    $('#show1-chart').highcharts({
        chart: {
            zoomType: 'x',
            spacingRight: 30,
            height:330,
        },
        title: {
            text: '当日注册玩家等级分布图'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                '选区并拖动可以查看细节，左右拖动可以查看前后数据' :
                'Drag your finger over the plot to zoom in'
        },
        
        xAxis: {
           // type: 'num',
            maxZoom:10, // fourteen days
            title: {
                text: '用户等级',
            }
        },
        yAxis: {
            title: {
                text: '用户数'
            }
        },
        tooltip: {
            shared: true
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                lineWidth: 1,
                marker: {
                    enabled: false
                },
                shadow: false,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
        	type:'area',
            name: '用户数 ',
            pointInterval: 1,
            pointStart: 1,
            data:<?php echo json_encode( $showData['showData']['todayLevelMap'][0]['data'] );?>
        }]
    });


});
</script>

		
		
		
		<div id="show1">
			<div role="title" class="title">
				<strong class="l">详细等级分布</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">
				<div role="tabbar" class="box_top">
					
					<div id="sumAvgMd" class="info r">
	                	 总玩家数：<font id="show1-sum"><?php echo $showData['loginUserTotal'];?></font>&nbsp;&nbsp;
	                	 平均玩家数：<font id="show1-avg"><?php echo $showData['loginUserAvg'];?></font>&nbsp;&nbsp;   
	                	 最大玩家数：<font id="show1-md"><?php echo $showData['loginUserMax'];?></font>
	                </div>
				</div>
				
				
				<div role="chart"   >
					
					<!--图表显示区开始-->
					<div class="picbox" data-highcharts-chart="6" id="show1-chart" >
					</div><!--图表显示区结束-->
					
					
					<!--切换按钮-->
					<div id="show1-after" class="after">
		        		<a class="pic_but l hover"  onclick="changeDiv( 'show1-chart' , 'show1-table' , 'show1-after'  )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l"        onclick="changeDiv(  'show1-table' , 'show1-chart' ,  'show1-after' )"  ><font class="table_icon">表格</font></a>
		        	</div>
				</div>
				
				<div id="show1-table" style="display: none;">
					<div class="picbox" > 
					<div class="dataTables_wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					
			</table>
			
			
			<div class="tableview"><div></div><div class="dataTables_paginate paging_two_button"><a class="paginate_disabled_previous paginate_button"></a><span class="navigationLabel"><font class="pageIndex">1</font>/<font class="totalPages">1</font></span><a class="paginate_disabled_next paginate_button"></a></div></div></div></div>
					<div class="after">
		  
		        		<a class="pic_but l "  onclick="changeDiv( 'show1-chart' , 'show1-table' ,  'show1-after' )"><font class="pic_icon">图表</font></a>
		        		<a class="pic_but l hover "  onclick="changeDiv(  'show1-table' , 'show1-chart' , 'show1-after'  )"  ><font class="table_icon">表格</font></a>
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