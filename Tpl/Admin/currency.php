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
                type: 'line',
                marginRight: 130,
                marginBottom: 25,
                //width:800,
                height:330,
            },
            title: {
                text:"<?php echo $day;?>日留存率",
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
                    text:"留存率",
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            
            xAxis:<?php echo json_encode( $showData['keepLogins']['xAxis'] );?>,
            series:<?php echo json_encode( $showData["keepLogin{$_GET['days']}Rate"] ); ?> 
         });



        //用户登录
        $('#keepLoginNum-chart').highcharts({
            chart: {
                type: 'line',
                marginRight: 130,
                marginBottom: 25,
                //width:800,
                height:330,
            },
            title: {
                text:"<?php echo $day;?>日留存数",
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
                    text:"留存数",
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            
            xAxis:<?php echo json_encode( $showData['keepLogins']['xAxis'] );?>,
            series:<?php echo json_encode( $showData["keepLogin{$_GET['days']}Num"] ); ?> 
         });
        
    });


</script>

<body>
<div class="content r">
	<div class="boxmax">
		<div class="maintop">
			<div style=" z-index:99" class="relative l" id="datePicker"><a id="choseDate" class="time">
	<span onclick="show_date()">
			<font style="width:182px" id="dateValue" class="calendar">2013-07-12 ~ 2013-07-15</font>
	</span>
</a>
<div style="width: 278px; left: 0px; display: none;" id="datePanel" class="timetxt hide panone">
	<div class="timetop" id="dateBanner"> 
		<a style="margin-left: 0px" data="0">今日</a>|
		<a data="-1">昨日</a>|
		<a class="on_choose" data="-7">近7日</a>|
		<a data="-30">近30日</a> | 
<!-- 			<select	id="selectMonth"> -->
<!-- 				<option value="-1" disabled="true">月份</option> -->
<!-- 			</select> |  -->
		<a data="99">全部</a> 
	</div>
	<p class="TimeDef">
		自定<input type="text" class="Timeinput hasDatepicker" name="startTime" id="startTime">到 <input type="text" class="Timeinput mrnone hasDatepicker" name="endTime" id="endTime">
	</p>
	<div class="opeDiv after">
		<small class="l" style="display: none; margin-left: 30px;" id="datamessage"></small>
		<a id="confirmBtn" class="timecolse r Confirm" onclick="show_date()">确定</a>
		<a id="cancelBtn" class="r Cancel" onclick="show_date()">取消</a>
	</div>
</div>
</div>
			<div style=" z-index:99" class="relative r" id="selectChoose"><a id="selectBtn" class="time">
	<span>
		<font class="Filterbut">筛选</font>
	</span>
</a>
<div style="width:490px; right:0" id="selectPanel" class="timetxt hide">
    <!-- 渠道 -->
    <div id="partnerDIV">
    <div class="brdediv">
        <font class="l">
            渠道：
        </font>
        <a class="r partner" type="partner" id="removeHaveChoosed">不筛选</a>
        <div class="clear">
        </div>
    </div>
    <div class="Sievexx" id="partnerList"></div>
    </div>
    
    <!-- 区服 -->
    <div id="gameserverDIV">
    <div class="brdediv">
        <font class="l">
            区服：
        </font>
        <a class="r gameserver" type="gameserver" id="removeHaveChoosed">不筛选</a>
        <div class="clear">
        </div>
    </div>
    <div class="Sievexx" id="gameserverList"></div>
    </div>
	<div class="opeDiv after">
		<a id="confirmBtn" class="Sievecolse r Confirm">确定</a>
		<a id="cancelBtn" class="Sievecolse r Cancel">取消</a>
	</div>
</div></div>
			<div class="clear"></div>
			<div class="Editor hide Filter" id="showSelectedPartners">
				<div id="selectVersion"></div>
			</div>
		</div>
		
		
		
		<div id="keepLoginRate">
			<div role="title" class="title">
				<strong class="l"><?php echo $day;?>日留存率</strong>
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
							<th  rowspan="1" colspan="1">留存率</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['keepLogins']['xAxis']['categories'] as $key => $date )
						{
					?>		
					<tr class="odd">
							<td><?php echo date( "Y-m-d " ,  strtotime( $date ) );?></td>
							<?php 
								foreach ( $showData["keepLogin{$_GET['days']}Rate"] as  $series )
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
	
		<div id="keepLoginNum">
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
							<th  rowspan="1" colspan="1">留存率</th>		
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['keepLogins']['xAxis']['categories'] as $key => $date )
						{
					?>		
					<tr class="odd">
							<td><?php echo date( "Y-m-d " ,  strtotime( $date ) );?></td>
							<?php 
								foreach ( $showData["keepLogin{$_GET['days']}Num"] as  $series )
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