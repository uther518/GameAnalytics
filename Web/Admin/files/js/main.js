 function PControl()
 {
   var url;
   var appUrl;
   var temp;
   var pop_max_index;
   var windowHeight;
   var scrollTop;
 };
 PControl.prototype.ini = function()
 {
   this.url='admin.php';
   this.temp='';
   this.pop_max_index=10;
   this.windowHeight=0;
   this.scrollTop=0;
 };
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//公用
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//-------------------------------------------
//页面就绪
//-------------------------------------------
 PControl.prototype.domReady = function()
 {
 }
 //-------------------------------------------
 //消息框
 //-------------------------------------------
 PControl.prototype.messageBox = function(title,content)
 {
	$('#message_box_title span').html(title);
	$('#message_box_content').html(content);
	$('#message_box_bg').css('display','block');
 };
 //-------------------------------------------
 //消息框
 //-------------------------------------------
 PControl.prototype.closeMessageBox = function()
 {
	$('#message_box_bg').css('display','none');
 };
//-------------------------------------------
//隐藏
//-------------------------------------------
 PControl.prototype.hideDiv = function(id)
 {
	$('#'+id).css('display','none');
 };
//-------------------------------------------
//显示
//-------------------------------------------
 PControl.prototype.showDiv = function(id)
 {
	$('#'+id).css('display','');
 };
//-------------------------------------------
//跳转
//-------------------------------------------
 PControl.prototype.toUrl = function(url)
 {
	window.location.href=url;
 };
//-------------------------------------------
//重载
//-------------------------------------------
 PControl.prototype.windowReload = function()
 {
	window.location.reload();
 }; 
//-------------------------------------------
//重载
//-------------------------------------------
 PControl.prototype.historyGo = function(val)
 {
	window.history.go(val);
 }; 
//-------------------------------------------
//打开链接
//-------------------------------------------
 PControl.prototype.openURI = function(uri)
 {
	window.location.href=encodeURI(uri);
 }; 
//-------------------------------------------
//关闭弹出窗
//-------------------------------------------
 PControl.prototype.closeWindow = function(id)
 {
	$('#pop_window_'+id).remove();
 };
//-------------------------------------------
//窗口置顶
//-------------------------------------------
 PControl.prototype.setWindowTop = function(id)
 {
	$('#pop_window_'+id).css('z-index',++this.pop_max_index);
 };
//-------------------------------------------
//弹出窗口
//-------------------------------------------
 PControl.prototype.popWindow = function(id,tryagain,left,top)
 {
 	 $('body').append("<div class='pop_window' id='pop_window_"+id+"' >载入中...  <a href='javascript:void(0);' class='pop_window_op' onclick='"+tryagain+"'>重试</a><a href='javascript:void(0);' class='pop_window_op' onclick='__oa.closeWindow(\""+id+"\")'>关闭</a></div>");
	 $('#pop_window_'+id).css('z-index',++this.pop_max_index);
	 $('#pop_window_'+id).css('left',left);
	 $('#pop_window_'+id).css('top',top);
 };
//-------------------------------------------
//停止冒泡
//------------------------------------------- 
 PControl.prototype.cancelBubble = function()
 {
	var ev = window.event || arguments.callee.caller.arguments[0];
    if (window.event) 
	{ev.cancelBubble = true;}
    else 
	{ev.stopPropagation();}
 }
