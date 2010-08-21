/**
 * @author Mauricio Prado
 */
(function($){
	$.fn.iconize = function(filename, iconsize){
		var valid_sizes = [16,48];
		var use_size = null;
		if(iconsize!=null){
			iconsize = parseInt(iconsize);
			for(idx in valid_sizes){
				if(iconsize==valid_sizes[idx]){
					use_size = valid_sizes[idx];
				}
			}
		}
		use_size = use_size==null?16:use_size;
		var extension = filename.toString().split('.').pop().toString().toLowerCase();
		switch(extension){
			case 'bmp':
			case 'png':
			case 'gif':
			case 'tga':
			case 'jpg':
			case 'jpeg':{
				extension = 'picture';
				break;
			}
			case 'zip':
			case 'tar':
			case 'gz':
			case 'tgz':
			case 'bz2':
			case 'tbz':
			case '7z':{
				extension = 'compress';
				break;
			}
			case 'avi':
			case 'mpg':
			case 'mpeg':
			case 'flv':{
				extension = 'film';
				break;
			}
		}
		this.each(function(){
			var jqthis = jQuery(this);
			jqthis.uniconize();
			jQuery(this)
				.addClass('show_iconized_'+use_size)
				.addClass('show_iconized_'+use_size+'_'+extension)
			;
		})
	}
	$.fn.uniconize = function(){
		this.each(function(){
			var jqthis = jQuery(this);
			var css_class_attr = jQuery(this).attr('class');
			if(css_class_attr!='' && css_class_attr!=null){
				var css_classes = css_class_attr.toString().split(' ');
				if(css_classes.length>0){
					for(idx in css_classes){
						var css_class = css_classes[idx].replace(/^\s*|\s*$/g,"");//es un trim
						var re = '^show_iconized_[0-9]+.*';
						var patt1=new RegExp(re);
						var es_iconized = patt1.test(css_class);
						if(es_iconized){
							jqthis.removeClass(css_class);
						}
					}
				}
			}
		});
	}
}(jQuery));