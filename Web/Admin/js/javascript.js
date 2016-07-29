// JavaScript Document
// JavaScript Document

//事件监听事件函数

function addEvent(obj,evt,fn){
	if(obj.addEventListener){
		obj.addEventListener(evt,fn,true);
		return obj;
	}
	if(!obj._fns)obj._fns={};
	if(!obj._fns[evt])obj._fns[evt]=[];
	var arryfn=obj._fns[evt];
	for(var i=0; i<arryfn.length; i++){
		if(arryfn[i]==fn)return obj;
	}
	arryfn.push(fn);
	fn._Index=arryfn.length-1;
	if(typeof obj["on"+evt]=="function"){
		if(obj["on"+evt]!=handler)arryfn.push(obj["on"+evt]);
	}
	obj["on"+evt]=handler;
	return obj;
}
function handler(){
	var evttype=window.event.type,
		arryfn=this._fns[evttype];
	for(var i=0; i<arryfn.length; i++){
		arryfn[i].call(this);
	}
}

//删除事件函数
function delEvent(obj,evt,fn){
	if(obj.removeEventListener){
		obj.removeEventListener(evt,fn,true);
		return obj;
	}
	var fns=obj._fns;
	if(fns!=null){    
		fns=fns[evt];
		if(fns!=null){  
		fns.splice(fn._Index,1);
			/*for(var i=0; i<_fns_.length;i++){
				if(_fns_[i]==fn){		
					delete _fns_[i];
				}
			}*/
		}
	}
	return obj;
}

//id获取
function $id(id){
	return document.getElementById(id);
}
//class类名获取元素
function $Class(classNames,context){
	context=context||document;
	if(context.getElementsByClassName){
		return context.getElementsByClassName(classNames);
	}
	var nodes=context.getElementsByTagName("*"),
		ret=[];
	for(var i = 0; i<nodes.length; i++){
		if(hasClass(nodes[i],classNames)) ret.push(nodes[i]);
	}
	return ret;
}

function hasClass(nodes,classNames){
	var names=nodes.className.split(/\s+/);
	for(var i=0; i<names.length;i++){
		if(names[i]==classNames)return true;
		return false;
	}
}

//获取样式函数
function getStyle(obj,css){   
	if(window.getComputedStyle){  //W3C DOM 获取css样式
		return window.getComputedStyle(obj,null)[css];   //第一个参数为检测对象，第二个参数为null预留，未来实现功能
	}else if(obj.currentStyle){   //IE获取css样式
		return obj.currentStyle[css];
	}
	return obj;
}


//删除class
function delclass(nodes,classNames){
	var node=nodes
		names=nodes.className.split(/\s+/);
		for(var i=0;i<names.length;i++){
			if(names[i]===classNames)names.splice(i,1);
		}
		node.className=names.join(" ");
}

