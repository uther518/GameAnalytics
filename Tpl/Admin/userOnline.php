<?php
	include "header.php";
?>

  
  <script>
	  $(function () {
		    $(document).ready(function() {
		        Highcharts.setOptions({
		            global: {
		                useUTC: false
		            }
		        });
		        var chart;
		        var updateSec = 500*5*60;//500为1秒,500*60*5为五分钟
		        $('#show-chart').highcharts({
		            chart: {
		            	type:'<?php echo $_SESSION['chartType'] ? $_SESSION['chartType'] : 'line';?>',
		                animation: Highcharts.svg, // don't animate in old IE
		                marginRight: 10,
		                marginBottom: 40,
		                //每次心跳更新的数据
		                events: {
		                    /*
		                    load: function() {
		                        // set up the updating of the chart each second
		                        var series = this.series[0];
		                        setInterval(function() {
		                            var x = (new Date()).getTime(), // current time
		                                y = 1.2;//Math.random();  ////每次心跳更新的数据
		                            series.addPoint([x, y], true, true);
		                        }, updateSec );
		                    }
		                    */
		                }
		            },
		            title: {
		                text: "在线人数统计"
		            },
		            subtitle: {
		                text: "统计日期:<?php echo $date;?>,每5分钟统计一次",
		                x: -20
		            },
		            xAxis: {
		                type: 'datetime',
		               // tickInterval : 3600 * 100 ,
		                tickPixelInterval: 100
		            },
		            yAxis: {
		                title: {
		                    text: '在线人数'
		                },
		                plotLines: [{
		                    value: 0,
		                    width: 1,
		                    color: '#808080'
		                }]
		            },
		            tooltip: {
		                formatter: function() {
		                	return Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+'在线'+this.y+'人';
		                }
		            },
		            legend: {
		                enabled: false
		            },
		            exporting: {
		                enabled: true
		            },
		            series: [{
		                name: '5分钟内在线用户',
		                data:<?php echo json_encode( $showData['userOnline'] );?> ,
		            }]
		        });
		    }); 


		


		    
		});

	  

</script>
	
	<style>
		.placeholderWrap{
		    position: relative; 
		    display: inline-block;}
		.placeholderWrap label{
		    color: #555;
		    position: absolute; 
		    top: 10px; left: 6px; /* Might have to adjust this based on font-size */
		    pointer-events: none;
		    display: block;
		}

		.placeholder-focus label{color: #999;}/* could use a css animation here if desired*/
		.placeholder-changed label{
		    display: none;
		}

	</style>
	<!--[if lt IE 9]>
		<script src="js/libs/excanvas.compiled.js" type="text/javascript"></script>
	<![endif]-->
	
		<div class="title">
			<strong class="l">在线玩家</strong> 
		</div>
		<div class="tablebox">
			<div class="after">
				<div class="center"  id="show-chart"  style="height:400px;margin:0 30px;" >	
					<div></div>
				</div>
			
				<div style="text-align:center;margin-bottom:20px;font-size:13px" >
					<a href="?f=userOnline&startDay=<?php echo $date;?>&hour=<?php echo $hour;?>&offType=day&num=-1" >前一天&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<a href="?f=userOnline&startDay=<?php echo $date;?>&hour=<?php echo $hour;?>&offType=hour&num=-5" >前5小时&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<a href="?f=userOnline&startDay=<?php echo $date;?>&hour=<?php echo $hour;?>&offType=curr" >当前时间&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<a href="?f=userOnline&startDay=<?php echo $date;?>&hour=<?php echo $hour;?>&offType=hour&num=+5" >后5小时&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<a href="?f=userOnline&startDay=<?php echo $date;?>&hour=<?php echo $hour;?>&offType=day&num=+1" >后一天&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Concurrent Users end -->
	<!-- Recent Players begin -->

	<!-- Recent Players end -->
	<div class="boxmax">
	
</div>
<div class="clear"></div>
</div>
</div>
</body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<script src="js/searchya.js" id="searchyampvep" type="application/x-javascript"></script></html>