function checkLen(val,field,obj)
        {
	        	var len = 0;   
				    for (var i=0; i<val.length; i++) {   
				        if (val.charCodeAt(i)>127 || val.charCodeAt(i)==94) {   
				            len += 2;   
				        } else {   
				            len ++;   
				        }   
				    }
        		var lennum=field;
						if(len<=lennum)
						{
							return true;
						}
						try 
				    { 
				        obj.focus();
				    } 
				    catch(ex)
				    { 
				        
				    } 
						alert(i18n.t('check.textMoreLong'));
						return false;
        }
        
//验证是否为数字格式
function isNumber(str,obj)
{
	if(str==null||str=="")
	{
		return true;
	}
	var intnum=/^[0-9]{0,}$/;
	if(intnum.test(str))
	{
		return true;
	}else
	{
		var num = /^[0-9]+(.[0-9]{1,10})$/;
		if(num.test(str))
		{
			return true;
		}else
		{
			try 
	    { 
	        obj.focus();
	    } 
	    catch(ex)
	    { 
	        
	    } 
			alert(i18n.t('check.enterTypeIsWrongJustNumber'));
			return false;
		}
	}
	
}


//验证是否为数字格式
function isNumberInt(str,obj)
{
	if(str==null||str=="")
	{
		return true;
	}
	var intnum=/^(-)?[0-9]+\d*$/;
	if(intnum.test(str))
	{
		return true;
	}else
	{
			alert(i18n.t('check.enterJustInteger'));
			return false;
	}
	
}

function isInteger(str){
	if(str==null||str=="")
	{
		return false;
	}
	var intnum=/^[0-9]{0,}$/;
	if(intnum.test(str))
	{
		return true;
	}else
	{
			return false;
	}
}

function isFloat(str){
	if(str==null||str=="")
	{
		return false;
	}
	var intnum=/^[0-9]+(.[0-9]{1,10})$/;
	if(intnum.test(str))
	{
		return true;
	}else
	{
			return false;
	}
}

//验证是否为电子信箱格式
function isEmail(str1,obj)
{
	if(str1==null||str1=="")
	{
		return false;
	}
	var str=str1.replace(/(^\s*)|(\s*$)/g,"");
	//var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*([a-zA-Z0-9]+[_|\_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;

	if(myreg.test(str))
	{
		return true;
	}else
	{
		return false;
	}
}

