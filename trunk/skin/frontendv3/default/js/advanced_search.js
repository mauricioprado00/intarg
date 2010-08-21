window.AddFiltroWindow = {
	jqBtnAddFiltro: null,
	jqFieldsetFiltro: null,
	jqBtnAgregarFiltro: null,
	jqBtnCancelarAgregarFiltro: null,
	jqAdvancedSearchForm: null,
	loadWindow: function(){
		jQuery.blockUI({ 
			message: this.jqFieldsetFiltro,
			css: {
	            width: '140px',
				//height: '90%',
				//overflow: 'scroll',
				cursor:'auto',
				'margin-left':'auto',
				'margin-right':'auto',
				//top: '5%', left: '15%',
				'text-align':'left',
				padding:'10px'
	        }
		});
	},
	unloadWindow: function(cancelar){
		var that = this;
		params = {
			fadeOut:false,
			onUnblock:function(){
				jQuery.blockUI({message:'agregando filtro'});
				window.AdvancedSearch.refrescarBusqueda();
			}
		}
		
		if(cancelar==true){
			params = null;
			window.AdvancedSearch.onCancelarAgregarFiltro();
		}
		jQuery.unblockUI(params);
	},
	init: function(){
		var that = this;
		this.jqBtnAddFiltro = jQuery('.advanced_search_form .btn_add_filtro');
		this.jqBtnAgregarFiltro = jQuery('.advanced_search_form .btn_agregar_filtro');
		this.jqBtnCancelarAgregarFiltro = jQuery('.advanced_search_form .btn_cancelar_agregar_filtro');
		this.jqFieldsetFiltro = jQuery('.contenedor_fieldset_filtro');
		this.jqAdvancedSearchForm = jQuery('.advanced_search_form form');
		
		this.jqBtnAddFiltro
			.click(function(){
				that.loadWindow();
			})
		;
		this.jqBtnAgregarFiltro
			.click(function(){
				that.unloadWindow();
				//that.jqFieldsetFiltro.appendTo('.advanced_search_form .buscador_productos');
			})
		;
		this.jqBtnCancelarAgregarFiltro
			.click(function(){
				that.unloadWindow(true);
			})
		;
		
	}
};
window.AdvancedSearch = {
	listaajax: null,
	jqCampoBusqueda:null,
	jqTextoBusqueda: null,
	jqAutocompleteBox: null,
	jqAdvancedSearchForm: null,
	strPrevSearch: null,
	strSearchUrl: null,
	ishover: null,
	minimunSearchCharacters: 2,
	maxAutocompleteOptions: 0,
	withBlockui:false,
	init: function(){
		var with_blockui = jQuery('.advanced_search_form .with_blockui').val();
		this.withBlockui = with_blockui=='1'?true:false;
		if(this.isWithBlockui()){
			window.AddFiltroWindow.init();
		}
		this.jqCampoBusqueda = jQuery('.advanced_search_form [name=campo_busqueda]');
		this.jqCampoOrden = jQuery('.advanced_search_form [name=campo_orden]');
		this.jqTextoBusqueda = jQuery('.advanced_search_form [name=texto_busqueda]');
		this.jqAutocompleteBox = jQuery('.advanced_search_form .autocomplete_box');
		this.jqAdvancedSearchForm = jQuery('.advanced_search_form form');
		this.strSearchUrl = this.jqTextoBusqueda.attr('data-searchurl');
		this.listaAjax = jQuery.manageAjax({maxReq: 1});
		this.closeAutoComplete();
		var that = this;
		jQuery('.advanced_search_form .max_autocomplete_options')
			.each(function(){
				if(!this.value)
					return;
				var max = new Number(parseInt(this.value));
				if(!max)
					return;
				that.maxAutocompleteOptions = max;
			})
		;
		this.jqCampoBusqueda
			.change(function(){
				if(this.selectedIndex==null)
					return;
				
				var selected_option = this.options[this.selectedIndex];
				var jqSelectedOption = jQuery(selected_option);
				var json_valores_texto = jqSelectedOption.attr('data-valorestexto');
				that.setResultados(null);
				if(!json_valores_texto){
					that.search();
					return;
				}
				var valores_texto = null;
				try{
					eval('valores_texto = '+json_valores_texto+';'); 
				}catch(e){;}
				if(!valores_texto){
					that.search();
					return;
				}
				that.setResultados(valores_texto);
				that.jqTextoBusqueda[0].focus();
				//window.console.log(valores_texto);
				//this.search();//that.checkChanges();
			})
		;
		if(this.isWithBlockui())
			this.jqCampoOrden
				.change(function(){
					that.refrescarBusqueda();
				})
			;
		this.jqTextoBusqueda
			.blur(function(){
				that.closeAutoComplete();
			})
			.focus(function(){
				that.openAutoComplete();
			})
			.keyup(function(e){
				try{
					if(e.keyCode==27){
						that.closeAutoComplete();
						return;
					}
					
				}catch(e){;}
				that.search();//that.checkChanges();
			})
			.dblclick(function(e){
				that.openAutoComplete();
			})
		;
	},
	isWithBlockui: function(){
		return(this.withBlockui?true:false);	
	},
	onCancelarAgregarFiltro: function(){
		this.jqTextoBusqueda.val('');
		this.jqAutocompleteBox.html('');
		this.closeAutoComplete();	
	},
	onFiltroQuitado: function(filtro){
		if(this.isWithBlockui()){
			jQuery.blockUI({message:'quitando filtro'});
			this.refrescarBusqueda();
		}
	},
	onFiltroCambiado: function(filtro){
		if(this.isWithBlockui()){
			jQuery.blockUI({message:'modificando filtro'});
			this.refrescarBusqueda();
		}
	},
	openAutoComplete: function(){
		if(!this.jqAutocompleteBox.text())
			return;
		this.jqAutocompleteBox.css('display','block');
	},
	closeAutoComplete: function(force){
		if(this.ishover&&force==null)
			this.hideWhenIsNotHover = true;
		else{
			this.hideWhenIsNotHover = false;
			this.jqAutocompleteBox.css('display','none');
		}
	},
	notHover: function(){
//		try{
//			window.console.log("hover out");
//		}catch(e){;}
		this.ishover = false;
		if(this.hideWhenIsNotHover)
			this.closeAutoComplete();
		
	},
	isHover: function(){
//		try{
//			window.console.log("hover");
//		}catch(e){;}
		this.ishover = true;
	},
//	checkChanges: function(){
////		var search = false;
////		if(this.strPrevSearch==null)
////			search = true
////		else if(this.strPrevSearch!=this.jqTextoBusqueda.val())
////			search = true;
////		if(search)
//		this.search();
//	},
	refrescarBusqueda:function(){
		this.jqAdvancedSearchForm.submit();
	},
	setLoading: function(t){
		this.jqAutocompleteBox.html('<div class="buscando">'+t+'</div>');
	},
	clearSelected: function(){
		jQuery('li.selected', this.jqAutocompleteBox)
			.each(function(){
				jQuery(this).removeClass('selected');
			})
		;
	},
	selectItem: function(item){
		var jqitem = jQuery(item);
		jqitem.addClass('selected');
		this.jqTextoBusqueda.val(jqitem.text());
	},
	useItem: function(item){
//		try{
//			window.console.log(jQuery(item).text());
//		}catch(e){;}
		this.closeAutoComplete(true);
		this.clearSelected();
		this.selectItem(item);
		this.setLastSearchParams(this.getCurrentSearchParams());
	},
	setResultados: function(r){
		var that = this;
		if(r){
			var html_items = '';
			var cantidad_items = 0;
			for(varname in r){
				html_items += '<li value="'+varname+'">' + r[varname] + '</li>';
				cantidad_items++;
			}
			if(html_items!=''){
				this.notHover();
				html_items = '<ul>' + html_items + '</ul><div class="clearer"></div><div class="clearer"></div>';
				this.jqAutocompleteBox.html(html_items+'<span class="min_width"></span>');
				jQuery('li', this.jqAutocompleteBox)
					.hover(function(){
						jQuery(this).addClass('li_hover');
					})
					.mouseout(function(){
						jQuery(this).removeClass('li_hover');
					})
					.click(function(){
						that.useItem(this);
					})
				;
				
				var jqul = jQuery('ul', this.jqAutocompleteBox)
					.mouseover(function(){
						that.isHover();
					})
					.mouseout(function(){
						that.notHover();
					})
				;
				if(this.maxAutocompleteOptions){
					//jqul
					var do_strange_thing = false;
					if(window.imOnIE){
						if(window.ieVersion>6)
							do_strange_thing = true;
					}else{
						try{var x = document.all;/*chome,opera,etc*/
							do_strange_thing = true;
						}catch(e){;}
					}
					if(do_strange_thing){
						var prev_disp = this.jqAutocompleteBox.css('display');
						this.jqAutocompleteBox.css('display','block');
						this.jqAutocompleteBox.css('overflow-y','visible');
						var width = jQuery('ul',this.jqAutocompleteBox).width();
						this.jqAutocompleteBox.css('width', width+'px');
						this.jqAutocompleteBox.css('display',prev_disp);
					}
					
					this.jqAutocompleteBox
						.css('height',12*this.maxAutocompleteOptions)
						.css('overflow-y',cantidad_items>this.maxAutocompleteOptions?'scroll':'visible')
						.css('overflow-x','hidden')
					;
				}
			} 
		}
		else{
			this.jqAutocompleteBox.html('');
		}
	},
	sameSearch: function(params){
		if(this.lastSearchParams==null){
			this.setLastSearchParams(params);
			return(false);
		}
		else{
			var igual = true;
			var lastSearchParams = this.getLastSearchParams();
			for(varname in lastSearchParams){
				if(params[varname]!=lastSearchParams[varname]){
					igual = false;
					break;
				}
			}
			if(igual){
//				try{
//					window.console.log('igual busqueda');
//				}catch(e){;}
				return(true);
			}
			return(false);
		}
	},
	setLastSearchParams:function(params){
		this.lastSearchParams = params;
	},
	getLastSearchParams:function(){
		return(this.lastSearchParams);
	},
	getCurrentSearchParams: function(){
		var c = this.jqCampoBusqueda.val();
		var t = this.jqTextoBusqueda.val();
		var params = {
			campo: c,
			texto: t
		};
		return(params);
	},
	avoidSearch: function(){
		if(!this.jqCampoBusqueda.length)
			return(false);
		var campoBusqueda = this.jqCampoBusqueda[0]; 
		var idx = campoBusqueda.selectedIndex;
		if(idx==null)
			return(false);
		var jqSelectedOption = jQuery(campoBusqueda.options[idx]);
		if(jqSelectedOption.attr('data-esautobuscable')==null)
			return(true);
		return(false);
	},
	search: function(){
		var params = this.getCurrentSearchParams();
		if(params.texto.length<this.minimunSearchCharacters){
			this.jqAutocompleteBox.html('');
			this.closeAutoComplete();
			return;
		}
		if(this.sameSearch(params))
			return;
		if(this.avoidSearch())
			return;
		this.setLastSearchParams(params);
		this.listaAjax.abort();
		this.openAutoComplete();
		this.setLoading(params.texto);
		var that = this;
		this.listaAjax.add({
			type: "POST",
			data: params,
			url: this.strSearchUrl,
			success: function(strdata){
				var jsdata = null;
				try{
					eval('jsdata = '+strdata);
				}catch(e){;}
				if(jsdata!=null&&jsdata.length>0){
					that.setResultados(jsdata);
				}
				else{
					that.jqAutocompleteBox.html('');
					that.closeAutoComplete();
				}
			}	
		});
//		this.listaAjax.add({
//		//		type: "POST",
////				data: {texto:this.jqTextoBusqueda.val()},
////				url: 'index.php?modulo=consulta',//va a al archivo datos_consulta_inicio
////				success: function(data){
////					
////				}
//		)};
	}
};
window.ShowFiltros = {
	jqShowFiltros: null,
	init: function(){
		var that = this;
		this.jqShowFiltros = jQuery('.show_filtros');
		jQuery('.show_filtro',this.jqShowFiltros)
			.each(function(){
				that.alterShowFiltro(this);
			})
		;
	},
	alterShowFiltro: function(div_show_filtro){
		if(div_show_filtro==null)
			return;
		var jqShowFiltro = jQuery(div_show_filtro);
		var jqPanelShowFiltro = jqShowFiltro.append('<div class="panel_show_filtro"><div class="panel_show_filtro_button panel_show_filtro_edit"></div><div class="panel_show_filtro_button panel_show_filtro_delete"></div></div>');
		
		var campo_busqueda = jQuery('[name*=campo_busqueda]',jqShowFiltro).val();
		var jqoption = jQuery('.advanced_search_form [name=campo_busqueda] option[value='+campo_busqueda+']');
		var jqcampo_texto = jQuery('[name*=texto_busqueda]', jqShowFiltro);
		var campo_texto = jqoption.text();
		var jqlabel = jQuery('.show_filtro_label', jqShowFiltro);
		
		jqShowFiltro[0].setTitle = function(){
			var title = '';
			title = campo_texto+': '+jqlabel.text();
			if(window.imOnIE||true){
				title = 'filtro\r\n'+title;
			}
			jQuery(this).attr('title', title);
		}
		jqShowFiltro[0].setTitle();
		jqShowFiltro
			.mouseover(function(){
				if(!this.count_hover)
					this.count_hover = 0;
				this.count_hover ++;
				jQuery('.show_filtro_hover').removeClass('show_filtro_hover');
				jQuery(this).addClass('show_filtro_hover');
			})
			.mouseout(function(){
				this.count_hover --;
				this.count_hover = this.count_hover<0?0:this.count_hover;
				var that = this;
				setTimeout(
					function(){
						if(that.count_hover==0)
							jQuery(that).removeClass('show_filtro_hover');
					},
					500
				);
			})
		;
		jQuery('.panel_show_filtro_delete', jqShowFiltro)
			.attr('title','eliminar filtro')
			.click(function(){
				if(confirm('¿Desea continuar eliminando el filtro "'+ campo_texto +'"?')){
					jqShowFiltro.each(function(){this.parentNode.removeChild(this);})
					window.AdvancedSearch.onFiltroQuitado(this);
				}
			})
		;
		jQuery('.panel_show_filtro_edit', jqShowFiltro)
			.attr('title','editar filtro')
			.click(function(){
				do{
					var nuevo_valor = prompt('Ingrese nuevo valor para el filtro "'+ campo_texto +'": ', jqlabel.text());
					if(nuevo_valor==null)
						return;
					if(nuevo_valor.length>2)
						break;
					alert("Debe ingresar al menos 2 letras");
				}while(true);
				jqcampo_texto.val(nuevo_valor);
				jqlabel.html(nuevo_valor);
				jqShowFiltro[0].setTitle();
				window.AdvancedSearch.onFiltroCambiado(this);
			})
		;
	}
	
};
jQuery(document).ready(function(){
	AdvancedSearch.init();
	ShowFiltros.init();
});