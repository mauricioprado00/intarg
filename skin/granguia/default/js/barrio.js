function MapaBarrio(contenedor,id_barrio,url_imagen_mapa, tipos_puntos, anuncios){
	this.contenedor = null;
	this.url_imagen_mapa ='';
	this.id_barrio = null;
	this.tipos_puntos = [];
	this.indexed_tipos_puntos = {};
	this.anuncios = [];
	this.preloaded_images = [];
	this.url_contador;
	this.tamano_estado=0;
	this.tamano_estado_maximo = 10;
	this.escala_tamano = {};
	this.escala_opacity = {};
	this.escala_tamano;
	var p1 = 0;
	var p2 = 0;
	for(var i=0; i<=this.tamano_estado_maximo; i++){
		
		fib = p1 + p2;
		this.escala_tamano[i] = (fib = fib<1?1:fib);
		p2 = p1;
		p1 = fib;
		
		this.escala_opacity[i] = 100 - parseInt(((i-1)*100)/this.tamano_estado_maximo);
	}
	//window.console.log(this.escala_tamano);
	if(typeof(contenedor)=='object'){
		if(contenedor.contenedor!=null){
			this.loadFromObject(contenedor);
			return;
		}
	}
	this.load(contenedor, id_barrio, url_imagen_mapa, tipos_puntos, anuncios);
}
MapaBarrio.prototype = {
//	contenedor: null,
//	url_imagen_mapa:'',
//	tipos_puntos: [],
//	indexed_tipos_puntos: {},
//	anuncios: [],
//	preloaded_images: [],
	setUrlContador: function(url_contador){
		this.url_contador = url_contador;
	},
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
	loadFromObject: function(o){
		this.load(o.contenedor, o.id_barrio, o.url_imagen_mapa, o.tipos_puntos, o.anuncios);
	},
	load: function(contenedor, id_barrio, url_imagen_mapa, tipos_puntos, anuncios){
		if(typeof(contenedor)=='string')
			this.contenedor = jQuery(contenedor);
		else
			this.contenedor = contenedor;
		this.id_barrio = id_barrio;
		this.url_imagen_mapa = url_imagen_mapa;
		this.loadTiposPuntos(tipos_puntos);
		this.loadAnuncios(anuncios);
		this.overloadVisor();
	},
	overloadVisor: function(){
		VisorAnuncio.tellme(this,this.onVisorClick, this.onVisorOver, this.onVisorOut);
	},
	onVisorClick: function(){
		//window.console.log('onVisorClick');
	},
	onVisorOver: function(){
		//window.console.log('onVisorOver');
		this.remarcarSeleccionado();
	},
	onVisorOut: function(){
		//window.console.log('onVisorOut');
		this.desmarcarSeleccionado();
	},
	remarcarSeleccionado: function(){
		//window.console.log(jQuery('.seleccionado', this.contenedor));
		jQuery('.seleccionado', this.contenedor).mouseover();
	},
	desmarcarSeleccionado: function(){
		jQuery('.dohover', this.contenedor).mouseout();
	},
	loadTiposPuntos:function(tipos_puntos){
		for(idx in tipos_puntos){
			this.addTipoPunto( new MapaBarrioTipoPunto(tipos_puntos[idx]) );
		}
	},
	addTipoPunto:function(oTipoPunto){
		this.preloadImage(oTipoPunto.imagen_deseleccionado);
		if(oTipoPunto.imagen_seleccionado)
			this.preloadImage(oTipoPunto.imagen_seleccionado);
		if(oTipoPunto.imagen_over)
			this.preloadImage(oTipoPunto.imagen_over);
		this.tipos_puntos.push(oTipoPunto);
		this.indexed_tipos_puntos['id:'+oTipoPunto.id] = oTipoPunto;
	},
	getTipoPunto: function(id_tipo_punto){
		if(this.indexed_tipos_puntos['id:'+id_tipo_punto]!=null)
			return this.indexed_tipos_puntos['id:'+id_tipo_punto];
		return null;
	},
	loadAnuncios:function(anuncios){
		for(idx in anuncios){
//			window.console.log(anuncios[idx]);
			this.addAnuncio( new MapaAnuncio(anuncios[idx]) );
		}
	},
	addAnuncio:function(oAnuncio){
		//window.console.log(oAnuncio);
		this.preloadImage(oAnuncio.imagen_logo);
		this.anuncios.push(oAnuncio);
	},
	getUrl: function(url){
		return UrlHelper.getUrl(url);
	},
	getResourceUrl: function(url){
		return UrlHelper.getResourceUrl(url);
	},
	getUrlImagenMapa: function(){
		return this.getResourceUrl(this.url_imagen_mapa);
	},
	drawMapa:function(){
		jQuery('<img />').attr('src', this.getUrlImagenMapa()).appendTo(this.contenedor);
	},
	configureContainer: function(){
		this.contenedor.css('position', 'relative').css('float','left');
	},
	contados: {},
	contarClickAnuncio: function(anuncio){
		if(this.contados[this.id_barrio]==null)
			this.contados[this.id_barrio] = {};
		if(this.contados[this.id_barrio][anuncio.id]==null){
			this.contados[this.id_barrio][anuncio.id] = true;
			jQuery.post(this.url_contador,{id_anuncio:anuncio.id});
		}
	},
	updateCirculosEstadoAnuncio: function(tamano, opacity){
		var that = this;
		jQuery('.circulo_estado_anuncio', this.contenedor).each(function(){
			var anuncio = jQuery(this).data('MapaAnuncio');
			that.updateCirculoEstadoAnuncio(jQuery(this), anuncio, tamano, opacity);
		});
	},//( this.tamano_estado );
	updateCirculoEstadoAnuncio: function( capa, anuncio, tamano, opacity){
		//capa.css('border', '1px solid black');
		capa.css('position', 'absolute');
		var top = (anuncio.posicion_y - (tamano + 1));
		capa.css('top', top+'px');
		var left = (anuncio.posicion_x - (tamano + 1));
		capa.css('left', left+'px');
		capa.css('z-index',5);
		capa.css('cursor','pointer');
		capa.width(tamano*2 + 1);
		capa.height(tamano*2 + 1);
		//window.console.log(opacity);
		var o = opacity/100;
		capa.css('opacity', o);
	},
	createCirculoEstadoAnuncio: function( anuncio, tamano ){
//		var capa = anuncio.data('CirculoEstadoAnuncio');
//		if(capa==null){
		var capa = jQuery('<div></div>');
		var capa = jQuery('<img />');
		capa.addClass('circulo_estado_anuncio');
		capa.attr('src', this.getResourceUrl('skin/granguia/default/img/'+(anuncio.abierto?'activo.png':'inactivo.png')));
		capa.data('MapaAnuncio', anuncio);
		capa.appendTo(this.contenedor);
//		}
//		capa.data('MapaAnuncio', anuncio);
//		capa.data('MapaBarrioTipoPunto', tipo_punto);
//		capa.data('MapaBarrio', this);
//		capa.attr('title', anuncio.nombre);
//		//window.console.log(this.getUrl(tipo_punto.getImagenDeseleccionado()));
		
		this.updateCirculoEstadoAnuncio(capa, anuncio, tamano, 100);
		capa = null;
		
	},
	createPuntoAnuncio: function(anuncio, tipo_punto){
		var capa = jQuery('<div></div>');
		capa.addClass('punto_anuncio')
		capa.data('MapaAnuncio', anuncio);
		capa.data('MapaBarrioTipoPunto', tipo_punto);
		capa.data('MapaBarrio', this);
		capa.attr('title', anuncio.nombre);
		//window.console.log(this.getUrl(tipo_punto.getImagenDeseleccionado()));
		
		
		capa.css('background-repeat', 'no-repeat');
		capa.css('position', 'absolute');
		capa.css('top', (anuncio.posicion_y - tipo_punto.getHotspotY('imagen_deseleccionado'))+'px');
		capa.css('left', (anuncio.posicion_x - tipo_punto.getHotspotX('imagen_deseleccionado'))+'px');
		//capa.css('z-index',10);
		capa.css('cursor','pointer');
//		capa.width(50);
//		capa.height(50);
		capa.width(tipo_punto.width);
		capa.height(tipo_punto.height);
		if(anuncio.destacado==1){
			capa.addClass('seleccionado');
		}
		capa.appendTo(this.contenedor);
		capa
			.mouseover(function(){
				var capa = jQuery(this);
				var that = jQuery(this).data('MapaBarrio');
//				window.console.log(
//					tipo_punto.getImagenOver(),
//					that.getUrl(tipo_punto.getImagenOver())
//				);
				capa.css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenOver())+')');
				capa.addClass('dohover');
				//capa.css('z-index',20);
				capa.css('top', (anuncio.posicion_y - tipo_punto.getHotspotY('imagen_over'))+'px');
				capa.css('left', (anuncio.posicion_x - tipo_punto.getHotspotX('imagen_over'))+'px');
			})
			.mouseout(function(){
				var capa = jQuery(this);
				var that = jQuery(this).data('MapaBarrio');
				if(capa.hasClass('seleccionado'))
					capa.css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenSeleccionado())+')');
				else
					capa.css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenDeseleccionado())+')');
				//capa.css('z-index',10);
				capa.removeClass('dohover');
				capa.css('top', (anuncio.posicion_y - tipo_punto.getHotspotY('imagen_deseleccionado'))+'px');
				capa.css('left', (anuncio.posicion_x - tipo_punto.getHotspotX('imagen_deseleccionado'))+'px');
			})
			.click(function(){
				var capa = jQuery(this);
				var that = jQuery(this).data('MapaBarrio');
				jQuery('.punto_anuncio.seleccionado', that.contenedor).each(function(){
					var tipo_punto = jQuery(this).data('MapaBarrioTipoPunto');
					jQuery(this).css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenDeseleccionado())+')');
				}).removeClass('seleccionado');
				jQuery(this).addClass('seleccionado');
				VisorAnuncio.displayAnuncio(anuncio);
				that.contarClickAnuncio(anuncio);
			})
			.mouseout()
		;
