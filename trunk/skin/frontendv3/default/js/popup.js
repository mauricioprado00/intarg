if(window.name.toString().split('bm_catalog_search').length>1){
	var parent_name = window.name.toString().split('_child').join('');
	jQuery(document).ready(function(){
		history.back = function(){
			window.close();
		}
		jQuery('a[href]')
			.click(function(){
				if(jQuery(this).attr('target')!=null){
					if(jQuery(this).attr('target')!=''){
						return null;
					}
				}
				var clickedElement	= jQuery(this); // The element that was actually clicked
				var clickedLink		= clickedElement.is('a') ? clickedElement : clickedElement.parents('a'); // If it's not an a, check if any of its parents is
					clickedLink		= (clickedLink && clickedLink.is('a') && clickedLink.attr('href').search(/(.*)\.(jpg|jpeg|gif|png|bmp|tif|tiff)$/gi) != -1) ? clickedLink : false; // If it was an a or child of an a, make sure it points to an image
				var clickedImg		= (clickedLink && clickedLink.find('img').length) ? clickedLink.find('img') : false; // See if the clicked link contains and image
				
				// Only continue if a link pointing to an image was clicked
				if (clickedLink){
					return;
				}
				window.mipapa.location.href = jQuery(this).attr('href');
				window.close();
				return(false);
			})
		;
		jQuery('form')
			.attr('target',parent_name)
			.submit(function(){
				window.close();
			})
		;
	})
}
else{
	try{
		window.console.log("algo salio mal");
	}catch(e){}
}
jQuery(document).ready(function(){
	try{
	if(imOnIE&&ieVersion<8){
		setTimeout(function(){
		jQuery('.texto_fax').css('position','static');
		},500);
	}
	}catch(e){}
});