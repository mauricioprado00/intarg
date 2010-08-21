window.LayeredNavigation = {
	jqSelectorCategoria: null,
	jqSelectorMarca: null,
	jqTextoBusqueda: null,
	jqForm: null,
	init:function(){
		var that = this;
		this.jqSelectorCategoria = jQuery('#selector_categoria');
		this.jqSelectorMarca = jQuery('#selector_marca');
		this.jqTextoBusqueda = jQuery('#texto_busqueda');
		this.jqForm = jQuery('#layered_navigation form');
		this.jqSelectorCategoria.change(function(){
			that.CategoriaChange();
		});
		this.jqSelectorMarca.change(function(){
			that.MarcaChange();
		});
		this.jqTextoBusqueda.change(function(){
			that.TextoChange();
		})
	},
	CategoriaChange: function(){
		this.jqSelectorMarca.each(function(){this.selectedIndex = 0;});//.val('');
		this.Search();
	},
	MarcaChange: function(){
		this.jqSelectorCategoria.each(function(){this.selectedIndex = 0;});//.val('');
		this.Search();
	},
	TextoChange: function(){
		this.Search();
	},
	Search: function(){
		jQuery.blockUI({
				message: jQuery('<h2>Realizando búsqueda</h2>')
		});
		this.jqForm.submit();
	},
	SearchByMarca: function(marca){
		this.jqSelectorMarca.val(marca)
		this.Search();
	}
};
jQuery(document).ready(function(){
	LayeredNavigation.init();
	//window.console.log(LayeredNavigation);
})
window.ir_a_marca = function(l){
	window.LayeredNavigation.SearchByMarca(jQuery(l).attr('rel'));
	return false;
}