//		window.console.log(capa.data('MapaAnuncio'));
//		window.console.log(capa.data('MapaBarrioTipoPunto'));
		capa = null;
	},
	drawAnuncios: function(){
		for(idx in this.anuncios){
			var anuncio = this.anuncios[idx];
			var tipo_punto = this.getTipoPunto( anuncio.id_tipo_punto )
			this.createPuntoAnuncio( anuncio, tipo_punto );
		}
	},
	draw:function(){
		this.configureContainer();
		this.drawMapa();
		this.drawAnuncios();
		this.drawCirculosEstado(true);
		this.pollingEstado();
	},
	drawCirculosEstado:function(init){
		init = init==null?false:(init?true:false);
		if(init){
			this.preloadImage('skin/granguia/default/img/activo.png', false);
			this.preloadImage('skin/granguia/default/img/inactivo.png', false);
			for(idx in this.anuncios){
				var anuncio = this.anuncios[idx];
				//var tipo_punto = this.getTipoPunto( anuncio.id_tipo_punto )
				if(init)
					this.createCirculoEstadoAnuncio( anuncio, this.escala_tamano[this.tamano_estado] );
			}
		}
		else{
			//jQuery('.circulo_estado_anuncio', this.contenedor).remove();
			if(this.tamano_estado > this.tamano_estado_maximo){
				this.tamano_estado = 0;
			}
			this.updateCirculosEstadoAnuncio( this.escala_tamano[this.tamano_estado], this.escala_opacity[this.tamano_estado] );
			this.tamano_estado += 1;
		}
	},
	pollingEstado: function(){
		if(this.contenedor==null){
			return;
		}
		if(!jQuery(this.contenedor).parents('body').length){
			this.contenedor = null;
			return;
		}
		this.drawCirculosEstado();
		var that = this;
		setTimeout(function(){that.pollingEstado()},60);
	}
};




