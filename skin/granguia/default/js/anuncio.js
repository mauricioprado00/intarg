function VisorAnuncio(contenedor, anuncio){
	if(typeof(nombre)=='object'){
		if(contenedor.contenedor!=null){
			this.loadFromObject(contenedor);
			return;
		}
	}
	this.load(contenedor, anuncio);
}
VisorAnuncio.prototype = {
	contenedor: null,
	anuncio: null,
	textos: {contacto_normal:'Contacto',contacto_boliche:'Arma tu Lista',link_minisitio:'Ver Minisitio'},
	setTextos: function(t){
		for(idx in t){
			if(this.textos[idx]!=null)
				this.textos[idx] = t[idx]; 
		}
		return this;
	},
	loadFromObject: function(o){
		this.load(o.contenedor, o.anuncio);
		return this;
	},
	load: function(contenedor, anuncio){
		this.setContenedor(contenedor);
		this.setAnuncio(anuncio);
		this.prepareTriggers();
		return this;
	},
	removeTriggers: function(){
		if(this.contenedor==null)
			return this;
		jQuery('img', this.contenedor).unbind('click');
		jQuery('img', this.contenedor).unbind('mouseover');
		jQuery('img', this.contenedor).unbind('mousedown');
		return this;
	},
	prepareTriggers: function(){
		if(this.contenedor==null)
			return this;
		jQuery('img', this.contenedor).click(function(){
			VisorAnuncio.getInstance().triggerClick();
		});
		jQuery('img', this.contenedor).mouseover(function(){
			VisorAnuncio.getInstance().triggerMouseOver();
		});
		jQuery('img', this.contenedor).mouseout(function(){
			VisorAnuncio.getInstance().triggerMouseOut();
		});
		return this;
	},
	tellto: null,
	tellme: function(o, onclick, onmouseover, onmouseout){
		this.tellto = {o: o, onclick: onclick, onmouseover: onmouseover, onmouseout:onmouseout};
		return this;
	},
	triggerClick: function(){
		if(this.tellto!=null)
			if(this.tellto.onclick!=null)
				this.tellto.onclick.apply(this.tellto.o, arguments);
//		window.console.log('triggerClick');
		return this;
	},
	triggerMouseOver: function(){
		if(this.tellto!=null)
			if(this.tellto.onmouseover!=null)
				this.tellto.onmouseover.apply(this.tellto.o, arguments);
//		window.console.log('triggerMouseOver');
		return this;
	},
	triggerMouseOut: function(){
		if(this.tellto!=null)
			if(this.tellto.onmouseout!=null)
				this.tellto.onmouseout.apply(this.tellto.o, arguments);
//		window.console.log('triggerMouseOut');
		return this;
	},
	setContenedor: function(contenedor){
		this.removeTriggers();
		if(typeof(contenedor)=='string')
			this.contenedor = jQuery(contenedor);
		else this.contenedor = contenedor;
		this.prepareTriggers();
		return this;
	},
	setAnuncio: function(anuncio){
		this.anuncio = anuncio;
		return this;
	},
	getUrl: function(url){
		return UrlHelper.getUrl(url);
	},
	getResourceUrl: function(url){
		return UrlHelper.getResourceUrl(url);
	},
	getUrlAnuncio: function(anuncio){
		if(anuncio.tiene_minisitio == 0)
			return false;
		else if(anuncio.tiene_minisitio == 1)
			return this.getResourceUrl(anuncio.url_minisitio)
		//else if(anuncio.tiene_minisitio == 2)
		return anuncio.url_minisitio;
	},
	displayAnuncio: function(anuncio){
		if(anuncio!=null)
			this.setAnuncio(anuncio);
		anuncio = this.anuncio;
		this.contenedor.html('');
		jQuery('<img />')
			.attr('alt', anuncio.nombre)
			.attr('title', anuncio.nombre)
			.attr('src', this.getResourceUrl(anuncio.imagen_logo))
			.width(191)
			.height(187)
			.appendTo(this.contenedor)
		;
		var jqbottombox = jQuery('<div class="bottombox"></div>').appendTo(this.contenedor);
		var url = this.getUrlAnuncio(anuncio);
		if(url!=false){
			var texto_minisitio = this.textos.link_minisitio;
			if(anuncio.texto_minisitio!=null && anuncio.texto_minisitio!=''){
				texto_minisitio = anuncio.texto_minisitio;
			}
			jQuery('<a></a>')
				.attr('href', url)
				.attr('target','_blank')
				.text(texto_minisitio)
				.appendTo(jqbottombox)
			;
		}
		if(anuncio.id_tipo_contacto>1){//tieneContacto()//1 es sin contacto
			var tipo_contacto = anuncio.id_tipo_contacto==2?'normal':'boliche';
			var texto = anuncio.label_contacto;
			if(texto==null||texto=='')
				texto = anuncio.id_tipo_contacto==2?this.textos.contacto_normal:this.textos.contacto_boliche;
			var url = this.getUrl(anuncio.url_contacto);
			jQuery('<a></a>')
				.attr('class','link_contacto link_contacto_'+tipo_contacto)
				.attr('href', url)
				.text(texto)
				.appendTo(jqbottombox)
			;
		}
		jqbottombox = null;
		this.prepareTriggers();
//		window.console.log('mostrar anuncio');
//		window.console.log(anuncio);
		return this;
	}
};

/*<para crear un singleton>*/
VisorAnuncio.instancia = new VisorAnuncio();
VisorAnuncio.getInstance = function(){
	if(VisorAnuncio.instancia == null)
		VisorAnuncio.instancia = new VisorAnuncio();
	return VisorAnuncio.instancia; 
}
for(idx in VisorAnuncio.getInstance()){
	var instancia = VisorAnuncio.getInstance();
	if(typeof(instancia[idx])=='function'){
		(function(funcion){
			VisorAnuncio[idx] = function(){
				return funcion.apply(VisorAnuncio.getInstance(), arguments);
			}
		})(instancia[idx]);
	}
	instancia = null;
	//window.console.log(idx, );
}
/*</para crear un singleton>*/



//VisorAnuncio.doAlgo('kradkk');
//window.console.log(VisorAnuncio.getInstance() == VisorAnuncio.getInstance());
//window.console.log(VisorAnuncio.instancia);

