jQuery(document).ready(function(){
//	jQuery('a.fancylink').fancybox({
//		'speedIn'		:	600, 
//		'speedOut'		:	200, 
//		'overlayShow'	:	false
//	}).click(function(){return false;});
	jQuery('a.fancylink').each(function(){
		var options = {
			'width'				: '75%',
			'height'			: '75%',
	        'autoScale'     	: false,
	        'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'iframe'
		};
		var options2 = null;
		try{
			eval('options2 = '+jQuery(this).attr('rel'));
			for(varname in options2){
				options[varname] = options2[varname];
			}
		}
		catch(e){
			
		}
		jQuery(this).fancybox(options);	
	})
});