//-------------------------------------------
//通知
//-------------------------------------------
 PControl.prototype.setNotification = function(content)
 {
	this.messageBox( '处理中...' , content );
 };
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//用户管理
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//-------------------------------------------
//删除用户
//-------------------------------------------
PControl.prototype.deleteUser = function(userId)
 {
	 if(!confirm('确定要删除用户?(ID'+userId+')')){return false;}
	 this.setNotification('处理中...');
	 $.ajax({
		   type:"POST",
		   url:this.url+'?f=user&do=delete&input_uid='+userId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//发送动作
//-------------------------------------------
PControl.prototype.sendAction = function(type)
 {
	 var userId = $('#userId').text();
	 var sAction = $('#sAction').val();
	 var sObject = $('#sObject').val();
	 var sValue = $('#sValue').val();
	 if( !userId || !sAction || !sObject || !sValue || !type ){alert('参数不完整');return false;}
	 this.setNotification('处理中...');
	 $.ajax({
		   type:"POST",
		   url:this.url+'?f=user&do=send&input_uid='+userId,
		   data:{type:type,sAction:sAction,sObject:sObject,sValue:sValue},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//修改任务
//-------------------------------------------
PControl.prototype.editUserTask = function(type)
 {
	 var userId = $('#userId').text();
	 var taskId = $('#taskId').val();
	 if( !userId || !taskId || !type ){alert('参数不完整');return false;}
	 this.setNotification('处理中...');
	 $.ajax({
		   type:"POST",
		   url:this.url+'?f=user&do=task&input_uid='+userId,
		   data:{type:type,taskId:taskId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//背包管理
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//-------------------------------------------
//修改装备
//-------------------------------------------
 PControl.prototype.updateEquipment = function(bagId)
 {
	var uid = $('#userId').text();
	var isUsing = $('#isUsing_'+bagId).val();
	var color = $('#color_'+bagId).val();
	var enchantLevel = $('#enchantLevel_'+bagId).val();
	var slotStatus1 = $('#slotStatus1_'+bagId).val();
	var slotStatus2 = $('#slotStatus2_'+bagId).val();
	var slotStatus3 = $('#slotStatus3_'+bagId).val();
	var slot1 = $('#slot1_'+bagId).val();
	var slot2 = $('#slot2_'+bagId).val();
	var slot3 = $('#slot3_'+bagId).val();
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=bag&do=update&type=equipment&input_uid='+uid,
		   data:{bagId:bagId,isUsing:isUsing,color:color,enchantLevel:enchantLevel,slotStatus1:slotStatus1,slotStatus2:slotStatus2,slotStatus3:slotStatus3,slot1:slot1,slot2:slot2,slot3:slot3},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
//-------------------------------------------
//删除装备
//-------------------------------------------
PControl.prototype.deleteEquipment = function(bagId)
 {
	 if($('#isUsing_'+bagId).val()==1){alert('装备正在使用中无法删除，请先卸下装备');return false;}
	 if(!confirm('确定要删除装备?(位置'+bagId+')')){return false;}
	 var uid = $('#userId').text();
	 this.setNotification('处理中...');
	 $.ajax({
		   type:"POST",
		   url:this.url+'?f=bag&do=delete&type=equipment&input_uid='+uid,
		   data:{bagId:bagId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增装备
//-------------------------------------------
 PControl.prototype.addEquipment = function()
 {
	var uid = $('#userId').text();
	var itemId = $('#eq_itemId').val();
	var isUsing = $('#eq_isUsing').val();
	var color = $('#eq_color').val();
	var enchantLevel = $('#eq_enchantLevel').val();
	if( !uid || !enchantLevel || !itemId || !isUsing || !color ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=bag&do=add&type=equipment&input_uid='+uid,
		   data:{itemId:itemId,isUsing:isUsing,color:color,enchantLevel:enchantLevel},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//修改物品
//-------------------------------------------
 PControl.prototype.updateItem = function(itemId)
 {
	var uid = $('#userId').text();
	var number = $('#number_'+itemId).val();
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=bag&do=update&type=item&input_uid='+uid,
		   data:{itemId:itemId,number:number},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
//-------------------------------------------
//删除物品
//-------------------------------------------
PControl.prototype.deleteItem = function(itemId)
 {
	 if(!confirm('确定要删除物品?(ID:'+itemId+')')){return false;}
	 var uid = $('#userId').text();
	 this.setNotification('处理中...');
	 $.ajax({
		   type:"POST",
		   url:this.url+'?f=bag&do=delete&type=item&input_uid='+uid,
		   data:{itemId:itemId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增物品
//-------------------------------------------
 PControl.prototype.addItem = function()
 {
	var uid = $('#userId').text();
	var itemId = $('#item_id').val();
	var number = $('#item_num').val();
	if( !uid || !itemId || !number ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=bag&do=add&type=item&input_uid='+uid,
		   data:{itemId:itemId,number:number},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//海域管理
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//-------------------------------------------
//刷新所有怪物
//-------------------------------------------
 PControl.prototype.refreshMapMonster = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	if( !uid || !mapId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=refresh&input_uid='+uid+'&map_id='+mapId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增被打劫残像
//-------------------------------------------
 PControl.prototype.addLootRecord = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	var looterId = $('#r_looterId').val();
	var itemId = $('#r_itemId').val();
	var gold = $('#r_gold').val();
	if( !uid || !mapId || !looterId ){alert('参数不完整');return false;}
	if( !gold && !itemId ){alert('参数不完整');return false;}
	if( uid==looterId ){alert('不能打劫自己');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=add&type=record&input_uid='+uid+'&map_id='+mapId,
		   data:{looterId:looterId,itemId:itemId,gold:gold},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//更新被打劫残像
//-------------------------------------------
 PControl.prototype.updateLootRecord = function()
 {
	
 };
//-------------------------------------------
//删除被打劫残像
//-------------------------------------------
 PControl.prototype.deleteLootRecord = function()
 {
	if(!confirm('确定要清空残像?')){return false;}
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	if( !uid || !mapId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=delete&type=record&input_uid='+uid+'&map_id='+mapId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增被打劫记录
//-------------------------------------------
 PControl.prototype.addLootList = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	var looterId = $('#l_looterId').val();
	var itemId = $('#l_itemId').val();
	var gold = $('#l_gold').val();
	if( !uid || !mapId || !looterId ){alert('参数不完整');return false;}
	if( !gold && !itemId ){alert('参数不完整');return false;}
	if( uid==looterId ){alert('不能打劫自己');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=add&type=list&input_uid='+uid+'&map_id='+mapId,
		   data:{looterId:looterId,itemId:itemId,gold:gold},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//更新被打劫记录
//-------------------------------------------
 PControl.prototype.updateLootList = function()
 {

 };
//-------------------------------------------
//删除被打劫记录
//-------------------------------------------
 PControl.prototype.deleteLootList = function()
 {
	if(!confirm('确定要清空记录?')){return false;}
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	if( !uid || !mapId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=delete&type=list&input_uid='+uid+'&map_id='+mapId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };

//-------------------------------------------
//更新推荐列表获取时间
//-------------------------------------------
 PControl.prototype.updateRecommendTime = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	var time = $('#rec_getTime').val();
	if( !uid || !mapId || !time ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=update&type=time&input_uid='+uid+'&map_id='+mapId,
		   data:{time:time},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//更新推荐打劫金币用户ID串
//-------------------------------------------
 PControl.prototype.updateRecommendGoldList = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	var goldUserIds = $('#rec_goldUserIds').val();
	if( !uid || !mapId || !goldUserIds ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=update&type=gold&input_uid='+uid+'&map_id='+mapId,
		   data:{goldUserIds:goldUserIds},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增推荐打劫列表
//-------------------------------------------
 PControl.prototype.addRecommendLootList = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	var itemId = $('#rec_itemId').val();
	var userIds = $('#rec_userIds').val();
	if( !uid || !mapId || !userIds || !itemId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=add&type=recommend&input_uid='+uid+'&map_id='+mapId,
		   data:{userIds:userIds,itemId:itemId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//更新推荐打劫列表
//-------------------------------------------
 PControl.prototype.updateRecommendLootList = function()
 {
 };
//-------------------------------------------
//删除推荐打劫列表
//-------------------------------------------
 PControl.prototype.deleteRecommendLootList = function( itemId )
 {
	if(!confirm('确定要删除记录?(道具ID:'+itemId+')')){return false;}
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	if( !uid || !mapId || !itemId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=delete&type=recommend&input_uid='+uid+'&map_id='+mapId,
		   data:{ itemId:itemId },
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//根据竞技场积分查找可打劫人数
//-------------------------------------------
PControl.prototype.searchBeLootNumber = function()
{
	areaScore = $( '#searchArenaScore' ).val();
	if( !areaScore ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		type:"POST",
		url:this.url + '?f=arena&do=searchBeLootNumber' ,
		data: {searchArenaScore:areaScore},
		success:function( data )
		{
			$( '#beLootNumber' ).val( data );
			_p.closeMessageBox();
		},
		error:function(){alert('操作失败 请重试');}
	}); 
};
//-------------------------------------------
//新增同地图好友
//-------------------------------------------
 PControl.prototype.addSameMapFriend = function()
 {
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	var friendId = $('#friendId').val();
	var friendLevel = $('#friendLevel').val();
	friendLevel = friendLevel?friendLevel:1;
	var friendInteractTime = $('#friendInteractTime').val();
	friendInteractTime = friendInteractTime?friendInteractTime:'';
	if( !uid || !mapId || !friendId  ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=add&type=friend&input_uid='+uid+'&map_id='+mapId,
		   data:{friendId:friendId,friendLevel:friendLevel,friendInteractTime:friendInteractTime},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//删除同地图好友
//-------------------------------------------
/* PControl.prototype.deleteSameMapFriend = function( friendId )
 {
	if(!confirm('确定要删除记录?(用户ID:'+friendId+')')){return false;}
	var uid = $('#userId').text();
	var mapId = $('#mapId').text();
	if( !uid || !mapId || !friendId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=map&do=delete&type=friend&input_uid='+uid+'&map_id='+mapId,
		   data:{ friendId:friendId },
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//副本管理
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 //-------------------------------------------
//刷新所有怪物
//-------------------------------------------
 PControl.prototype.refreshCopyMonster = function()
 {
	var leaderId = $('#leaderId').text();
	if( !leaderId  ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=refresh&input_uid='+leaderId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增被打劫残像
//-------------------------------------------
 PControl.prototype.addCopyLootRecord = function()
 {
	var userId = $('#r_userId').val();
	var looterId = $('#r_looterId').val();
	var itemId = $('#r_itemId').val();
	var gold = $('#r_gold').val();
	if( !userId || !looterId  ){alert('参数不完整');return false;}
	if( !gold && !itemId ){alert('参数不完整');return false;}
	if( userId==looterId ){alert('不能打劫自己');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=add&type=record&input_uid='+userId,
		   data:{looterId:looterId,itemId:itemId,gold:gold},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };

//-------------------------------------------
//删除被打劫残像
//-------------------------------------------
 PControl.prototype.deleteCopyLootRecord = function(userId)
 {
	if(!confirm('确定要清空残像?(用户ID:'+userId+')')){return false;}
	if( !userId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=delete&type=record&input_uid='+userId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增被打劫记录
//-------------------------------------------
 PControl.prototype.addCopyLootList = function()
 {
	var userId = $('#l_userId').val();
	var looterId = $('#l_looterId').val();
	var itemId = $('#l_itemId').val();
	var gold = $('#l_gold').val();
	if( !userId || !looterId ){alert('参数不完整');return false;}
	if( !gold && !itemId ){alert('参数不完整');return false;}
	if( userId==looterId ){alert('不能打劫自己');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=add&type=list&input_uid='+userId,
		   data:{looterId:looterId,itemId:itemId,gold:gold},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };

//-------------------------------------------
//删除被打劫记录
//-------------------------------------------
 PControl.prototype.deleteCopyLootList = function(userId)
 {
	if(!confirm('确定要清空记录?(用户ID:'+userId+')')){return false;}
	if( !userId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=delete&type=list&input_uid='+userId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };

//-------------------------------------------
//更新推荐列表获取时间
//-------------------------------------------
 PControl.prototype.updatCopyeRecommendTime = function( userId )
 {
	var time = $('#rec_getTime_'+userId).val();
	if( !userId || !time ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=update&type=time&input_uid='+userId,
		   data:{time:time},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//更新推荐打劫金币用户ID串
//-------------------------------------------
 PControl.prototype.updateCopyRecommendGoldList = function( userId )
 {
	var goldUserIds = $('#rec_goldUserIds_'+userId).val();
	if( !userId || !goldUserIds ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=update&type=gold&input_uid='+userId,
		   data:{goldUserIds:goldUserIds},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增推荐打劫列表
//-------------------------------------------
 PControl.prototype.addCopyRecommendLootList = function()
 {
	var userId = $('#rec_userId').val();
	var userIds = $('#rec_userIds').val();
	var itemId = $('#rec_itemId').val();
	if( !userId || !userIds || !itemId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=add&type=recommend&input_uid='+userId,
		   data:{userIds:userIds,itemId:itemId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//删除推荐打劫列表
//-------------------------------------------
 PControl.prototype.deleteCopyRecommendLootList = function( userId , itemId )
 {
	if(!confirm('确定要删除记录?(用户ID:'+userId+',道具ID:'+itemId+')')){return false;}
	if( !userId || !itemId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=delete&type=recommend&input_uid='+userId,
		   data:{ itemId:itemId },
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增打怪顺序列表
//-------------------------------------------
 PControl.prototype.addCopyMonsterList = function()
 {
	var userId = $('#mon_userId').val();
	var point = $('#mon_monsterPoint').val();
	var result = $('#mon_result').val();
	if( !userId || !point || !result ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=add&type=monster&input_uid='+userId,
		   data:{point:point,result:result},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };

//-------------------------------------------
//删除打怪顺序列表
//-------------------------------------------
 PControl.prototype.deleteCopyMonsterList = function()
 {
	if(!confirm('确定要清空打怪残像?')){return false;}
	var leaderId = $('#leaderId').text();
	if( !leaderId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=copy&do=delete&type=monster&input_uid='+leaderId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
//-------------------------------------------
//新增技能列表
//-------------------------------------------
 PControl.prototype.addSkill = function()
 {
	var userId = $('#userId').text();
	var skillId = $('#skillId').val();

	if( !userId || !skillId || skillId == 0 ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=addSkill&input_uid='+userId,
		   data:{skillId:skillId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 //-------------------------------------------
//更新默认技能序列
//-------------------------------------------
 PControl.prototype.updateDefaultOrder = function()
 {
	var userId = $('#userId').text();
	var defaultOrderId = $('#defaultOrderId').val();

	if( !userId || !defaultOrderId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=updateDefaultOrder&input_uid='+userId,
		   data:{defaultOrderId:defaultOrderId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//修改技能序列长度
//-------------------------------------------
 PControl.prototype.updateOrderLength = function()
 {
	var userId = $('#userId').text();
	var orderLength = $('#orderLength').val();

	if( !userId || !orderLength ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=updateOrderLength&input_uid='+userId,
		   data:{orderLength:orderLength},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 //-------------------------------------------
//更新技能序列
//-------------------------------------------
 PControl.prototype.updateOrderList = function( orderId )
 {
	var userId = $('#userId').text();
	var skillList = $('#order'+orderId).val();

	if( !userId || !skillList ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=updateOrderList&input_uid='+userId,
		   data:{orderId:orderId,skillList:skillList},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//删除技能序列
//-------------------------------------------
 PControl.prototype.deleteOrderList = function( orderId )
 {
	var userId = $('#userId').text();

	if( !userId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=deleteOrderList&input_uid='+userId,
		   data:{orderId:orderId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
//-------------------------------------------
//新增 技能序列
//-------------------------------------------
 PControl.prototype.addOrderList = function()
 {
	var userId = $('#userId').text();
	var newOrderId = $('#newOrderId').val();
	if( !userId  ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=addOrderList&input_uid='+userId,
		   data:{newOrderId:newOrderId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };

//-------------------------------------------
//新增 技能序列
//-------------------------------------------
 PControl.prototype.deleteSkill = function( skillId )
 {
	var userId = $('#userId').text();
	if( !userId || !skillId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=deleteSkill&input_uid='+userId,
		   data:{skillId:skillId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
 PControl.prototype.reduceSkillLevel = function( skillId )
 {
	var userId = $('#userId').text();
	if( !userId || !skillId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=reduceSkillLevel&input_uid='+userId,
		   data:{skillId:skillId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
 PControl.prototype.deleteAllSkill = function( skillId )
 {
	var userId = $('#userId').text();
	if( !userId ){alert('参数不完整');return false;}
	if(!confirm('确定要删除全部技能?')){return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=deleteAllSkill&input_uid='+userId,
		   data:{skillId:skillId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
 PControl.prototype.resetSkill = function( skillId )
 {
	var userId = $('#userId').text();
	if( !userId ){alert('参数不完整');return false;}
	if(!confirm('确定重置全部技能点?')){return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=resetSkill&input_uid='+userId,
		   data:{skillId:skillId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
 PControl.prototype.studyAllSkill = function( skillId )
 {
	var userId = $('#userId').text();
	var skillLevel = $('#skillLevel').val();
	if( !userId || !skillLevel ){alert('参数不完整');return false;}
	if(!confirm('确定要学习全部技能?')){return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=studyAllSkill&input_uid='+userId,
		   data:{skillLevel:skillLevel},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
 PControl.prototype.addSkillLevel = function( skillId )
 {
	var userId = $('#userId').text();
	if( !userId || !skillId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=skill&do=addSkillLevel&input_uid='+userId,
		   data:{skillId:skillId},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
 };
 
//-------------------------------------------
//建筑
//-------------------------------------------
PControl.prototype.addBuilding = function()
{
	var userId = $('#userId').val();
	var buildingId = $('#buildingId').val();
	var level = $('#building_level').val();
	if( !userId || !buildingId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
	   type:"POST",
	   url:this.url+'?f=building&do=add&input_uid='+userId,
	   data:{buildingId:buildingId,level:level},
	   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
	   error:function(){alert('操作失败 请重试');}
	}); 
};
PControl.prototype.updateBuilding = function( id )
{
	var userId = $('#userId').val();
	var level = $('#building_' + id ).val();
	if( !userId || !id ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
	   type:"POST",
	   url:this.url+'?f=building&do=update&input_uid='+userId,
	   data:{buildingId:id,level:level},
	   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
	   error:function(){alert('操作失败 请重试');}
	}); 
};
PControl.prototype.deleteBuilding = function( id )
{
	var userId = $('#userId').val();
	if( !userId || !id ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
	   type:"POST",
	   url:this.url+'?f=building&do=delete&input_uid='+userId,
	   data:{buildingId:id},
	   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
	   error:function(){alert('操作失败 请重试');}
	}); 
};
//-------------------------------------------
//催熟领地订单
//-------------------------------------------
PControl.prototype.toFinishTerritoryOrderTime = function( territory )
{
	var userId = $('#userId').val();
	if( !userId || !territory ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
	   type:"POST",
	   url:this.url+'?f=building&do=toFinishTerritoryOrderTime&input_uid='+userId,
	   data:{buildingId:territory},
	   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
	   error:function(){alert('操作失败 请重试');}
	}); 
};
PControl.prototype.jumpTask = function( taskId )
{
	var userId = $('#userId').text();
	if( !userId || !taskId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
	   type:"POST",
	   url:this.url+'?f=user&do=jumpTask&input_uid='+userId,
	   data:{taskId:taskId},
	   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
	   error:function(){alert('操作失败 请重试');}
	}); 
};
//解锁大关
PControl.prototype.unlockBigStage = function( bigStageId )
{
	var userId = $('#userId').text();
	if( !userId || !bigStageId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
	   type:"POST",
	   url:this.url+'?f=map&do=unlockBigStage&input_uid='+userId,
	   data:{bigStageId:bigStageId},
	   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
	   error:function(){alert('操作失败 请重试');}
	}); 
};


//对地图加锁
PControl.prototype.lockMap = function( mapId )
{
	if( !confirm( '确定要删除记录?（Map ID：' + mapId + '）' ) )
	{
		return false;
	}
	var uid = $( '#userId' ).text();
	if( !uid || !mapId )
	{
		alert( '参数不完整' );
		return false;
	}
	this.setNotification( '处理中...' );
	$.ajax(
		{
			type : "POST",
			url: this.url + '?f=map&do=lockMap&input_uid=' + uid + '&mapId=' + mapId ,
			data : {},
			success : function(echo)
			{
				if( echo != '' )
					alert(echo);
				window.location.reload();
			},
			error : function()
			{
				alert( '操作失败 请重试' );
			}
		}
	);
};

//对地图解锁
PControl.prototype.unlockMap = function()
{
	var uid = $( '#userId' ).text();
	var mapId = $( '#unlockMapId' ).val();
	if( !uid || !mapId )
	{
		alert( '参数不完整' );
		return false;
	}
	this.setNotification( '处理中...' );
	$.ajax(
		{
			type : "POST",
			url: this.url + '?f=map&do=unlockMap&input_uid=' + uid + '&mapId=' + mapId ,
			data : {},
			success : function(echo)
			{
				if( echo != '' )
					alert(echo);
				window.location.reload();
			},
			error : function()
			{
				alert( '操作失败 请重试' );
			}
		}
	);
};

//更新水印线
PControl.prototype.updateWaterLevel = function()
{
	var waterLevel = $( '#txtLootServerWaterLevel' ).val();
	if( !waterLevel )
	{
		alert( '参数不完整' );
		return false;
	}
	this.setNotification( '处理中...' );
	$.ajax(
		{
			type : "POST",
			url: this.url + '?f=arena&do=updateWaterLevel&waterLevel=' + waterLevel ,
			data : {},
			success : function(echo)
			{
				if( echo != '' )
					alert(echo);
				window.location.reload();
			},
			error : function()
			{
				alert( '操作失败 请重试' );
			}
		}
	);
};

//更新打劫服务器配置
PControl.prototype.updateLootServerConfig = function()
{
	var topWaterLevel = $( '#txtLootServerTopWaterLevel' ).val();
	var bottomWaterLevel = $( '#txtLootServerBottomWaterLevel' ).val();
	var safeTime = $( '#txtSafeTime' ).val();
	var safeTimeClearFlag = $( '#txtSafeTimeClearFlag' ).val();
	if( !safeTimeClearFlag.length || !safeTime || !topWaterLevel || !bottomWaterLevel )
	{
		alert( '参数不完整' );
		return false;
	}
	this.setNotification( '处理中...' );
	$.ajax(
		{
			type : "POST",
			url: this.url + "?f=arena&do=setServerConfig&topWaterLevel=" + topWaterLevel + "&bottomWaterLevel=" + bottomWaterLevel + "&safeTime=" + safeTime + "&isClearSafeTimeAtLogin=" + ( safeTimeClearFlag == 1 ? "1" : "0" ) ,
			data : {},
			success : function(echo)
			{
				if( echo != '' )
					alert(echo);
				window.location.reload();
			},
			error : function()
			{
				alert( '操作失败 请重试' );
			}
		}
	);
};

//使用户登录到打劫服务器
PControl.prototype.toLoginLootServer = function()
{
	var userIds = $( '#txtToLoginServerUserIds' ).val();
	if( !userIds )
	{
		alert( '参数不完整' );
		return false;
	}
	this.setNotification( '处理中...' );
	$.ajax(
			{
				type : "POST",
				url: this.url + "?f=arena&do=toLoginServer" ,
				data : { "userIds" : userIds },
				success : function(echo)
				{
					if( echo != '' )
						alert(echo);
					window.location.reload();
				},
				error : function()
				{
					alert( '操作失败 请重试' );
				}
			}
	);
};
PControl.prototype.searchRank = function()
{
	this.setNotification( '处理中...' );
	$.ajax(
			{
				type : "POST",
				url: this.url + "?f=seasonArena&do=rankTop" ,
				success : function(echo)
				{
					if( echo != '' )
						$('#rankTop').html(echo);
					_p.closeMessageBox();
				},
				error : function()
				{
					alert( '操作失败 请重试' );
				}
			}
	);
}
PControl.prototype.searchSeasonArenaNum = function()
{
	$.ajax(
			{
				type : "POST",
				url: this.url + "?f=seasonArena&do=getNum" ,
				success : function(echo)
				{
					if( echo != '' )
						$('#beLootNumber').html(echo);
				},
				error : function()
				{
					alert( '操作失败 请重试' );
				}
			}
	);
};

//-------------------------------------------
//添加船上NPC
//-------------------------------------------
PControl.prototype.addShipNpc = function()
{
	var npcId = $('#txtNpcId').val();
	var userId = $('#userId').text();
	if( !userId || !npcId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=ship&do=addNpcId&input_uid='+userId + '&npcId='+ npcId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
};

//-------------------------------------------
//添加船上NPC
//-------------------------------------------
PControl.prototype.addShipNpc = function()
{
	var npcId = $('#txtNpcId').val();
	var userId = $('#userId').text();
	if( !userId || !npcId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=ship&do=addNpcId&input_uid='+userId + '&npcId='+ npcId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
};

//-------------------------------------------
//删除船上NPC
//-------------------------------------------
PControl.prototype.removeShipNpc = function( npcId )
{
	var userId = $('#userId').text();
	if( !userId || !npcId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=ship&do=removeNpcId&input_uid='+userId + '&npcId='+ npcId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
};
//-------------------------------------------
//添加船上NPC
//-------------------------------------------
PControl.prototype.addShipNpc = function()
{
	var npcId = $('#txtNpcId').val();
	var userId = $('#userId').text();
	if( !userId || !npcId ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=ship&do=addNpcId&input_uid='+userId + '&npcId='+ npcId,
		   data:{},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
};

//-------------------------------------------
//根据实力积分查询埋伏的人数
//-------------------------------------------
PControl.prototype.searchAmbushNumber = function()
{
	fightScore = $( '#searchFightScore' ).val();
	if( !fightScore ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		type:"POST",
		url:this.url + '?f=tackle&do=searchAmbushNumber' ,
		data: {searchFightScore:fightScore},
		success:function( data )
		{
			$( '#ambushNumber' ).val( data );
			_p.closeMessageBox();
		},
		error:function(){alert('操作失败 请重试');}
	}); 
};

//-------------------------------------------
//显示获得平台额外奖励的用户ID
//-------------------------------------------
PControl.prototype.showAwardUsers = function()
{
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=user&do=show' ,
		   data:{},
		   success:function( data )
		   {
			   $( '#awardUserIds' ).val( data );
				_p.closeMessageBox();
		   },
		   error:function(){alert('操作失败 请重试');}
		   }); 
};

//-------------------------------------------
//获得平台奖励的用户
//-------------------------------------------
PControl.prototype.addAwardItem = function()
{
	var userIds = $('#awardUserIds').val();
	var itemId = $('#item_id').val();
	var number = $('#item_num').val();
	if( !itemId || !number ){alert('参数不完整');return false;}
	this.setNotification('处理中...');
	$.ajax({
		   type:"POST",
		   url:this.url+'?f=user&do=toAward' ,
		   data:{itemId:itemId,number:number,userIds:userIds},
		   success:function(echo){if(echo!='')alert(echo);window.location.reload();},
		   error:function(){alert('操作失败 请重试');}
		   }); 
};
var _p=new PControl();
 _p.ini();
//就绪
 $(document).ready(function(){_p.domReady();});
//$(window).resize();
//$(window).scroll();
/*******************************************************************/
