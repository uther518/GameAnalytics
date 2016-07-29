var emailmessage = i18n.t('activeRegist.enterEmailAddressLikeThis');
var username =  i18n.t('activeRegist.4to30Char');
var passwordmessage =i18n.t('activeRegist.enter6To32Character');
// var telephonemessage = "请输入您的联系手机";
// var companymessage = "请输入1~50位中文、英文、数字字符";
// var qqmessage = "请输入您的QQ/MSN";
// var contactmessage = "可输入1~16个字符，包括英文、中文、数字";
var vtimecc;
var acode;
$(function(){
	$(".text input").focusin(function(){
		$(this).next().hide();
	});
});

function regist() {
	    var url = basePath + "servlet/RegisteServlet";
		var email = $("#email").val(),
			password = $("#password").val(),
			name = $("#name").val(),
			company = $('#company').val(),
			tel = $('#TEL').val(),
			qq = $('#qq').val(),
			params = null;
		//非空验证
		if(!name){
			setDivInfo("namediv", i18n.t('activeRegist.nameNotEmpty'), 1);
			return;
		}
		if(!company){
			setDivInfo("companydiv", i18n.t('activeRegist.conpanyNotEmpty'), 1);
			return;
		}
		if(!tel){
			setDivInfo("TELldiv",  i18n.t('activeRegist.phoneNotEmpty'), 1);
			return;
		}
		if(!email){
			setDivInfo("emaildiv", i18n.t('activeRegist.EmailMustNotEmpty'), 1);
			return;
		}
		if(!qq){
			setDivInfo("qqdiv", i18n.t('activeRegist.qqNotEmpty'), 1);
			return;
		}
		if(!password){
			setDivInfo("passworddiv",i18n.t('activeRegist.PWDNotEmpty'), 1);
			return;
		}
		//数据格式验证
		if(name.length>30){
			setDivInfo("namediv", i18n.t('activeRegist.NameMost30Character'), 1);
			return;
		}
		if(!isTelphone(tel) && !isMobel(tel)){
			setDivInfo("TELldiv", i18n.t('activeRegist.phoneTypeNotCorrect'), 1);
			return;
		}
		if(email.length > 50){
			setDivInfo("emaildiv", i18n.t('activeRegist.least50charEmailAddress'), 1);
			return;
		}
		if(!isEmail(email)){
			setDivInfo("emaildiv", i18n.t('activeRegist.enterEmailAddressLikeThis'), 1);
			return;
		}
		if(!/^[1-9]\d{4,14}$/.test(qq)){
			setDivInfo("qqdiv",i18n.t('activeRegist.QQTypeIsWrong'), 1);
			return;
		}
		if(password.length<6 || password.length>32){
			setDivInfo("passworddiv", i18n.t('activeRegist.enter6To32Character'), 1);
			return;
		}
		//申请平台使用权限
		var platformPower = $("#platformPower").val();
		
		//先检测邮箱是否已经注册
		params = {
			vemail : email,
			servertype : 1,
			timecc : Math.random()	
		};
		if(platformPower != null && platformPower != "") params["servertype"] = 6;
		
		loadShow();
		$.ajax({
			url:url,
			data:params,
			async:false,
			success:function(data){
				loadHide();
				if(data != "0") {
					setDivInfo("emaildiv",i18n.t('activeRegist.thisEmailHasRegistered'), 1);
				} else {
						if(checkpassword()){
							loadShow();
							params = {
								vemail : email,
								servertype : 0,
								vpassword : password,
								vname : name,
								vtelephone: tel,
								vcompanyname: company,
								vqq: qq
							};
							//申请平台使用权限
							if(platformPower != null && platformPower != "") params["servertype"] = 5;
							$.getJSON(url, params, savecallback);
						}else{
							setDivInfo("passworddiv", i18n.t('activeRegist.enter6To32Character'), 1);
						}
				}
			}
	    });
		//checkname
//		check('name', 'namemessage');
//		var name = $("#name").attr("value");
//		if($.trim(name) == "") {
//			setDivInfo("namediv", "姓名不能为空", 1);
//			loadHide();
//			return;
//		} else {
//			if(name.length>30) {
//				setDivInfo("namediv", "最多可输入30个字符", 1);
//				loadHide();
//				return;
//			} else {
//				//=============checkemail start===============
//				check('email', 'emailmessage');
//				var email = $("#email").attr("value");
//				if(email != null && email != "") {
//					if(email.length > 50) {
//						setDivInfo("emaildiv", "请输入50字符内的邮箱地址", 1);
//					} else {
//						if(isEmail(email, $("#email"))) {
//							var vtimecc = Math.random();
//							var params = {
//								vemail : email,
//								servertype : 1,
//								timecc : vtimecc
//							};
//							$.ajax({
//								url:url,
//								data:params,
//								async:false,
//								success:function(data){
//									if(data != "0") {
//										setDivInfo("emaildiv", "此邮箱地址已被注册", 1);
//									} else {
//											if(checkpassword()){
//												loadShow();
//												var url = basePath + "servlet/RegisteServlet";
//												var params = {
//													vemail : email,
//													servertype : 0,
//													vpassword : password,
//													vname : name,
//												};
//												$.getJSON(url, params, savecallback);
//											}else{
//												setDivInfo("passworddiv", "请输入6~32位任意字符", 1);
//											}
//									}
//								}
//						    });
//						} else {
//							setDivInfo("emaildiv", "请输入邮箱地址，格式为abc@abc.com", 1);
//						}
//					}
//				}else{
//					setDivInfo("emaildiv", "请输入邮箱地址，格式为abc@abc.com", 1);
//				}
//			}
//		}
//		loadHide();
}




