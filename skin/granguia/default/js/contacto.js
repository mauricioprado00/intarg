function input_contacto_capcha(jqform,elid){
	//regenero el src
	var jqimg = jQuery('#'+elid+' .capcha img');
	var src = jqimg.attr('src');
	jqimg.attr('src', '');
	jqimg.attr('src', src.split('?')[0]+'?'+Math.random());
	jQuery('#'+elid+' .capcha input.aceptar').unbind('click').click(function(){
		jQuery.blockUI({
			message:'<div class="contacto_mensaje contacto_wait"><div class="mensaje">Aguarde un momento</div></div>',
			css:{
				'border-radius':'8px',
				'-moz-border-radius':'8px',
				'-webkit-border-radius':'8px',
				padding:'15px',
				border:'7px solid #BBBBFF'
			}
		})
		var vars = {};
		jqform.find('[name]').each(function(){
			vars[this.name] = this.value;
		});
		vars['capcha_code'] = jQuery('#'+elid+' .capcha input').val();
		jQuery.post(jqform.attr('action'),vars,function(data){
			var objeto = null;
			try{
				eval('objeto = '+data);
			}catch(e){
				objeto = null;;
			}
			var mensaje = 'Ha ocurrido un error durante el envio';
			var resultado = false;
			if(objeto!=null){
				if(objeto.mensaje!=null)
					mensaje = objeto.mensaje;
				if(resultado!=null)
					resultado = objeto.resultado?true:false;
			}
			if(!resultado){
				var ventana = jQuery('<div class="contacto_mensaje contacto_mensaje_ko"><div class="mensaje"></div><div><input type="button" value="Reintentar" class="reintentar"/><input type="button" value="Cancelar" class="cerrar"/></div></div>')
				var jqmensaje = jQuery('.mensaje', ventana);
				var jqcerrar = jQuery('.cerrar', ventana);
				var jqreintentar = jQuery('.reintentar', ventana);
				jqmensaje.html(mensaje);
				jqcerrar.click(function(){
					jQuery.unblockUI();
				});
				jqreintentar.click(function(){
					input_contacto_capcha(jqform,elid);
				});
				jQuery.blockUI({
					message:ventana,
					css:{
						'border-radius':'8px',
						'-moz-border-radius':'8px',
						'-webkit-border-radius':'8px',
						padding:'15px',
						border:'7px solid #BBBBFF'
					}
				});
			}
			else{
				var ventana = jQuery('<div class="contacto_mensaje contacto_mensaje_ok"><div class="mensaje"></div><div><input type="button" value="Aceptar" class="cerrar" /></div></div>');
				var jqmessage = jQuery('.mensaje', ventana).html(mensaje);
				var jqcerrar = jQuery('.cerrar', ventana);
				jqcerrar.click(function(){jQuery.unblockUI();});
				jQuery.blockUI({
					message:ventana,
					css:{
						'border-radius':'8px',
						'-moz-border-radius':'8px',
						'-webkit-border-radius':'8px',
						padding:'15px',
						border:'7px solid #BBBBFF'
					}
				});
			}
		});
	})
	jQuery.blockUI({
		message: jQuery('.capcha'),
		css:{
			'border-radius':'8px',
			'-moz-border-radius':'8px',
			'-webkit-border-radius':'8px',
			padding:'15px',
			border:'7px solid #BBBBFF'
		}
	});
	return false;
}
function enviar_formulario_contacto(){
	var elid = this.parentNode.id;
	var jqform = jQuery(this);
	if(!validar({
		donde: '#'+elid,
		estatico:true,
		css:{opacity:1}
		})){
			return false;
		}
	input_contacto_capcha(jqform, elid);
	return false;
}