/**
 * @author Mauricio Prado
 */
	jQuery.fn.setDisable = function(disable){
		disable = disable==null || disable==true?true:false;
		var preserve = ['position', 'opacity'];
		var all = this;
		if(disable){
			all.each(function(){
				if(this.preserverObjDisabled!=null)
					return;
				var jqthis = jQuery(this);
//				var width = jqthis.width();
//				var height = jqthis.height();
				this.preserverObjDisabled = {};
				for(idx in preserve){
					var name = preserve[idx];
					var val = null;
					if((val = jqthis.css(name))!=null)
						this.preserverObjDisabled[name] = val;
				}
				jqthis
					.css('position', 'relative')
					.css('opacity', '0.5')
				;
				jqoverlay = 
					jQuery('<div class="disabler-overlay-noclick"></div>')
						.appendTo(jqthis)
						.css('position','absolute')
						.css('background-color','white')
						.css('opacity','0')
						.css('top',0)
						.css('left',0)
						.width('100%')
						.height('100%')
				;
			});
			jQuery('[name]', all).attr('disabled','disabled');
		}
		else{//restaurar
			all.each(function(){
				if(this.preserverObjDisabled==null)
					return;
				for(idx in preserve){
					var name = preserve[idx];
					var val = this.preserverObjDisabled[name];
					if(val)
						jQuery(this).css(name, val);
				}
				this.preserverObjDisabled = null;
				jQuery('.disabler-overlay-noclick', this).remove();
			});
			jQuery('[name]', all).removeAttr('disabled');
		}
	}
