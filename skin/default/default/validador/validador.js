//si queres agregar funciones validadoras hacelo aca:

		//de aca para abajo es extensible, hay que agregar funciones:
		//function mensaje_valida_[nombre del validador]([valor del campo],[parametro del array])
		function valida_empty(valor, debe){
			var empty = valor=='';
			return empty==debe;
		}
		function mensaje_valida_empty(valor, debe){
			if(!valida_empty(valor, debe)){
				return (debe?'':'no ')+'debe estar vacio';
			}
		}
		
		function valida_decimal(valor, cantidad, caracter){
			if(valor=='')return true;
			var re = "";
			if(cantidad==null){
				re = "^-?[0-9]+(["+caracter+"][0-9]+)?$";
			}
			else{
				re = "^-?[0-9]+(["+caracter+"][0-9]{1,"+cantidad+"})?$";
			}//patt1=new RegExp(^[0-9]+.[0-9]{3}$);
			var patt1=new RegExp(re);
			var resultado = patt1.test(valor);
			//window.console.log(valor, cantidad, resultado);
			return(resultado);
		}
		function mensaje_valida_decimal(valor, cantidad){
			var caracter = '.';
			if(typeof(cantidad)=='object'){
				if(cantidad.length<=0){
					kradkk.kradkk();//invalidaar
				}
				if(cantidad.length>1){
					caracter = cantidad[1];
				}
				cantidad = cantidad[0];
			}
			if(!valida_decimal(valor, cantidad, caracter)){
				if(cantidad==null){
					return 'debe ser decimal';
				}
				else{
					var mensaje = 'debe tener %cantidad% decimales';
					return mensaje.split('%cantidad%').join(cantidad); 
				}
			}
		}
		function valida_entero(valor, cantidad){
			if(valor=='')return true;
			if(cantidad==null){
				var patt1=new RegExp("^-?[0-9]+$");
			}
			else{
				var patt1=new RegExp("^-?[0-9]{"+cantidad+"}$");
			}
			return(patt1.test(valor));
		}
		function mensaje_valida_entero(valor, cantidad){
			if(!valida_entero(valor, cantidad)){
				if(cantidad==null){
					return 'debe ser entero';
				}
				else{
					var mensaje = 'debe tener %cantidad% digitos';
					return mensaje.split('%cantidad%').join(cantidad); 
				}
			}
		}
		function valida_lt(valor, than){
			if(valor=='')return true;
			var valor = parseFloat(valor, than);
			if(than<=valor){
				return false;
			} 
			return true;
		}
		function mensaje_valida_lt(valor, than){
			if(!valida_lt(valor, than)){
				return 'debe ser menor a '+than;
			}
		}
		function valida_gt(valor, than){
			if(valor=='')return true;
			var valor = parseFloat(valor, than);
			if(than>=valor){
				return false;
			} 
			return true;
		}
		function mensaje_valida_gt(valor, than){
			if(!valida_gt(valor, than)){
				return 'debe ser mayor a '+than;
			}
		}
		function valida_lte(valor, than){
			if(valor=='')return true;
			var valor = parseFloat(valor, than);
			if(than<valor){
				return false;
			} 
			return true;
		}
		function mensaje_valida_lte(valor, than){
			if(!valida_lte(valor, than)){
				return 'debe ser menor o igual a '+than;
			}
		}
		function valida_gte(valor, than){
			if(valor=='')return true;
			var valor = parseFloat(valor, than);
			if(than>valor){
				return false;
			} 
			return true;
		}
		function mensaje_valida_gte(valor, than){
			if(!valida_gte(valor, than)){
				return 'debe ser mayor o igual a '+than;
			}
		}
		
		function valida_porcentaje(valor, debe){
			if(valor=='')return true;
			var numericExpression = /^[0-9]+$/;
			var fractionExpression = /^[0-9]+,[0-9]+$/;
			var numericPercentageExpression = /^[0-9]+%$/;
			var fractionPercentageExpression = /^[0-9]+,[0-9]+%$/;
			
			//si esta buit no es mira, ja es mirara en un altre filtre
			var es_porcentaje = false;
			if(valor.match(numericExpression) || 
					valor.match(fractionExpression) ||
					valor.match(numericPercentageExpression) || 
					valor.match(fractionPercentageExpression))
			{
				es_porcentaje = true;
			}
			return es_porcentaje=debe;
		}
		function mensaje_valida_porcentaje(valor, debe){
			if(!valida_porcentaje(valor, debe)){
				return (debe?'':'no ')+'debe ser un porcentaje';
			}
		}
		function valida_hora(valor, debe){
			if(valor=='')return true;
			var hourExpression = /^[0-9]{2}:[0-9]{2}$/;
			//si esta buit no es mira, ja es mirara en un altre filtre
			var es_hora = false;
			if(valor.match(hourExpression))
				es_hora = true;
			return es_hora == debe;
		}
		function mensaje_valida_hora(valor, debe){
			if(!valida_hora(valor, debe)){
				return (debe?'':'no ')+'debe ser una hora';
			}
		}
		function valida_fecha(valor, debe){
			if(valor=='')return true;
			var dataExpression = /^[0-9]{2}-[0-9]{2}-[0-9]{4}$/;
			var es_fecha = false;
			if(valor.match(dataExpression)){
				es_fecha = true;
			}
			return es_fecha == debe;
		}
		function mensaje_valida_fecha(valor, debe){
			if(!valida_fecha(valor, debe)){
				return (debe?'':'no ')+'debe ser una fecha';
			}
		}
		function valida_email(valor){
			if(valor=='')return true;
			var re = "";
			re = "\\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,4}\\b";
			var patt1 = new RegExp(re,'i');
			var resultado = patt1.test(valor);
			return(resultado);
		}
		function mensaje_valida_email(valor){
			if(!valida_email(valor)){
				return 'debe ser un email';
			}
		}
		
		
		

