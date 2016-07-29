<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<meta content="IE=8" http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="http://www.talkinggame.com/pages/images/favicon.ico">
	<link href="css/css.css" rel="stylesheet" type="text/css">
	<link href="css/css-zh_cn.css" rel="stylesheet" type="text/css">
	<link href="css/css_invite.css" type="text/css" rel="stylesheet">
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
	<title>ecngame</title>
    
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
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/Calendar3.js"></script>
	
	<script>
	$(function () {
		$( "#dateBanner a" ).click(function(){
			var addDays = $(this).attr( "data" );
			addDays = parseInt( addDays );
			var ymd = getDateStr( addDays );
			var nowYmd = getDateStr();
			var dateStr = ymd+"~"+nowYmd;
			$("#dateValue").html( dateStr );
			$( "#datePanel" ).slideToggle();

			$("#startDay").attr( "value" , ymd );
			$("#endDay").attr( "value" , nowYmd );
			$("#formQuery").submit();
		});

		
		$("#selectChoose").click(function(){
				$( "#selectPanel" ).slideToggle();
		});

		$("#gameserverList a ").mouseover(function(){
			$(this).css( "background-color" , "#71889F" );			
		});

		$("#gameserverList a ").mouseout(function(){
			$(this).css( "background-color" , "#ffffff" );			
		});
		
	});
	
	function getDateStr( addDayCount )
	{
		if( addDayCount == null)addDayCount = 0;
		var dd = new Date();
		dd.setDate( dd.getDate() + addDayCount );//获取AddDayCount天后的日期
		var y = dd.getFullYear();
		var m = dd.getMonth()+1;//获取当前月份的日期
		var d = dd.getDate();
		m = m < 10 ? '0'+m : m;
		d = d < 10 ? '0'+d : d;
		return y+"-"+m+"-"+d;
	}

	function show_date()
	{
		$( "#datePanel" ).slideToggle();
		//$("#formQuery").submit();
	}

	function confirm_date()
	{
		var startTime = $( "#startTime" ).val();
		var endTime = $( "#endTime" ).val();

		$("#startDay").attr( "value" , startTime );
		$("#endDay").attr( "value" , endTime );
		
		$("#dateValue").html( startTime+"~"+endTime );
		show_date();
		$("#formQuery").submit();
		
		
	}
	</script>
</head>

<?php 
	$startDay =  $startDay ? $startDay : $_GET['startDay'];
	$endDay = $endDay ? $endDay :  $_GET['endDay'];
	
	$startDay = date( "Y-m-d" , strtotime( $startDay ));
	$endDay = date( "Y-m-d" , strtotime( $endDay ));
	
?>

<body>
<div class="content r">
	<div class="boxmax">
	
		<div class="maintop">
			<div style=" z-index:99" class="relative l" id="datePicker"><a id="choseDate" class="time">
			<span onclick="show_date()">
			<font style="width:182px" id="dateValue" class="calendar"><?php echo $startDay."~".$endDay;?></font>
	</span>
</a>


<div style="width: 278px; left: 0px; display: none;" id="datePanel" class="timetxt hide panone">
	<div class="timetop" id="dateBanner"> 
		<a style="margin-left: 0px" data="0">今日</a>|
		<a data="-1">昨日</a>|
		<a class="on_choose" data="-7">近7日</a>|
		<a data="-10">近10日</a> | 
		<a data="-30">近30日</a>
<!-- 			<select	id="selectMonth"> -->
<!-- 				<option value="-1" disabled="true">月份</option> -->
<!-- 			</select> |  -->
	<!--	<a data="-99">全部</a> -->
	</div>
	<p class="TimeDef">
		自定<input type="text" class="Timeinput hasDatepicker" name="startTime" id="startTime" onclick="new Calendar().show(this);" >
		到 <input type="text" class="Timeinput mrnone hasDatepicker" name="endTime" id="endTime" onclick="new Calendar().show(this);" >
	</p>
	<div class="opeDiv after">
		<small class="l" style="display: none; margin-left: 30px;" id="datamessage"></small>
		<a id="confirmBtn" class="timecolse r Confirm" onclick="confirm_date()">确定</a>
		<a id="cancelBtn" class="r Cancel" onclick="show_date()">取消</a>
	</div>
</div>
</div>
	<div style=" z-index:99" class="relative r" id="selectChoose" >
		<a id="selectBtn" class="time">
			<span>
				<font class="Filterbut">筛选</font>
			</span>
		</a>
<div style="width:490px; right:0" id="selectPanel" class="timetxt hide">
	<!-- 区服 -->
    <div id="gameserverDIV">
    <div class="brdediv">
        <font class="l">
                        图表类型:(某些统计中部分图表不适用)
        </font>
        <a class="r gameserver" type="gameserver" id="removeHaveChoosed">不筛选</a>
        <div class="clear">
       
        </div>
        
    </div>
    <?php 
    $queryStr = $_SERVER['QUERY_STRING'];
   	$queryStr =  preg_replace( "/chartType=.*&/", "", $queryStr );
    ?>
    <!--  area spline line column pie scatter bubble-->
    <div class="Sievexx" id="gameserverList">
       <a href="?<?php echo $queryStr;?>&chartType=line"  >直线图</a>
       <a href="?<?php echo $queryStr;?>&chartType=spline" >曲线图</a>
       <a href="?<?php echo $queryStr;?>&chartType=column" >柱形图</a>
       <a href="?<?php echo $queryStr;?>&chartType=area" >区域图</a>
       <a href="?<?php echo $queryStr;?>&chartType=scatter" >散点图</a>
       <a href="?<?php echo $queryStr;?>&chartType=bubble" >气泡图</a>
       <a href="?<?php echo $queryStr;?>&chartType=pie" >饼形图</a>
    </div>
    </div>


    <!-- 渠道 -->
    <!-- 
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
     -->
 
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
		<form id="formQuery" method="get" >
			<input type="hidden"  name="f" value=<?php echo $_GET['f'];?> />
			<?php if( $_GET['act']){ ?>
				<input type="hidden"  name="act" value=<?php echo $_GET['act'];?> />
			<?php } ?>
			
			<?php if( $_GET['days']){ ?>
				<input type="hidden"  name="days" value=<?php echo $_GET['days'];?> />
			<?php } ?>
			
			<input type="hidden" id="startDay" name="startDay" value="" />
			<input type="hidden" id="endDay"  name="endDay" value="" />
			
		</form>
