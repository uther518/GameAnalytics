<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="IE=7" http-equiv="X-UA-Compatible" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>
		ecngame-专业的手游数据统计分析平台|iOS游戏统计分析|Android游戏统计分析|Unity游戏统计分析
</title>
<script src="js/javascript.js" type="text/javascript"></script>
<script src="js/jquery-1.4.4.js"></script>
<script type="text/javascript" src="js/i18next.js"></script>
<script src="js/input.js"></script>
<script src="js/js.js" type="text/javascript"></script>
<script src="js/login.js" type="text/javascript"></script>
<?php
$url = "http://".$_SERVER['SERVER_ADDR'].$_SERVER['REQUEST_URI'];
?>


<script type="text/javascript">
var basePath="<?php echo $url;?>";

</script>
<link href="css/inc.css" rel="stylesheet" type="text/css" />
<link href="css/contact.css" rel="stylesheet" type="text/css" />
<link href="css/rl.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body onkeydown="keyLogin();">


<div class="menu" id="menu">
	<a href="./index.html" class="hm">
    	<span class="png">主页</span>
    </a>
    <a href="./function.html" class="fn">
    	<span class="png">功能</span>
    </a>
</div>


<!--[if IE 6]><script type="text/javascript" src="./js/dd_belatedpng.js"></script>
<script type="text/javascript">
addEvent(window,"load",function(){
	  DD_belatedPNG.fix('.png,a .png:hover,body img');
})
</script>
<![endif]-->



<div class="top">
	<div class="main login png">
    	<a href="./login.html">登录</a>|<a href="./regist.html">注册</a>
    </div>
</div>


<script>
function logout(){
	var vtimecc=Math.random();
	var url=basePath+"servlet/UserLoginServlet";
	var params = {
		servertype:1,
		timcc:vtimecc
	};
	$.get(url,params,logcallback);
}
	
function logcallback(data){
	window.location.href=basePath+"index/login.html";
}


function loginSubmit()
{
	$( "#form1" ).submit();
}

</script>

<!-- Logo -->
<div class="main logo">
	<img src="images/fn_logo.png" alt="ecngame-专业的手游数据统计分析平台"/>
</div>




<!--Content -->
<div class="main content png" id="login">
	<div class="alert">
    	<div class="title">
        	登录
        </div>
        <div class="txt">
            <p class="tipinfo">
                                 您在ecngame任意产品中注册的帐户均可直接登录
            </p>
            <form id="form1" class="table" action="" method="post">
            
        	<ul class="text">
                <li class="relative">
                    <label>用户名</label>
                    <input id="lemail" name="loginName" class="input"  type="text" value="" onfocus="hide('emailmessage');" onblur="fun1();"/>
                    <span class="Prompt" id="lemaildiv" style="display:none"></span>
               	</li>
                <li class="relative">
                    <label>密码</label>
                    <input class="input" name="password" id="lpassword" type="password" value="" onfocus="hide('passwordmessage');" onblur="fun2();"/>
                    <span class="Prompt" id="lpwddiv" style="display:none"></span>
                </li>
                <li> 
                    <span style="height:60px">
                        <span class="l" style="margin-left:34px"><input type="checkbox" id="talkdatacheckbox"/>记住密码</span>
                        <p id='loading' class="l" style="12px 0 0 32px">正在登录...<img src="../images/loading.gif" height="25px" alt="正在加载" /></p>
                        <a id="login" href="javascript:void(0);" class="button" onclick="loginSubmit();"><font>登录</font></a>                        
                	</span>
                    <strong>还没有ecngame账户？<a href="regist.html">点击注册</a></strong>
                </li>
          </ul>
          		
          	
          </form>
         </div>
    </div>
</div>

<script type="text/javascript">
$.i18n.init({
    lng:'zh_cn',
    ns: { namespaces: ['ns.html'], defaultNs: 'ns.html'},
    useLocalStorage: false
});
</script>


<script type="text/javascript" src="./js/jquery-1.4.4.js"> </script>
<link href="./css/style_zh.css" rel="stylesheet" type="text/css" />




<script>
	function changeLocale(obj){
		var tempLocale = 'zh_cn';
		var currentLocale = obj || "en_us";
		if(tempLocale!=currentLocale){
			$.ajax({
				url:"/v1/language/change?locale="+currentLocale,
				type : "get",
				success : function(data){
					if(data=="success"){
						window.location.reload();
						window.parent.document.location.reload();
					}
				}
			});
		}
	}
</script>
</body>
</html>

