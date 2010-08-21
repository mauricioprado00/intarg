jQuery(document).ready(function(){
	jQuery('.pageloader>.desarrollo').ScreenBlock({close_button:false});
	var base_url = UrlHelper.getResourceUrl('');
	var ajax_options = {
		type: "GET",
		url: base_url,
		//data: {},
		success: function(data){
			var la = jQuery('.loadarea');
			la.css('visibility','hidden').html(data);
			setTimeout(function(){
				jQuery(document).ready(function(){
					jQuery.ScreenBlock(false);
					jQuery('.pageloader').remove();
					la.css('visibility','visible');
				});
			}, 1);
		},
		beforeSend: function(xhr){
			xhr.setRequestHeader('EMULATENOAJAX', '1');
		}
	};
	jQuery.ajax(ajax_options);
});