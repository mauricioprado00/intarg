window._________helperurl = null;
function HelperUrl(){
	if(window._________helperurl==null){
		window._________helperurl = newHelperUrl();
	}
	return(window._________helperurl);
}
function newHelperUrl(){
	return({
		current_link_url: null,
		base: null,
		ajaxpath: null,
		jqs_contenedor: null,
		replaceWith: true,
		modificarLinks: function(contenedor){
			var that = this;
			jQuery('[href]', contenedor)
				.each(function(){
//window.console.log(this);
					var jqthis = jQuery(this);
					this.url_link = jqthis.attr('href');
					if(this.url_link.split('#').length>1)
						return;
//					window.console.log(this.url_link,that.base);
//					window.console.log(this.url_link.split(that.base).join(''));
					jqthis.attr('href','#'+this.url_link.split(that.base).join(''));
				})
		},
		initialize: function(base, ajaxpath, jqs_contenedor){
			this.base = base;
			this.ajaxpath = ajaxpath;
			this.jqs_contenedor = jqs_contenedor;
			this.base_ajax = this.base+this.ajaxpath;
			this.modificarLinks(jQuery(jqs_contenedor));
			this.poolUrl();
		},
		setCurrentLinkUrl: function(url){/** esto cambia el link pero evita que se vuelva a pedir por ajax*/
			if(url.split("#").length>1){
				url = url.split("#")[1];
			}
			else if(url.split(this.base).length>1){
				url = url.split(this.base)[1];
			}
			url = window.location.href.toString().split('#')[0]+"#"+url
			this.current_link_url = window.location.href = url; 
		},
		goUrl: function(url){
			window.location.href = window.location.href.toString().split('#')[0]+"#"+url.split(this.base)[1];
//			url = this.base + url.split(this.base).join(this.ajaxpath);
//			this.getReturn(url);
		},
		getReturn: function(url){
			var that = this;
//			jQuery('<div class="loading_url"></div>').ScreenBlock();
//			jQuery.get(
//				url,
//				function(data){
//					jQuery.ScreenBlock(false);
//					if(this.replaceWith)
//						jQuery(that.jqs_contenedor).html("").replaceWith(data);
//					else jQuery(that.jqs_contenedor).html("").replaceWith(data);
//					that.modificarLinks(that.jqs_contenedor);
//				}
//			);
//			var that = this;
			//this.showWaitScreen();
			jQuery('<div class="loading_url"></div>').ScreenBlock({onclose:function(){
				that.abortCurrent();
			}});
			var ajax_options = {
				type: "GET",
				url: url,
				//data: {},
				success: function(data, state, xhr){
					if(xhr.status!=200)//sino es que fue cancelada u ocurrieron errores
						return;
//					window.console.log(xhr.status, xhr.statusText);
//					window.console.log(arguments);
					jQuery.ScreenBlock(false);
					if(this.replaceWith)
						jQuery(that.jqs_contenedor).html("").replaceWith(data);
					else jQuery(that.jqs_contenedor).html("").replaceWith(data);
					that.modificarLinks(that.jqs_contenedor);
				},
				beforeSend: function(xhr){
					that.setXhr(xhr);
					//xhr.setRequestHeader('SCREENBLOCK', '1');
				}
			};
			jQuery.ajax(ajax_options);
		},
		abortCurrent:function(){
			if(this.xhr!=null){
				this.xhr.abort();
			}
		},
		setXhr: function(xhr){
			this.abortCurrent();
			this.xhr = xhr;
		},
		goLink: function(url_link){
			this.current_link_url = url_link;
			url_link = url_link.toString().split('#');
			if(url_link.length>1){
				this.getReturn(this.base_ajax+url_link[1]);
			}
		},
		poolUrl: function(){
			if(this.current_link_url != document.location.href.toString()){
				
					this.goLink(document.location.href);
			}
			setTimeout(function(){HelperUrl().poolUrl()}, 100);
		}
	});
}