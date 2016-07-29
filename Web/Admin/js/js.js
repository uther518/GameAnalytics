// JavaScript Document
(function(){
	addEvent(window,"load",inite);
})();
function inite(){
	menuflash("menu");
	input();
}
function input(){
	var input= $("input");
	for(var i=0; i<input.length; i++){
		if($(input[i]).val()!="")$(input[i]).siblings("label").hide();
		$("label:eq("+i+")").click(function(){
			$(this).siblings("input").focus();
		});
		$(input[i]).focus(function(){
			$(this).addClass("hover");
			$(this).siblings("label").fadeOut();
		});
		$(input[i]).blur(function(){
			$(this).removeClass("hover");
			if($(this).val()!="")$(this).siblings("label").fadeOut();
			else $(this).siblings("label").fadeIn();
		});
	}
}
function menuflash(o,pScrollY){		
	if(pScrollY==null) pScrollY=0;
	var obj=$id(o),
		scrollY=document.documentElement.scrollTop || document.body.scrollTop,
		moveTop =0.1 * (scrollY - pScrollY);
		moveTop = (moveTop > 0) ? Math.ceil(moveTop) : Math.floor(moveTop);
		obj.style.top = parseInt(getStyle(obj,"top")) + moveTop + "px";
		pScrollY = pScrollY + moveTop;
		setTimeout("menuflash('"+o+"',"+pScrollY+")",50);
}