function initCookie() {
	loadHide();
	$('#activecode').removeAttr("disabled");
	pagename = i18n.t('activeRegist.loginPage');
// var tmpemail = getCookie("talkdataCookieEmail");
// var tmppassword = getCookie("talkdataCookiePassWord");
//
// if(tmpemail != null && tmpemail != "" && tmppassword != null && tmppassword
// != "" ) {
// document.getElementById("lemail").value = tmpemail;
// document.getElementById("lpassword").value = tmppassword;
// }
	copyheight();
}

function loadShow() {
	$('#loading').show();
}

function loadHide() {
	$('#loading').hide();
}

function shide(id) {
	hide(id + "message");
	hide(id + 'div');
}

function checkactivecode() {
	check('activecode', 'activecodemessage');
	acode = $('#activecode').val();
	if(acode.length > 0) {
		$('#v').show();
		var url = basePath + "servlet/RegisteServlet";
		var params = {
			servertype : 3,
			vtimecc : vtimecc,
			acode : acode
		};
		$.get(url, params, acodecallback);
	}
}

function acodecallback(data) {
	$('#v').hide();
	if(data == "success") {
		hide('activecodediv');
		$('#yy').css({
			'padding-bottom' : '30px'
		});
		$('#registerdiv').show();
		$('#activecode').attr("disabled", "true");
		copyheight();
	} else {
		$('#activecodediv').html("邀请码无效").show(200);
	}
}

var emailflag = false;
function checkemail() {
	check('email', 'emailmessage');
	var email = $("#email").attr("value");
	if(email != null && email != "") {
		if(email.length > 50) {
			setDivInfo("emaildiv", i18n.t('activeRegist.least50charEmailAddress'), 1);
		} else {
			if(isEmail(email, $("#email"))) {
				checkemailForServer(email);
			} else {
				setDivInfo("emaildiv", i18n.t('activeRegist.enterEmailAddressLikeThis'), 1);
			}
		}
	}
}

function checkemailForServer(email) {
	var url = basePath + "servlet/RegisteServlet";
	var vtimecc = Math.random();
	var params = {
		vemail : email,
		servertype : 1,
		timecc : vtimecc
	};
	$.get(url, params, checkemailcallback);
}

