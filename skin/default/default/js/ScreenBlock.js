
jQuery.ScreenBlock = jQuery.fn.ScreenBlock = function(options){
	var staticmode = this==jQuery;
	if(options!=null){
		if(typeof(options)=='object'){
			for(idx in options){
				if(typeof(options[idx])=='object' && jQuery.fn.ScreenBlock.params[idx]!=null){
					options[idx] = jQuery.extend({}, jQuery.fn.ScreenBlock.params[idx], options[idx]);
				}
			}
		}
		else if(typeof(options)=='string'){
			options = {message:options};
		}
		else if(typeof(options)=='boolean'){
			if(options==false){
				if(jQuery.ScreenBlock.pushedWindows.length){
					//window.console.log(jQuery.ScreenBlock.pushedWindows);
					var jqwindow = jQuery.ScreenBlock.pushedWindows.pop();
					//window.console.log(jqwindow);
					jqwindow.each(function(){
						var message = jQuery(this).data('ScreenBlock/Message');
						if(typeof(message)=='object'){
							var postizo = null;
							if(postizo = jQuery(message).data('ScreenBlock/Postizo'))
								postizo.replaceWith(message);
								//jQuery(message).appendTo(parent);
							postizo = null;
						}
					});
					jqwindow.remove();
					// jQuery('<a>otra cssfsdfdfsa</a>').ScreenBlock({remover_anterior:false})
					if(jQuery.ScreenBlock.pushedWindows.length){
						var jqwindow = jQuery.ScreenBlock.pushedWindows[jQuery.ScreenBlock.pushedWindows.length-1];
						jqwindow.show();
					}
				}
//				jQuery('.ScreenBlockWrapper').each(function(){
//					var message = jQuery(this).data('ScreenBlock/Message');
//					if(typeof(message)=='object'){
//						var postizo = null;
//						if(postizo = jQuery(message).data('ScreenBlock/Postizo'))
//							postizo.replaceWith(message);
//							//jQuery(message).appendTo(parent);
//						postizo = null;
//					}
//				})
//				jQuery('.ScreenBlockWrapper').remove();
			}
			return;
		}
	}
	if(options==null)
		options = {};
	if(options.remover_anterior==true || options.remover_anterior==null){
		jQuery.ScreenBlock(false);
	}
	if(options.message==null){
		if(staticmode==false){
			options.message = this.first();
		}
		else return false;//si no hay mensaje no se muestra nada
	}
	var params = jQuery.extend({}, jQuery.fn.ScreenBlock.params, options);
	var jqwindow = jQuery('<div class="ScreenBlockWrapper"></div>').data('ScreenBlock/Message', params.message);
	if(params.message==null){
		jqwindow = null;
		return;
	}
	jQuery('.ScreenBlockWrapper').hide();
	jQuery.ScreenBlock.pushedWindows.push(jqwindow);
	
	if(typeof(params.message)=='object'){
		var postizo = jQuery('<div style="display:none;"></div>');
		jQuery(params.message).after(postizo);
		jQuery(params.message).data('ScreenBlock/Postizo', postizo);
		postizo = null;
	}
	var jqblock = jQuery('<div class="ScreenBlockTile"></div>');
	var jqmessage_container = jQuery('<div></div>').css(params.message_container_css);
	var jqmessage = jQuery('<div class="message"></div>').css(params.message_css).html(params.message).appendTo(jqmessage_container);
	
	jqwindow.css(params.window_css).appendTo('body');
	jqblock.css(params.block_css).appendTo(jqwindow);
	jqmessage_container.appendTo(jqwindow);
	if(params.message_css.width==null && params.autosize){//autowidth
		jqmessage.css('display','inline');
		jqmessage.width(jqmessage.width());
		jqmessage.css('display','block');
	}
	if(params.message_css.height==null && params.autosize){//autowidth
		jqmessage.css('display','inline');
		jqmessage.height(jqmessage.height());
		jqmessage.css('display','block');
	}
	if(params.autocenter==true){
		var tiene_tamano_definido = params.autosize || (params.message_css.height!=null && params.message_css.width!=null && params.message_css.display=='block');
		if(tiene_tamano_definido){
			jqmessage.css({
				position:'absolute',
				margin:'auto',
				top:0,
				left:0,
				bottom:0,
				right:0
			});
		}
		else{
			var whole_height = jqmessage.height() + parseFloat(jqmessage.css('borderTopWidth')) + parseFloat(jqmessage.css('borderBottomWidth')) + parseFloat(jqmessage.css('paddingBottom')) + parseFloat(jqmessage.css('paddingTop'));
			var half_height = whole_height / 2;
			var whole_width = jqmessage.width() + parseFloat(jqmessage.css('borderRightWidth')) + parseFloat(jqmessage.css('borderLeftWidth')) + parseFloat(jqmessage.css('paddingLeft')) + parseFloat(jqmessage.css('paddingRight'));
			var half_width = whole_width / 2;
			
			jqmessage.css({
				position:'absolute',
				top:'50%',
				margin:0,
				'margin-top':'-'+half_height+'px',//(height + padding-top + padding-bottom + border-top + border-bottom)/2 * -1
				left:'50%',
				'margin-left':'-'+half_width+'px',//(width + padding-right + padding-left + border-right + border-left)/2 * -1
			});
		}
	}

	if(params.autocenter_y){
		var height = jqmessage.height();
		var dheight = jqmessage.parent().height();
		var top = (dheight - height)/2;
		//jqmessage_container.css('position','absolute');
		jqmessage_container.css('position','absolute').css('top', top + 'px');
	}
	if(params.close_button){
		var jqbutton = jQuery('<div class="cerrar">X</div>').attr('title','Cerrar').css(params.close_button_css).appendTo(jqmessage);
		jqbutton[0].scrollLeft = 2;
		jqbutton[0].scrollTop = 1;
		jqbutton.click(function(){
			if(params.onclose){
				params.onclose();
			}
			jQuery.ScreenBlock(false);
		});
		jqbutton = null;
	}
	jqblock=null;
	jqmessage_container=null;
	jqmessage=null;
	jqwindow=null;
}
jQuery.ScreenBlock.pushedWindows = [];
jQuery.fn.ScreenBlock.params = {
	message:'Aguarde un momento...',
	window_css:{
		height:'100%',
		left:0,
		position:'fixed',
		top:0,
		width:'100%'
	},
	block_css: {
		opacity: 0.5,
		top: 0,
		left: 0, 
		width: '100%', 
		height: '100%', 
		'background-color': 'white',
		position: 'fixed', 
		'z-index': 50
	},
	message_css:{
		'-moz-border-radius': '11px',
		'webkit-border-radius': '11px',
		'border-radius': '11px',
		display:'block',
		//width:'auto',
		border:'5px solid #333333',
		'background-color':'white',
		margin:'0 auto',
		padding:'15px',
		position:'absolute',
		color:'black'
	},
	//autosize: true,
	autocenter: true,
	//autocenter_y: false,
	close_button: true,
	close_button_css:{
//		border:'2px solid black',
//		'-moz-border-radius':'15px',
//		width:'15px',
//		height:'15px',
//		cursor:'pointer',
		'border': '2px solid black',
		'width': '15px',
		'height': '15px',
		'cursor': 'pointer',
		'-moz-border-radius': '9px',
		'webkit-border-radius': '9px',
		'border-radius': '9px',
		'position': 'absolute',
		'right': '-8px',
		'top':'-8px',
		'color': 'black',
		'background-color': 'white',
		'font-weight': 'bold',
		'font-family': 'arial',
		'font-size': '28px',
		'overflow': 'hidden',
		'line-height': '19px'
	},
	message_container_css: {
		top: 0,
		left: 0, 
		width: '100%', 
		height: '100%', 
		position: 'fixed',
		'z-index': 51,
		'text-align':'center'
	},
	onclose:null,
	remover_anterior: true
}
//jQuery.ScreenBlock({message:'kradkk.com'});
//jQuery.ScreenBlock(false);
