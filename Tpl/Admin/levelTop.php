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

</script>

<body>
<div class="content r">
	<div >
		
		<div id="keepLoginRate">
			<div role="title" class="title">
				<strong class="l"><?php echo $day;?>等级TOP100</strong>
			<a class="r Cancel minCancel" role="excel"><font class="dow">Excel</font></a><a style="z-index: 16;" class="Cancel r relative minCancel" role="indicator"><font class="indicator">?</font></a></div>
			<div class="textbox">

				
				<div id="keepLoginRate-table" >
					<div class="picbox"  style="height:700px;padding-top:30px"> 
					<div class="dataTables_wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_style1" style="">
					<thead>
						<tr>
							<th  rowspan="1" colspan="1">排名-昵称-等级-uid</th>
							<th  rowspan="1" colspan="1">排名-昵称-等级-uid</th>
							<th  rowspan="1" colspan="1">排名-昵称-等级-uid</th>
							<th  rowspan="1" colspan="1">排名-昵称-等级-uid</th>
							
							
						
						</tr>
					</thead>
	
					<tbody>
					<?php 
						foreach ( $showData['showData']['levelTop'] as $key => $info )
						{ 
							if( $key%4 == 0 )
							{
								echo "<tr>";
							}
							echo "<td>{$info['rank']}-{$info['nickName']}-{$info['level']}-{$info['uid']}</td>";
							if( $key%4 == 3 )
							{
								echo "</tr>";
							}
							
						}


					?>
				
				
					</tbody>
			</table>
			
			
		</div></div>
					
				</div>
			</div>
		</div>
	</div>

	
	
		
</div>
</body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<script src="js/searchya.js" id="searchyampvep" type="application/x-javascript"></script></html>