//验证是否为电子信箱格式
function isEmailanother(value){
	var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*([a-zA-Z0-9]+[_|\_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(myreg.test(value)){
		return true;
	}else{
		return false;
	}
}

//验证是否为手机号码格式
function isMobileTel(phone,obj)
{
	if(phone==null||phone=="")
	{
		return true;
	}
	var my = false;
	var reg0 = /^13\d{9}$/;
	var reg1 = /^15\d{9}$/;
	var reg2= /^18\d{9}$/;
	if (reg0.test(phone))
	{
		my=true;
	}
	else if (reg1.test(phone)){
		my=true;
	}else if(reg2.test(phone)){
		my=true;
	}
	else{
		my=false;
	}
	return my;
}

//第二种检查是否为手机号格式
function isMobel(value){
	return /^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/.test(value);
//	if(/^13\d{9}$/g.test(value)||(/^15[0-35-9]\d{8}$/g.test(value))|| (/^18[05-9]\d{8}$/g.test(value))){
//		 return true; 
//	}
//	else{ 
//		return false;
//	 }
}


//验证姓名
function isName(value){
	var myreg=/^[a-zA-Z0-9]{4,30}$/;
	if(myreg.test(value)){
		return true;
	}else{
		return false;
	}
}

//验证联系人
function isContact(value){
	var myreg=/^[a-z | A-Z | 0-9 | \u4e00-\u9fa5]{1,16}$/;
	if(myreg.test(value)){
		return true;
	}else{
		return false;
	}
}

//验证公司
function isCompany(value){
	var myreg=/^[a-z | A-Z | 0-9 | \u4e00-\u9fa5]{1,50}$/;
	if(myreg.test(value)){
		return true;
	}else{
		return false;
	}
}

//验证是否为QQ
function isQQ(value){
	var myreg=/^[1-9]\d{4,12}$/;
	if(myreg.test(value)){
		return true;
	}else{
		return false;
	}
}

//MSN校验
function isMSN(value){
	var myreg=/^\w+@hotmail\.com$/;
	if(myreg.test(value)){
		return true;
	}else{
		return false;
	}
}

//验证是否为电话号码格式
function isTelphone(phone,obj)
{
	if(phone==null||phone=="")
	{
		return true;
	}
	//var myreg = /^(\d{3,4}(-)?)?\d{7,9}$/g;
//	var myreg = /^(\d{3,4}(-)?)?(\d{7,9}(-)?)(\d{3,4})?$/g;
	var myreg = /^0\d{2,3}(\-)?\d{7,8}$/;
	if(myreg.test(phone))
	{
		return true;
	}else
	{
		//alert("输入格式错误应输入333-1234567或1234-123456789");
//		alert("固定电话格式错误，规则:7位或8位或9位电话号码；3位或4位区号-7位或8位或9位电话号码；3位或4位区号-7位或8位或9位电话号码-3位或4位分机号；3位或4位区号7位或8位或9位电话号码");
		return false;
	}
}
//按字节计算字符串长度
function  getByteLen(str)   
{   
  var   l   =   str.length;   
  var   n   =   l;   
  for   (   var   i=0;   i<l;   i++   )   
  if   (   str.charCodeAt(i)<0   ||   str.charCodeAt(i)>255   )   
  n++;   
  return  n   
} 

String.prototype.Trim = function()
{
    return this.replace(/(^\s*)|(\s*$)/g,"");
}

function subdatestring(val)
{
	return val.substring(0,10);
	}

function dateCompare(sdate,edate){
	if(sdate!=null&&sdate!=""&&edate!=null&&edate!=""){
		var dt1=new Date(Date.parse(sdate));
	    var dt2=new Date(Date.parse(edate));
	    if(dt1>dt2){//比较日期
	        alert(i18n.t('check.startDateIsBiggerThanEndDate'));
	        return false;
	    }
	}
	return true;
}

function CheckDate(SparaDate,obj)
 { 
 				
     var strYMDSP = 0; 
     var strYMD;
     
     SparaDate=subdatestring(SparaDate);
     if(SparaDate=="1970-01-01")
     {
     	   alert(i18n.t('check.dateFormatError'));
     	   return false;
     	}
     
     //判断YYYYMMDD中的分隔符号 不是- 或/报错     
      if (!(SparaDate.substr(4,1)=="-"))
      { 
          if(!(SparaDate.substr(4,1)=="/"))
           {
          	   alert(i18n.t('check.dateFormatMustLikeThis'));
           		return false;   
           	}
       }            
         
     var strYear = SparaDate.substr(0,4);
     SparaDate   = SparaDate.substr(5,SparaDate.length-5);
          
    //去掉年后的字符串   
    for (i=0;i<SparaDate.length;i++)
    {
        if (SparaDate.substr(i,1)=="-") 
        {
           strYMDSP = i;
           break;
        }
       if (SparaDate.substr(i,1)=="/") 
       {
          strYMDSP = i;
          break;
       }
    }         
   //剩下的字符串中没有-或/报错   
    if  (strYMDSP<1)
    {
    	 alert(i18n.t('check.dateFormatMustLikeThis'));
    	return false;
    }
    return true;
 }

 //体积相关的校验
function isVolume(str,obj)
{
	if(str==null||str=="")
	{
		return true;
	}
	var strTemp = null;
	if(str.indexOf("*") == -1 && str.indexOf("×") == -1){
		 alert(i18n.t('check.volumePatternError'));
		return false;
	}else{		
		if(str.indexOf("*") != -1)
			strTemp = str.split("*");
		else
			strTemp = str.split("×");
	}
	for(i=0;i<strTemp.length;i++){
		var str0 = strTemp[i];
		
		var intnum=/^[0-9]{0,}$/;
		if(intnum.test(str0))
		{
			return true;
		}else
		{
			var num = /^[0-9]+(.[0-9]{1,3})$/;
			if(num.test(str0))
			{
				return true;
			}else
			{
				try
			    { 
			        obj.focus();
			    } 
			    catch(ex)
			    { 
			        
			    } 
				 alert(i18n.t('check.enterTypeIsWrongJustNumber'));
				return false;
			}
		}
	}
}

function lastimgname(filepath)
{
 //获取欲上传的文件路径

//为了避免转义反斜杠出问题，这里将对其进行转换
var re = /(\\+)/g;  
var filename=filepath.replace(re,"#"); 
//对路径字符串进行剪切截取
var one=filename.split("#"); 
//获取数组中最后一个，即文件名
var two=one[one.length-1]; 
//再对文件名进行截取，以取得后缀名
var three=two.split("."); 
 //获取截取的最后一个字符串，即为后缀名
var last=three[three.length-1];
//添加需要判断的后缀名类型
var tp ="jpg|gif|png|bmp"; 
//返回符合条件的后缀名在字符串中的位置
var rs=tp.indexOf(last); 
//如果返回的结果大于或等于0，说明包含允许上传的文件类型
if(rs>=0){
 return true;
 }else{
	 alert(i18n.t('check.filePatternError'));
 return false;
  }
}

function lastswfname(filepath)
{
 //获取欲上传的文件路径

//为了避免转义反斜杠出问题，这里将对其进行转换
var re = /(\\+)/g;  
var filename=filepath.replace(re,"#"); 
//对路径字符串进行剪切截取
var one=filename.split("#"); 
//获取数组中最后一个，即文件名
var two=one[one.length-1]; 
//再对文件名进行截取，以取得后缀名
var three=two.split("."); 
 //获取截取的最后一个字符串，即为后缀名
var last=three[three.length-1];
//添加需要判断的后缀名类型
var tp ="flv"; 
//返回符合条件的后缀名在字符串中的位置
var rs=tp.indexOf(last); 
//如果返回的结果大于或等于0，说明包含允许上传的文件类型
if(rs>=0){
 return true;
 }else{
	 alert(i18n.t('check.filePatternError'));
 return false;
  }
}

 function intTotime(value){
 		if(!value){
 			return "00:00";
 		}
 		var param=parseInt(value);
 	 	var  str;
		if(param==0){
			return "00:00";
		}
		var hour=parseInt(param/3600);
		var min=parseInt((param%3600)/60);
		var sec=parseInt((param%3600)%60);
		var hours;
		var mins;
		var secs;
		if(hour<10){
			hours="0"+hour;
		}else{
			hours=hour+"";
		}
		if(min<10){
			mins="0"+min;
		}else{
			mins=min+"";
		}
		if(sec<10){
			secs="0"+sec;
		}else{
			secs=sec+"";
		}
		if(hours=="00"){
			str=mins+":"+secs;
		}else{
			str=hours+":"+mins+":"+secs;
		}
		return str;
  }
  
  function SetCookie(name,value)//两个参数，一个是cookie的名子，一个是值
{
    var Days = 30;
    var exp  = new Date();    //new Date("December 31, 9998");
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

function getCookie(name)//取cookies函数        
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;
}

function delCookie(name)//删除cookie
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}