function MapaBarrioTipoPunto(id, imagen_deseleccionado, imagen_over, imagen_seleccionado, nombre, width, height, imagen_deseleccionado_hotspot_x, imagen_deseleccionado_hotspot_y, imagen_seleccionado_hotspot_x, imagen_seleccionado_hotspot_y, imagen_over_hotspot_x, imagen_over_hotspot_y){
	if(typeof(id)=='object'){
		this.loadFromObject(id);
		return;
	}
	this.load(id, imagen_deseleccionado, imagen_over, imagen_seleccionado, nombre, width, height, imagen_deseleccionado_hotspot_x, imagen_deseleccionado_hotspot_y, imagen_seleccionado_hotspot_x, imagen_seleccionado_hotspot_y, imagen_over_hotspot_x, imagen_over_hotspot_y);
}
MapaBarrioTipoPunto.prototype = {
	id: null,
	imagen_deseleccionado: null,
	imagen_over: null,
	imagen_seleccionado: null,
	nombre: null,
	imagen_deseleccionado_hotspot_x: null,
	imagen_deseleccionado_hotspot_y: null,
	imagen_seleccionado_hotspot_x: null,
	imagen_seleccionado_hotspot_y: null,
	imagen_over_hotspot_x: null,
	imagen_over_hotspot_y: null,
	loadFromObject: function(o){
		this.load(o.id, o.imagen_deseleccionado, o.imagen_over, o.imagen_seleccionado, o.nombre, o.width, o.height, o.imagen_deseleccionado_hotspot_x, o.imagen_deseleccionado_hotspot_y, o.imagen_seleccionado_hotspot_x, o.imagen_seleccionado_hotspot_y, o.imagen_over_hotspot_x, o.imagen_over_hotspot_y);
	},
	load: function(id, imagen_deseleccionado, imagen_over, imagen_seleccionado, nombre, width, height, imagen_deseleccionado_hotspot_x, imagen_deseleccionado_hotspot_y, imagen_seleccionado_hotspot_x, imagen_seleccionado_hotspot_y, imagen_over_hotspot_x, imagen_over_hotspot_y){
		this.id = id;
		this.imagen_deseleccionado = imagen_deseleccionado!=''?imagen_deseleccionado:null;
		this.imagen_over = imagen_over!=''?imagen_over:null;
		this.imagen_seleccionado = imagen_seleccionado!=''?imagen_seleccionado:null;

		this.imagen_seleccionado_hotspot_x = this.imagen_seleccionado!=null?imagen_seleccionado_hotspot_x:imagen_deseleccionado_hotspot_x;
		this.imagen_seleccionado_hotspot_y = this.imagen_seleccionado!=null?imagen_seleccionado_hotspot_y:imagen_deseleccionado_hotspot_y;
		this.imagen_over_hotspot_x = this.imagen_over!=null?imagen_over_hotspot_x:imagen_seleccionado_hotspot_x;
		this.imagen_over_hotspot_y = this.imagen_over!=null?imagen_over_hotspot_y:imagen_seleccionado_hotspot_y;
		
		this.imagen_deseleccionado_hotspot_x = parseInt(imagen_deseleccionado_hotspot_x); 
		this.imagen_deseleccionado_hotspot_y = parseInt(imagen_deseleccionado_hotspot_y); 
		this.imagen_seleccionado_hotspot_x = parseInt(this.imagen_seleccionado_hotspot_x); 
		this.imagen_seleccionado_hotspot_y = parseInt(this.imagen_seleccionado_hotspot_y); 
		this.imagen_over_hotspot_x = parseInt(this.imagen_over_hotspot_x); 
		this.imagen_over_hotspot_y = parseInt(this.imagen_over_hotspot_y);
		
		//window.console.log(this);
		this.imagen_seleccionado = this.imagen_seleccionado!=null?this.imagen_seleccionado:this.imagen_deseleccionado;
		this.imagen_over = this.imagen_over!=null?this.imagen_over:this.imagen_seleccionado;
		//window.console.log(this);

		this.nombre = nombre;
		this.width = width;
		this.height = height;
	},
	getImagenDeseleccionado: function(){
		return this.imagen_deseleccionado;
	},
	getImagenSeleccionado: function(){
		return this.imagen_seleccionado!=null?this.imagen_seleccionado:this.getImagenDeseleccionado();
	},
	getImagenOver: function(){
		return this.imagen_over!=null?this.imagen_over:this.getImagenSeleccionado();
	},
	getHotspotX: function(image_name){
		return this[image_name+'_hotspot_x']!=null?this[image_name+'_hotspot_x']:0;
	},
	getHotspotY: function(image_name){
		return this[image_name+'_hotspot_y']!=null?this[image_name+'_hotspot_y']:0;
	}
};


