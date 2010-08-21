function UrlHelper(base_url){
	if(typeof(base_url)=='object'){
		if(base_url.base_url!=null){
			this.loadFromObject(base_url);
			return;
		}
	}
	this.load(base_url);
}
UrlHelper.prototype = {
	base_url: null,
	modo_ajax: false,
	xhr: null,
	setXhr: function(xhr){
		if(this.xhr!=null){
			this.xhr.abort();
		}
		this.xhr = xhr;
	},
	loadFromObject: function(o){
		this.load(o.base_url);
	},
	load: function(base_url){
		this.setBaseUrl(base_url)
	},
	setBaseUrl: function(base_url){
		this.base_url = base_url;
		if(base_url==null)
			return;
		var current = window.location.href.toString().split('#');
		if(current.length>1){
			link_url = current[1];
		}
		else{
			if(current[0]==this.getBaseUrl()){//es el home
				return;
			}
			current = current[0].split(this.getBaseUrl());
			if(current.length>1){
				link_url = current[1];
			}
			else{
				return;
			}
		}
		//window.console.log(link_url);
		this.last_loaded_link_url = document.location.href.toString().split('#')[0] + '#' + link_url;
	},
	getBaseUrl: function(){
		return this.base_url;
	},
	getResourceUrl: function(url){
		return this.base_url + url;
	},
	getUrl: function(url){
		if(this.modo_ajax){
			//window.console.log(url);
			return '#' + url;
		}
		else
			return this.base_url + url;
	},
	setModoAjax: function(valor){
		this.modo_ajax = valor?true:false;
	},
	overloadLinksOn: function(contenedor){
		var that = this;
		jQuery('a[href]', contenedor).each(function(){
			if(this.overloaded_link)
				return;
			if(jQuery(this).parents('.banner_container:first').length)
				return;
			//si tiene un target definido entonces no hace falta hacer que el boton se ajax
			if(jQuery(this).filter(':[target]').length)
				return;
			var info = {};
			try{
				var extra_info = jQuery(this).attr('data-extrainfo');
				if(extra_info){
					eval('info='+extra_info+';');
					if(info){
						jQuery(this).data('data-extrainfo', info);
						if(info.no_ajax==1)
							return;
					}
				} 
			}catch(e){info={};}
			this.overloaded_link = true;
			if(info.screenblock!=1){
				var link = jQuery(this).attr('href');
				if(link.indexOf(that.base_url)!=-1){
					link = link.split(that.base_url).join('');
					if(link=='')
						link = that.getHomeLinkUrl();
					jQuery(this).attr('href', '#'+link);
				}
			}
			else{
				jQuery(this).click(function(){
					that.loadScreenblock(jQuery(this).attr('href'));
					return false;
				});
			}
		});
	},
	getDinamicHiddenContainer: function(clase){
		var contenedor = jQuery('.'+clase);
		if(!contenedor.length){
			contenedor = jQuery('<div></div>').addClass(clase).hide().appendTo('body');
			if(clase!='mainHiddenContainer')
				contenedor.appendTo(this.getDinamicHiddenContainer('mainHiddenContainer'));
		}
		return contenedor;
	},
	getMainHiddenContainer: function(){
		return this.getDinamicHiddenContainer('mainHiddenContainer');
	},
	getAjaxReturnArea: function(){
		return this.getDinamicHiddenContainer('ajax_return_area');
	},
	getScreenblockContainer: function(){
		return this.getDinamicHiddenContainer('screenblockContainer').html('').show();
	},
	loadScreenblock: function(url){
		var that = this;
		this.showWaitScreen();
		var ajax_options = {
			type: "GET",
			url: url,
			//data: {},
			success: function(data){
				var ajax_return_area = that.getAjaxReturnArea();
				var screen_block_container = that.getScreenblockContainer();
				ajax_return_area.html(data);
				jQuery(document).ready(function(){
					screen_block_container.ScreenBlock();
				});
			},
			beforeSend: function(xhr){
				xhr.setRequestHeader('SCREENBLOCK', '1');
			}
		};
		jQuery.ajax(ajax_options);
	},
	ajax_wait_div: null,
	getAjaxWaitDiv: function(){
		if(this.ajax_wait_div==null)
			this.ajax_wait_div = jQuery('<div><img src="'+this.getResourceUrl(this.ajaxLoadImage)+'" /></div>');
		return this.ajax_wait_div;
	},
	showWaitScreen: function(){
		this.getAjaxWaitDiv()
			.css('background-color', 'white')
			.ScreenBlock({close_button:false,block_css:{background:'none'}})
		;
	},
	hideWaitScreen: function(){
		jQuery.ScreenBlock(false);
	},
	getReturn: function(url){
		var that = this;
		this.showWaitScreen();
		var ajax_options = {
			type: "GET",
			url: url,
			//data: {},
			success: function(data){
				var ajax_return_area = that.getAjaxReturnArea();
				ajax_return_area.html(data);
//				window.console.log(that.xhr.getAllResponseHeaders());
//				window.console.log(that.xhr.getResponseHeader('pagina_sin_url'));
				jQuery(document).ready(function(){
					that.hideWaitScreen();
				});
				if(that.xhr.getResponseHeader('pagina_sin_url')=='si'){
					var last = that.last_loaded_link_url;
					if(last==null)
						last = document.location.href.toString().split('#')[0] + '#' + that.getHomeLinkUrl();
					that.current_link_url = that.current_link_url+'/loaded';
					that.current_link_url = last;
					document.location.href = that.current_link_url;
				}
				else{
					that.last_loaded_link_url = that.current_link_url;
				}
//				if(this.replaceWith)
//					jQuery(that.jqs_contenedor).html("").replaceWith(data);
//				else jQuery(that.jqs_contenedor).html("").replaceWith(data);
//				that.modificarLinks(that.jqs_contenedor);
			},
			beforeSend: function(xhr){
				xhr.setRequestHeader('PAGINA_ANTERIOR', that.last_loaded_link_url);
				xhr.setRequestHeader('GG_CLICK_COUNTER', window.gg_click_counter||0);
				that.setXhr(xhr);
				//xhr.setRequestHeader('Accept', 'text/html');
				//xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
				//xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST');
			}
		};
		jQuery.ajax(ajax_options);
	},
	goLink: function(url_link){
		this.prev_link_url = this.current_link_url;
		this.current_link_url = url_link;
		url_link = url_link.toString().split('#');
		if(url_link[1]==this.getHomeLinkUrl())url_link[1]='';
		if(url_link.length>1){
			this.getReturn(this.base_url+url_link[1]);
		}
	},
	poolUrl: function(){
		if(this.current_link_url != document.location.href.toString()){
			this.goLink(document.location.href);
		}
		setTimeout(function(){UrlHelper.poolUrl()}, 100);
	},
	preloaded_images: [],
	preloadImage: function(src, full_url){
		if(full_url==null)
			full_url = false;
		if(full_url==false)
			src = this.getResourceUrl(src);
		img = new Image();
		img.src = src;
		//window.console.log('preloading ' + src);
		this.preloaded_images.push(img);
	},
	ajaxLoadImage: null,
	setAjaxLoadImage: function(src){
		this.preloadImage(src);
		this.ajaxLoadImage = src;
	},
	initializeAjaxLoad: function(src){
		var jqAjaxLoadMessage = jQuery('.ajax_load_message');
		var that = this;
		if(jqAjaxLoadMessage.length<=0){
			UrlHelper.setAjaxLoadImage('skin/granguia/default/img/loaders/circular.gif');
		}
		else{
			jqAjaxLoadMessage.find('img[src]').each(function(){
				that.preloadImage(jQuery(this).attr('src'), true);
			});
			this.ajax_wait_div = jqAjaxLoadMessage;
		}
		that = null;
		jqAjaxLoadMessage = null;
	},
	home_link_url:'~',
	setHomeLinkUrl: function(link_url){
		this.home_link_url = link_url;
		if(this.last_loaded_link_url==null){
			this.last_loaded_link_url = this.getBaseUrl()+'#'+link_url;
		}
	},
	getHomeLinkUrl: function(){
		return this.home_link_url;
	}	
};

/*<para crear un singleton>*/
UrlHelper.instancia = new UrlHelper();
UrlHelper.getInstance = function(){
	if(UrlHelper.instancia == null)
		UrlHelper.instancia = new UrlHelper();
	return UrlHelper.instancia; 
}
for(idx in UrlHelper.getInstance()){
	var instancia = UrlHelper.getInstance();
	if(typeof(instancia[idx])=='function'){
		(function(funcion){
			UrlHelper[idx] = function(){
				return funcion.apply(UrlHelper.getInstance(), arguments);
			}
		})(instancia[idx]);
	}
	//window.console.log(idx, );
}
/*</para crear un singleton>*/

jQuery(document).ready(function(){
//	return;
	UrlHelper.setModoAjax(true);
	UrlHelper.overloadLinksOn('body');
	UrlHelper.poolUrl();
	UrlHelper.initializeAjaxLoad('skin/granguia/default/img/loaders/circular.gif');
});	