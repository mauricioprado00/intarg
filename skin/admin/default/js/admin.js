function habilitar_ajax(base_url){
	//alert("ajaxon"+base_url);
	var url = document.location.href.toString().split('onajax/').join('').split('noajax/').join('').split(base_url);
	url = url[1].split('#')[0];
	while(url[0]=='/')
		url = url.slice(1);
	if(url!='')
		url = '#'+url;
	url = base_url+'/onajax/'+url;
	document.location.href=url;
}
function deshabilitar_ajax(base_url){
	url = document.location.href.toString().split('onajax/').join('').split('noajax/').join('').split('#');
	while(url[0][url[0].length-1]=='/')
		url[0] = url[0].slice(0,url[0].length-1);
	url = url.join('/noajax/');
	document.location.href = url;
		;
}
function tabpanelfocus(el){
	if(el.jquery==null)
		el = jQuery(el);
	el.parents('.ui-tabs-panel:first').each(function(){
		var id = jQuery(this).attr('id');
		jQuery('[href=#'+id+']').trigger('click');
	})
	return;
}