function checkemailcallback(data) {
	if(data != "0") {
		setDivInfo("emaildiv",i18n.t('activeRegist.thisEmailHasRegistered') , 1);
	} else {
		setDivInfo("emaildiv", "", 2);
		emailflag = true;
	}
}

var nameflag = false;
function checkname() {
	check('name', 'namemessage');
	var name = $("#name").attr("value");
	if(name == "") {
		return false;
	} else {
		if(!isName(name)) {
			setDivInfo("namediv", i18n.t('activeRegist.4to30Char'), 1);
			return false;
		} else {
			checknameforserver(name);
		}
	}
}

function checknameforserver(name) {
	var url = basePath + "servlet/RegisteServlet";
	var vtimecc = Math.random();
	var params = {
		vname : name,
		servertype : 2,
		timecc : vtimecc
	};
//	$.get(url, params, checknamecallback);
	$.ajax({
		async:false,
		url:url,
		data:params,
		success:checknamecallback
	});
}

function checknamecallback(data) {
	if(data != "0") {
		setDivInfo("namediv", i18n.t('activeRegist.thisUserNameHasUsed'), 1);
	} else {
//		setDivInfo("namediv", "", 2);
		nameflag = true;
	}
}

function checkpassword() {
	check('password', 'passwordmessage');
	var password = $("#password").attr("value");
	if(password == "") {
		return false;
	} else if(password.length < 6 || password.length > 32) {
		setDivInfo("passworddiv", i18n.t('activeRegist.enter6To32Character'), 1);
		return false;
	} else {
		setDivInfo("passworddiv", "", 2);
	}
	return true;
}

function checkcpassword() {
	check('cpassword', 'cpasswordmessage');
	var password = $("#password").attr("value");
	var cpassword = $("#cpassword").attr("value");
	if(password != "") {
		if(cpassword != password) {
			setDivInfo("cpassworddiv", i18n.t('activeRegist.TwoTimesPWDIsDifferent'), 1);
			return false;
		}
	} else {
		return false;
	}
	setDivInfo("cpassworddiv", "", 2);
	return true;
}

function checktelephone() {
	check('telephone', 'telephonemessage');
	var telephone = $("#telephone").attr("value");
	if(telephone != "") {
		if(!isMobileTel(telephone, $("#telephone"))) {
			setDivInfo("telephonediv", i18n.t('activeRegist.phoneTypeNotCorrect'), 1);
			return false;
		} else {
			setDivInfo("telephonediv", "", 2);
			return true;
		}
	} else {
		return false;
	}
}

function checkqq() {
	check('qq', 'qqmessage');
	var qq = $("#qq").attr("value");
	if(qq != "") {
		if(!isQQ(qq, $("#qq")) && !isMSN(qq)) {
			setDivInfo("qqdiv",i18n.t('activeRegist.QQTypeIsWrong') , 1);
			return false;
		}
	} else {
		return false;
	}
	setDivInfo("qqdiv", "", 2);
	return true;
}

function checkCompanyname() {
	check('companyname', 'companynamemessage');
	var companyname = $("#companyname").attr("value");
	if(companyname == "") {
		return false;
	} else {
		if(!isCompany(companyname)) {
			setDivInfo("companynamediv", i18n.t('activeRegist.1to50Char'), 1);
			return false;
		} else {
			setDivInfo("companynamediv", "", 2);
			return true;
		}
	}
}

function checkcontact() {
	check('contact', 'contactmessage');
	var contact = $("#contact").attr("value");
	if(contact == "") {
		return false;
	} else {
		if(!isContact(contact)) {
			setDivInfo("contactdiv", i18n.t('activeRegist.1to16Linkman'), 1);
			return false;
		} else {
			setDivInfo("contactdiv", "", 2);
			return true;
		}
	}
}