// de aca para abajo no deberia modificarse
		function validar_estatico(mas_errores, css, opciones, use_wait, wait_message){
			return validar_estatico_en(null,mas_errores, css, opciones, use_wait, wait_message);
		}
		function validar_estatico_en(donde,mas_errores, css, opciones, use_wait, wait_message){
			return validar_en(donde,true,mas_errores, css, opciones, use_wait, wait_message);
		}
		function validar(estatico,mas_errores, css, opciones, use_wait, wait_message){
			if(typeof(estatico)=='object'){
				return validar_params(estatico);
			}
			return validar_en(null,estatico,mas_errores, css, opciones, use_wait, wait_message);
		}
		function validar_params(params){
			return validar_en(
				params.donde,
				params.estatico,
				params.mas_errores,
				params.css,
				params.opciones,
				params.use_wait,
				params.wait_message
			);
		}
		function validar_en(donde,estatico,mas_errores, css, opciones, use_wait, wait_message){
			if(estatico==null)estatico=false;
			else estatico=estatico?true:false;
			if(mas_errores!=null)if(mas_errores.length==null||mas_errores.length==0)mas_errores=null;
			if(use_wait==null)
				use_wait = false;
			else
				use_wait = use_wait?true:false;
			if(use_wait || wait_message!=null){//maldito blockui y su setTimeout, no me andubo
				if(wait_message==null || typeof(wait_message)!='string')
					wait_message = 'Aguarde un momento mientras se validan los campos.';
				//window.console.log(use_wait || wait_message!=null, wait_message);
				//window.console.log(donde);
				//show_messages([wait_message], true, null, null, 'img_wait');
				//show_messages(['prueba'], true, null, {onBlock:function(){window.console.log('te bloquee');}}, 'img_wait');
				//setTimeout(function(){window.console.log('blockeante')},100);
				//return;
				
			}
			var errores = validar_get_errores(donde);
			if(mas_errores!=null){
				for(idx in mas_errores)
					errores.push(mas_errores[idx]);
			}
			if(errores.length){
				//errores = errores.join('<br />');
				show_messages(errores, estatico, css, opciones);
				return false;
			}
			else{
				//hide_messages();
			}
			return true;
		}
		function validar_get_errores(donde){
			var errores = [];
			var jqcampos = null;
			jqcampos = jQuery('[data-validator]:not([disabled])', donde);//los campos disabled no los puede editar el usuario, por lo que seria innecesarios validarlos
			jqcampos.each(function(){
				var jqthis = jQuery(this);
				if(this.prevBorder!=null)
					jqthis.css('border', this.prevBorder);
				var campo = jqthis.attr('data-campo');
				try{
					var validadores = null;
					//window.console.log(jqthis);
					var js = 'validadores = '+jqthis.attr('data-validator')+';';
					//window.console.log(js);
					eval(js);
					if(validadores != null){
						//window.console.log(validadores);
						for(validador in validadores){
							var funcion_validadora = null;
							var params = validadores[validador];
							try{
								var js = 'funcion_validadora = mensaje_valida_'+validador+';';
								//window.console.log(js);
								eval(js);
								if(funcion_validadora){
									//window.console.log(funcion_validadora, params);
									var r = funcion_validadora(jqthis.val(), params, errores);
									if(r!=null){
										var mensaje_personalizado_generico = jqthis.attr('data-mensaje');
										var mensaje_personalizado_especifico = jqthis.attr('data-mensaje-'+validador);
										if(mensaje_personalizado_especifico!=null)r = mensaje_personalizado_especifico.split('%field').join(campo);
										else if(mensaje_personalizado_generico!=null)r = mensaje_personalizado_generico.split('%field').join(campo);
										else{
											var mensaje = '';
											var addFieldName = true;
											if(typeof(r)=='string'){
												mensaje = r;
											}
											else if(typeof(r)=='object'){
												if(r.mensaje!=null)
													mensaje = r.mensaje;
												if(r.addFieldName!=null)
													addFieldName = r.addFieldName?true:false;
											}
											if(addFieldName){
												if(mensaje.indexOf('%field')!=-1){
													addFieldName = false;
													mensaje = mensaje.split('%field').join(campo);
												}
											}
											r = (addFieldName?campo + ' ':'') + mensaje;
										}
										//window.console.log('data-mensaje, ',mensaje_personalizado_generico,', data-mensaje-'+validador+', ', mensaje_personalizado_especifico, this);
										errores.push(r);
										this.prevBorder = jqthis.css('border');
										jqthis.css('border', '1px solid red');
										if(mensaje_personalizado_generico!=null)
											return;
									}
									//window.console.log(r);
								}
							}
							catch(e){
								alert(e);
								errores.push('No se pudo validar "'+campo+'" ('+validador+')');
								//window.console.log('no funca la funcion ');
							}
						}
					}
				}
				catch(e){
					errores.push('No se pudo validar "'+campo+'"');
					//window.console.log('no funca el validador');
				}
			});
			return errores;
		}
		function hide_messages(){
			jQuery.unblockUI();
		}
		function show_messages(errors, estatico, mas_css, mas_opciones, img_classname){
			if(img_classname==null)
				img_classname = 'img_error';
			var cantidad = errors.length;
			var tiempo = cantidad*2000;
			errors = errors.join('<br />');
			var jqbox = jQuery('<div class="validador">'+(estatico?'<div class="cerrar">X</div>':'')+'		<div style="" id="valida">			<div class="'+img_classname+'">				<div class="reg2_not" style="text-align: left; padding-left: 5px;"></div>				<div style="clear:both;"></div>			</div>		</div>	</div>');
			if(estatico)
				jQuery('.cerrar', jqbox).click(function(){jQuery.unblockUI()});
	
/*			
	<div class="validador">
		<div style="" id="valida">
			<div class="img_error">
				<div class="reg2_not" style="text-align: left; padding-left: 5px;"></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
*/
			var jqvalida = jQuery(".reg2_not", jqbox);
			jqvalida.html(errors);
			var css = {
					//width: '350px',
					top: '100px',
					left: '',
					right: '10px',
					border: '1px solid #333333',
					padding: '5px',
					backgroundColor: '#FFF',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .8,
					color: '#159ADF'
				};
			if(mas_css!=null){
				for(idx in mas_css)
					css[idx] = mas_css[idx];
			}
			var opciones = {
				//message: jqbox,
				fadeIn: 700,
				fadeOut: 700,
				//timeout: tiempo,
				showOverlay: false,
				centerY: false
			};
			if(mas_opciones!=null)
				for(idx in mas_opciones)
					opciones[idx] = mas_opciones[idx];
			opciones.css = css;
			opciones.message = jqbox;
			if(!estatico){
				opciones.timeout = tiempo;
			}
			jQuery.blockUI(opciones);
		}

