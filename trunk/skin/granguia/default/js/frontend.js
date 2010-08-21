jQuery(document).ready(function(){
	//	design/granguia/default/template/page/header.phtml
	window.gg_click_counter = 0;
	jQuery('body').click(function(){
		window.gg_click_counter++;
	});
	jQuery('.jclock').jclock();
	//	design/granguia/default/template/page
//	jQuery('#manu>div').click(function(){
//		jQuery('#manu .seleccionado').removeClass('seleccionado');
//		jQuery(this).addClass('seleccionado');
//	});
	jQuery('#manu>div a[href]:not([target])').click(function(){
		var info = jQuery(this).data('data-extrainfo');
		if(info!=null){
			if(info.descargable)
				return;
			if(info.screenblock)
				return;
		}
		jQuery('#manu .seleccionado').removeClass('seleccionado');
		jQuery(this).parents('#manu>div').addClass('seleccionado');
	})
	
	//buscador
	jQuery('#busqueda form').submit(function(){
		var texto_busqueda = jQuery('[name=q]', this).val()
		var action = jQuery(this).attr('action');
		//alert(UrlHelper.getBaseUrl());
		var destino = action.split(UrlHelper.getBaseUrl())[1] +'?q=' + encodeURIComponent(texto_busqueda);
		window.location.href = "#" + destino; 
		return false;
	})
	
	//filtro de categorias
	jQuery('#menu .filtro').focus(function(){
		var valor = this.value;
		if(valor=='Ej: Pizzas'){
			this.value = '';
		}
		jQuery(this).css('color', '');
	}).blur(function(){
		var valor = this.value;
		if(valor==''){
			this.value = 'Ej: Pizzas';
		}
		jQuery(this).css('color', '#CCCCCC');
	}).blur();
	
	//banner carrousel
	window.crearCarrousel = function(){
		var carrousel = {
			jqdiv: null,
			jqcontainer: null,
			jqcontainer_clon:null,
			jqactivado:null,
			jqdesactivado:null,
			vstep: null,
			tiempo_cambio:6000,
			tiempo_fade:2000,
			tiempo_desperado:500,
			can_swap:true,
			init: function(jqdiv){
				this.jqdiv = jqdiv;
				this.jqcontainer = jqdiv.find('>div');
				this.jqcontainer_clon = this.jqcontainer.clone().appendTo(jqdiv);
				this.jqcontainer_clon.css('top','-50px');
				this.vstep = jqdiv.height();
				this.jqcontainer.css('z-index',1);
				this.jqactivado = this.jqcontainer;
				this.jqdesactivado = this.jqcontainer_clon;
				var that = this;
				setTimeout(function(){that.timeout()}, this.tiempo_cambio);
				jqdiv.mouseover(function(){that.mouseover();});
				jqdiv.mouseout(function(){that.mouseout();});
//				window.console.log(this.vstep);
//				window.console.log(this.jqcontainer);
//				window.console.log(this.jqcontainer_clon);
			},
			mouseover: function(){
				this.can_swap = false;
			},
			mouseout: function(){
				this.can_swap = true;
			},
			canSwap: function(){
				return this.can_swap;
			},
			swap: function(){
				var current = this.jqactivado;
				this.jqactivado = this.jqdesactivado;
				this.jqdesactivado = current;
				this.jqdesactivado.css('z-index',0);
				var top = parseInt(this.jqdesactivado.css('top'));
				top -= this.vstep * 2;
				var ptop = top * -1;
				if(ptop>=this.jqdesactivado.height())
					top += this.jqdesactivado.height();
				this.jqdesactivado.css('top', top+'px');
				this.jqactivado.css('z-index',1);
			},
			timeout:function(){
				var that = this;
				if(this.canSwap()){
					this.jqdesactivado.show();
					this.jqactivado.fadeOut(this.tiempo_fade,function(){
						that.swap();
					});
					setTimeout(function(){that.timeout()}, this.tiempo_cambio);
				}
				else{
					setTimeout(function(){that.timeout()}, this.tiempo_desperado);
				}
			}
			
		};
		jQuery('.stepcarousel2').data('carrousel2', carrousel);
		carrousel.init(jQuery('.stepcarousel2'));
		carrousel = null;
	}
	jQuery('.stepcarousel2').each(function(){
		var jqc = jQuery(this);
		var jqi = jqc.find('.contenedor_carousel');
		//setTimeout(function (){alert(jqi.attr('offsetHeight'))}, 5000);
		if(jqc.height()<jqi.height()){
			crearCarrousel();
		}
		
		jqc = null;
	});
});

