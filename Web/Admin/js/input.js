function input(focus, h) {
	hide(h);
	$('#'+focus).focus();
}
function hide(id) {
	$('#'+id).hide(200);
}
function show(id) {
	$('#'+id).show(200);
}
function check(focus, s) {
	var v = $('#'+focus).val();
	if(v == "undefined") {
		$('#'+focus).val("");
		show(s);
	}
	if(v == "" || v == null){
		show(s);
	}
}