function savecallback(data) {
	loadHide();
	$("#registul").hide();
	if(data == "1") {
//		window.location.href = basePath + "webpage/CreatProduct.jsp";
		$(".Success").html('感谢关注TalkingData产品！<br />我们会尽快核实信息并与您联络，请关注您的注册邮箱，接收我们的回复。');
	} else if(data == "2") {
//		alert("邀请码已失效");
		$(".Success").html('邀请码已失效');
	} else {
//		alert("注册失败");
		$(".Success").html(i18n.t('activeRegist.registerFail'));
	}
	$(".txt").height("233px");
	$(".alert").height("325px");
	$(".content").height("400px");
	$("#tipregist").hide();
	$("#successalert").show();
}

function SetCookie(name, value)// 两个参数，一个是cookie的名子，一个是值
{
	var Days = 30;
	var exp = new Date();
	// new Date("December 31, 9998");
	exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
	document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
}

function getCookie(name)// 取cookies函数
{
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if(arr != null)
		return unescape(arr[2]);
	return null;
}

function delCookie(name)// 删除cookie
{
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval = getCookie(name);
	if(cval != null)
		document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}

function selectTag(showContent, selfObj) {
	// 操作标签
	var tag = document.getElementById("tags").getElementsByTagName("li");
	var taglength = tag.length;
	for( i = 0; i < taglength; i++) {
		tag[i].className = "";
	}
	selfObj.parentNode.className = "selectTag";
	// 操作内容
	for( i = 0; j = document.getElementById("tagContent" + i); i++) {
		j.style.display = "none";
	}
	document.getElementById(showContent).style.display = "block";
}

function setDivMessage(obj, flag) {
	hide(obj.id + 'message');
	var val = obj.value;
	var divid = obj.name + "div";
	if(val == null || val == "") {
//		document.getElementById(divid).style.display = "block";
		$("#"+divid).show();
//		document.getElementById(divid).className = "Prompty";
		switch(flag) {
			case 1:
//				document.getElementById(divid).innerHTML = emailmessage;
				$("#"+divid).html(emailmessage);
				break;
			case 2:
				document.getElementById(divid).innerHTML = passwordmessage;
				$("#"+divid).html(passwordmessage);
				break;
			case 3:
//				document.getElementById(divid).innerHTML = passwordmessage;
				$("#"+divid).html(passwordmessage);
				break;
			case 4:
//				document.getElementById(divid).innerHTML = telephonemessage;
				$("#"+divid).html(telephonemessage);
				break;
			case 5:
				document.getElementById(divid).innerHTML = username;
				$("#"+divid).html(qqmessage);
				break;
			case 6:
//				document.getElementById(divid).innerHTML = qqmessage;
				$("#"+divid).html(qqmessage);
				break;
			case 7:
//				document.getElementById(divid).innerHTML = companymessage;
				$("#"+divid).html(companymessage);
				break;
			case 8:
//				document.getElementById(divid).innerHTML = contactmessage;
				$("#"+divid).html(contactmessage);
				break;
			default:
//				document.getElementById(divid).innerHTML = emailmessage;
				$("#"+divid).html(emailmessage);
				break;
		}
	}
}

function setDivInfo(divid, message, flag) {
	switch(flag) {
		case 1:
//			document.getElementById(divid).className = "Prompt";
			$("#"+divid).attr("class","Prompt");
//			document.getElementById(divid).innerHTML = message;
			$("#"+divid).html(message);
//			document.getElementById(divid).style.display = "block";
			$("#"+divid).show();
			break;
		case 2:
//			document.getElementById(divid).style.display = "none";
			$("#"+divid).hide();
			break;
		default:
//			document.getElementById(divid).innerHTML = emailmessage;
			$("#"+divid).html(emailmessage);
//			document.getElementById(divid).style.display = "block";
		    $("#"+divid).show();
			break;
	}

}

function keyLogin() {
	var event = arguments.callee.caller.arguments[0] || window.event;
	// 消除浏览器差异
	if(event.keyCode == 13) {// 回车键的键值为13
		regist();
		$('#email').blur();
		$('#name').blur();
		$('#password').blur();
		// document.getElementById("input1").click(); //调用登录按钮的登录事件
	}
}
function closeAlert(){
	window.location.href="/index/regist.jsp";
}