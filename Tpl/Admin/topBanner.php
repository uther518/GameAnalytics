<div style="z-index:99999;" id="topBanner" class="top mainMinwidth">
    <div class="header">
        <div class="logo l">
            <a href="/index/index.jsp"><img title="Talking Game" alt="Talking Game" src="images/logo.png"></a>
        </div>
        <div class="nav r">
        	
            	<font class="user">Hi，<a href="#developerInfo"><?php echo $_SESSION['adminInfo']['loginName'];?></a></font>
            	<font><a href="?f=logout" id="logout" class="logout"><?php Lang( 'logout');?></a></font>
			
        </div>
    </div>
    <div class="toptitle"><div class="l">
	<strong><a href="?"><?php Lang( 'data_center');?></a> </strong>
    <span></span>
	<strong><?php Lang( 'create_app');?></strong>
</div>


<div class="r user_dropselect">
	<?php 
			if( in_array( 103 , $_SESSION['adminInfo']['writable'] ) )
			{
	?>
	<a href="?f=accountCenter"><h4><font><?php Lang( 'account_permission');?></font></h4></a>
	<?php 
			}
	?>
</div>
</div>
</div>