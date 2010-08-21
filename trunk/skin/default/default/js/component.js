function ComponentHandler(components){
	if(typeof(components)=='object'){
		if(components.components!=null){
			this.loadFromObject(components);
			return;
		}
	}
	this.load(components);
}
ComponentHandler.prototype = {
	components: null,
	assoc_components: null,
	loadFromObject: function(o){
		this.load(o.components);
	},
	load: function(components){
		this.setComponents(components);
	},
	setComponents: function(components){
		//window.console.log(components);
		this.components = components;
		this.assoc_components = {};
		for(idx in this.components){
			var uid = this.components[idx];
			this.assoc_components[uid] = true;
		} 
	},
	isLoaded: function(uid){
		var is_loaded = this.assoc_components[uid]!=null;
		window.console.log('isLoaded "'+uid+'": '+(is_loaded?'si':'no'));
		return is_loaded;
	},
	loadComponent: function(uid, component_html){
		//window.console.log('try loading', uid, this.isLoaded);
		if(this.isLoaded(uid)){
			window.console.log('ya esta cargado el componente');
			return;
		}
		jQuery(component_html).attr('data-uid',uid).appendTo('head');
		this.assoc_components[uid] = true;
		window.console.log('componente cargado');
	}
};

/*<para crear un singleton>*/
ComponentHandler.instancia = new ComponentHandler();
ComponentHandler.getInstance = function(){
	if(ComponentHandler.instancia == null)
		ComponentHandler.instancia = new ComponentHandler();
	return ComponentHandler.instancia; 
}
for(idx in ComponentHandler.getInstance()){
	var instancia = ComponentHandler.getInstance();
	if(typeof(instancia[idx])=='function'){
		(function(funcion){
			ComponentHandler[idx] = function(){
				return funcion.apply(ComponentHandler.getInstance(), arguments);
			}
		})(instancia[idx]);
	}
	//window.console.log(idx, );
}
/*</para crear un singleton>*/


/*inicializo*/
ComponentHandler.setComponents(window.added_components);