function MapaAnuncio(nombre, imagen_logo, id_tipo_punto, posicion_x, posicion_y, tiene_minisitio, url_minisitio, destacado, id_tipo_contacto, url_contacto, label_contacto, id, texto_minisitio, abierto){
	if(typeof(nombre)=='object'){
		this.loadFromObject(nombre);
		return;
	}
	this.load(nombre, imagen_logo, id_tipo_punto, posicion_x, posicion_y, tiene_minisitio, url_minisitio, destacado, id_tipo_contacto, url_contacto, label_contacto, id, texto_minisitio, abierto);
}
MapaAnuncio.prototype = {
	nombre: null,
	imagen_logo: null,
	id_tipo_punto: null,
	posicion_x: null,
	posicion_y: null,
	tiene_minisitio: null,
	url_minisitio: null,
	destacado:null,
	id_tipo_contacto: null,
	url_contacto: null,
	label_contacto: null,
	id: null,
	texto_minisitio:null,
	loadFromObject: function(o){
		this.load(o.nombre, o.imagen_logo, o.id_tipo_punto, o.posicion_x, o.posicion_y, o.tiene_minisitio, o.url_minisitio, o.destacado, o.id_tipo_contacto, o.url_contacto, o.label_contacto, o.id, o.texto_minisitio, o.abierto);
	},
	load: function(nombre, imagen_logo, id_tipo_punto, posicion_x, posicion_y, tiene_minisitio, url_minisitio, destacado, id_tipo_contacto, url_contacto, label_contacto, id, texto_minisitio, abierto){
		this.nombre = nombre;
		this.imagen_logo = imagen_logo;
		this.id_tipo_punto = id_tipo_punto;
		this.posicion_x = posicion_x;
		this.posicion_y = posicion_y;
		this.tiene_minisitio = tiene_minisitio;
		this.url_minisitio = url_minisitio;
		this.destacado = destacado;
		this.id_tipo_contacto = id_tipo_contacto;
		this.url_contacto = url_contacto;
		this.label_contacto = label_contacto;
		this.id = id;
		this.texto_minisitio = texto_minisitio;
		this.abierto = abierto;
	}
};
jQuery(document).ready(function(){
	ComponentHandler.loadComponent('contenedor_mapa.marcadores.zindexes',"<style type=\"text/css\">\n"+
			".contenedor_mapa .punto_anuncio{z-index:10;}\n"+
			".contenedor_mapa .seleccionado{z-index:15;}\n"+
			".contenedor_mapa .dohover{z-index:20;}\n"+
			"</style>");
	return;
//	var newrule = jQuery(
//			"<style>\n"+
//			".contenedor_mapa .punto_anuncio{z-index:10;}\n"+
//			".contenedor_mapa .seleccionado{z-index:15;}\n"+
//			".contenedor_mapa .dohover{z-index:20;}\n"+
//			"</style>"
//	);
//	newrule.appendTo('head');
});