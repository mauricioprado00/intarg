window.ieVersion = null;
window.imOnIE = navigator.platform == "Win32" && navigator.appName == "Microsoft Internet Explorer" && window.attachEvent;
if(imOnIE){
	var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, '');
	window.ieVersion = Number(rslt[1]);
	window.itsAllGood = (rslt != null && Number(rslt[1]) >= 5.5 && Number(rslt[1])<7);
	
	
	switch(true){
		case window.ieVersion<7:{
			jQuery(document).ready(function(){
//				jQuery('.imgSearchProducto').each(function(){
//					var jqthis = jQuery(this);
//					var that = this;
//					var jqparent = jQuery(this.parentNode.parentNode);
//					var my_height = jqthis.height();
//					var my_width = jqthis.width();
//					jqthis.height(0);
//					jqthis.width(0);
//					var dad_height = jqparent.height();
//					var dad_width = jqparent.width();
//					jqthis.height(dad_height);
//					jqthis.width((my_width * dad_height / my_height));
//				});
				jQuery('.headerProducto').each(function(){
					var jqthis = jQuery(this);
					var that = this;
					var my_height = jqthis.height();
					if(my_height<24)
						jqthis.height(24);
				})
				jQuery('.catalogo_ver_producto .producto_descripcion').each(function(){
					var jqthis = jQuery(this);
					var that = this;
					var my_height = jqthis.height();
					var my_width = jqthis.width();
					if(my_height>300)
						jqthis.height(300);
					if(my_height<122)
						jqthis.height(122);
				});
				jQuery('.box-info').css('z-index','1');
			});
			break;
		}
	}
	
}
