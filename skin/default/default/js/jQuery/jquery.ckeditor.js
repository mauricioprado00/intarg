/**
 * @author Mauricio Prado
 */
(function($) {
	
	// global $ methods for blocking/unblocking the entire page
	$.fn.ckeditor = function(accion,options){
		if(typeof(accion)=='object'){
			options = accion;
			accion = 'overload';
		}
		else if(accion==null)accion='overload';
		if(accion=='overload'){
			$.ckeditor.cleanOrphans();
			this.each(function(){
				var jqthis = jQuery(this);
				//window.console.log(this);
				if(this.instanciaCkeditor)
					return;
				var jqthis = jQuery(this);
				try{
					var preservar = {name:jqthis.attr('name'),id:jqthis.attr('id')};
					jqthis.removeAttr('name').removeAttr('id');//para que el ckeditor no lo cree con nombres
					var config = null;
					if(options!=null){
						if(options.config!=null)
							config = options.config;
					}
					this.instanciaCkeditor = CKEDITOR.replace( this, config );
					if(preservar.name!=null && preservar.name!='')
						jqthis.attr('name', preservar.name);
					if(preservar.id!=null && preservar.id!='')
						jqthis.attr('id', preservar.id);
					//if(CKEDITOR.instances[this.id]==null)kradkk.com();
					//this.instanciaCkeditor = CKEDITOR.instances[this.id];
					//window.console.log(this.instanciaCkeditor.name);
					if(options!=null){
						var eventos = ['instanceReady', 'key'];
						for(idx in eventos){
							var evento = eventos[idx];
							if(options[evento]!=null){
								this.instanciaCkeditor.on(evento, options[evento]);
							}
						}
					}
				}
				catch(e){
					alert('error jquery.ckeditor.js: '+e);
					//setTimeout(function(){jqthis.ckeditor()}, 100);
				}
			})
		}
		else if(accion=='refresh'){
			this.each(function(){
				//window.console.log(this.instanciaCkeditor);
				if(this.instanciaCkeditor==null)
					return;
				var jqthis = jQuery(this);
				instancia = this.instanciaCkeditor;
				//window.console.log(instancia.getData(true));
				jQuery(this).text( instancia.getData(true) );	
			});
		}
		return this;
	}
	$.ckeditor = {
		'cleanOrphans':function(){//mata los ckeditors viejos
			//window.console.group('limpiando');
			for(name in CKEDITOR.instances){
				///window.console.log(['id:',name]);
				try{
					var instancia = CKEDITOR.instances[name];
					var el = instancia.element.$;
					//window.console.log(el);
					var tienePapa = jQuery(el).parents('body').length>0;
					//window.console.log(['tienePapa', tienePapa]);
					if(!tienePapa){
						CKEDITOR.remove(instancia);
					}
				}catch(e){
					window.console.log('error:'+e);
				}
			}
			//window.console.groupEnd();
		},
		'removeAll':function(){
			for(name in CKEDITOR.instances)
				CKEDITOR.remove(CKEDITOR.instances[name]);
		},
		'overload': function(){
			alert("metodo viejo por favor no usar $.ckeditor.overload, sino jQuery(###SELECTOR###).ckeditor()");
			try{
				for(name in CKEDITOR.instances)
					CKEDITOR.remove(CKEDITOR.instances[name]);
				$('.ckeditor').each(function(){
					CKEDITOR.replace( this.id );
					if(CKEDITOR.instances[this.id]==null)kradkk.com();
			        $(this).removeClass('ckeditor');
				});
	
			}catch(e){
				//window.console.log(e);
				setTimeout(function(){jQuery.ckeditor.overload();}, 100);
			}
		},
		'refresh': function(){
			alert("metodo viejo por favor no usar, usar jQuery(###SELECTOR###).ckeditor('refresh')");
			// Remove old non-existing editors from memory
			for(var name in CKEDITOR.instances){
				if(jQuery('#'+name).length==0)
				 delete CKEDITOR.instances[name];
			};
			// loop through editors
			for(var name in CKEDITOR.instances){
				var instancia = CKEDITOR.instances[name];
				var data = instancia.getData(true);//this.content(name);
				var area = jQuery('#'+name);
				area.text( data );
				alert("looping end "+name);
			};
		}
	}
})(jQuery);
