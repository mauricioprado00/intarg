/*
	Class:    	dwIMageProtector
	Author:   	David Walsh
	Website:    http://davidwalsh.name
	Version:  	1.0.0
	Date:     	08/09/2008
	Built For:  jQuery 1.2.6
*/


/* sample usage */ 
if(window.console==null){
	window.console = {
		log:function(){}
	}
}
jQuery(window).bind('load', function() {
	jQuery('.protect img').each(function(){
		this.cant = 1;
		this.doit = function(){
			var jqthis = jQuery(this);
			var width = jqthis.width();
			var height = jqthis.height();
			if((width==0 || height==0) && this.cant++<10){
				window.console.log('try '+this.cant);
				var that = this;
				setTimeout(function(){that.doit()}, 100);
				return;
			}
			var src = jqthis.attr('src');;
			window.console.log(width, height, src);
			var blank_src = jQuery('script[src*=js/protect.js]').attr('src').split('/');
			blank_src.pop();
			blank_src.pop();
			blank_src = blank_src.join('/')+'/img/trans.gif';
			jqthis.attr('src', blank_src).parent('.protect:first').css('position','relative');
			jqthis.width(width);
			jqthis.height(height);
			var floater = 
			jQuery('<div style="position:absolute;"></div>')
				.width(width).height(height)
				.css('left', 0).css('bottom', '0').css('cursor','pointer')
			;
			var floater_clone = floater.clone();
			floater_clone
				.prependTo(jqthis.parent('.protect:first'));
			floater
				.css('background-image', 'url('+src+')')
				.prependTo(jqthis.parent('.protect:first'));
		}
		if(this.complete)
			this.doit();
		else this.onload = function(){this.doit();}
		
	})
});

/**/
