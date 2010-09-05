<?
class Admin_ResultadoEsperado_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden',
			'medios_verificacion',
			'addEditIndicador','deleteIndicador',
			'addEditMedioVerificacion','deleteMedioVerificacion'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_resultado_esperado');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_resultado_esperado=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ResultadoEsperado()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar ResultadoEsperado.');
		}
		else{
			if(isset($id_resultado_esperado)){
				Admin_ResultadoEsperado_Helper::actionEliminarResultadoEsperado($id_resultado_esperado);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_resultado_esperado=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ResultadoEsperado()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar ResultadoEsperado.');
			//$mensajes[] = 'No tiene permitido editar resultado_esperadoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_indicadores = $post&&$post->hasIndicadores()?$post->getIndicadores():null;
			$post_eliminar_indicadores = $post&&$post->hasEliminarIndicadores()?$post->getEliminarIndicadores():null;
			$post_resultado_esperado = $post&&$post->hasResultadoEsperado()?$post->GetResultadoEsperado(true):null;
			$resultado_esperado = new Inta_Model_ResultadoEsperado();
			//echo Core_Helper::DebugVars($post_problemas_asociados);
			if(isset($post_resultado_esperado)){
				$resultado_esperado->loadFromArray($post_resultado_esperado->getData());
				$agregando = $resultado_esperado->getId()?false:true;
				//echo Core_Helper::DebugVars($resultado_esperado->getData());
				$guardado = 
					Admin_ResultadoEsperado_Helper::actionAgregarEditarResultadoEsperado($resultado_esperado, $post_indicadores, $post_eliminar_indicadores)?true:false;
			}
			else{
				$agregando = !isset($id_resultado_esperado) || !$id_resultado_esperado;
				if(isset($id_resultado_esperado)){
					$resultado_esperado->setId($id_resultado_esperado);
					$resultado_esperado->load();
				}
				if(!$resultado_esperado->getId())
					$resultado_esperado->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
				if($agregando){
					Core_App::getLayout()
						->addActions('entity_new')
					;
				}
			}

			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_resultado_esperado)?'seteado':'no seteado'));
			if($guardado&&!$agregando){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_resultado_esperado_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_resultado_esperado');
				$layout = Core_App::getLoadedLayout();

				$resultado_esperado->addAutofilterOutput('utf8_decode');
				
				$audiencia = new Inta_Model_Audiencia();
				$audiencia->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
				$audiencia->setWhere(Db_Helper::equal('id_agencia'));
				$audiencias = $audiencia->search();
				
				
				$ids_audiencia = array();
				foreach($audiencias as $audiencia)
					$ids_audiencia[] = $audiencia->getId();
				$problema = new Inta_Model_Problema();
				if($resultado_esperado->getId())
					$problema->setWhere(Db_Helper::in('id_audiencia', true, $ids_audiencia),' AND (',Db_Helper::equal('id_resultado_esperado', 0),' OR ',Db_Helper::equal('id_resultado_esperado', $resultado_esperado->getId()),')');
				else 
					$problema->setWhere(Db_Helper::in('id_audiencia', true, $ids_audiencia),' AND (',Db_Helper::equal('id_resultado_esperado', 0),')');
				$problemas = $problema->search();
				//echo Core_Helper::DebugVars($ids_audiencia);
				//echo Core_Helper::DebugVars($problema->searchGetSql());
				
				foreach($layout->getBlocks('resultado_esperado_add_edit_form') as $block){
					$block->setIdToEdit($resultado_esperado->getId());
					$block->setObjectToEdit($resultado_esperado);
					$block->setProblemas($problemas);
				}
			}
		}
	}
	protected function addEditIndicador($id_resultado_esperado=null, $id_indicador_resultado=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_IndicadorResultado()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar IndicadorResultado.');
			//$mensajes[] = 'No tiene permitido editar indicador_resultadoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_indicador_resultado = $post&&$post->hasIndicadorResultado()?$post->GetIndicadorResultado(true):null;
			$indicador_resultado = new Inta_Model_IndicadorResultado();
			//echo Core_Helper::DebugVars($post_problemas_asociados);
			if(isset($post_indicador_resultado)){
				$indicador_resultado->loadFromArray($post_indicador_resultado->getData());
				$agregando = $indicador_resultado->getId()?false:true;
				//echo Core_Helper::DebugVars($indicador_resultado->getData());
				$guardado = 
					Admin_ResultadoEsperado_Helper::actionAgregarEditarIndicadorResultado($indicador_resultado)?true:false;
			}
			else{
				if(isset($id_resultado_esperado)&&$id_resultado_esperado){
					$indicador_resultado->setIdResultadoEsperado($id_resultado_esperado);
				}
				if(isset($id_indicador_resultado)&&$id_indicador_resultado){
					$indicador_resultado->setId($id_indicador_resultado);
					$indicador_resultado->load();
				}
			}

			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_indicador_resultado)?'seteado':'no seteado'));
			if($guardado){
				//var_dump($indicador_resultado->getData());
				$this->listarIndicador($indicador_resultado->getIdResultadoEsperado());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_indicador_resultado');
				$layout = Core_App::getLoadedLayout();

				$indicador_resultado->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulariox')){
						$formulario->setAjaxTarget($target_container);
					}
				}
				
				foreach($layout->getBlocks('indicador_resultado_add_edit_form') as $block){
					$block->setIdToEdit($indicador_resultado->getId());
					$block->setObjectToEdit($indicador_resultado);
					if(isset($target_container))
						$block->setTargetContainer($target_container);
				}
			}
		}
	}
	protected function listarIndicador($id_resultado_esperado){
		Core_App::getLayout()->addActions('list_admin_indicador_resultado');
		//$this->cambiarUrlAjax('administrator/resultado_esperado/addEdit/');
		if($bloque_listado = Core_App::getLoadedLayout()->getBlock('resultado_esperado_add_edit_form')){
			$resultado_esperado = new Inta_Model_ResultadoEsperado();
			$resultado_esperado->setId($id_resultado_esperado);
			if($resultado_esperado->load()){
				$bloque_listado->setObjectToEdit($resultado_esperado);
			}
			else echo "Error en la carga del resultado esperado";
		}
		
	}
	protected function deleteIndicador($id_indicador_resultado){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_IndicadorResultado()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar ResultadoEsperado.');
		}
		else{
			if(isset($id_indicador_resultado)){
				$indicador_resultado = new Inta_Model_IndicadorResultado();
				$indicador_resultado->setId($id_indicador_resultado);
				if($indicador_resultado->load()){
					Admin_ResultadoEsperado_Helper::actionEliminarIndicadorResultado($id_indicador_resultado);
					$this->listarIndicador($indicador_resultado->getIdResultadoEsperado());
					return;
				}
			}
		}
		Admin_App::getInstance()->addErrorMessage('Error en la eliminacion.');
	}
	
	protected function addEditMedioVerificacion($id_indicador_resultado=null, $id_medio_verificacion_indicador_resultado=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_MedioVerificacionIndicadorResultado()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar MedioVerificacionIndicadorResultado.');
			//$mensajes[] = 'No tiene permitido editar medio_verificacion_indicador_resultadoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_medio_verificacion_indicador_resultado = $post&&$post->hasMedioVerificacionIndicadorResultado()?$post->GetMedioVerificacionIndicadorResultado(true):null;
			$medio_verificacion_indicador_resultado = new Inta_Model_MedioVerificacionIndicadorResultado();
			//echo Core_Helper::DebugVars($post_problemas_asociados);
			if(isset($post_medio_verificacion_indicador_resultado)){
				$medio_verificacion_indicador_resultado->loadFromArray($post_medio_verificacion_indicador_resultado->getData());
				$agregando = $medio_verificacion_indicador_resultado->getId()?false:true;
				//echo Core_Helper::DebugVars($medio_verificacion_indicador_resultado->getData());
				$guardado = 
					Admin_ResultadoEsperado_Helper::actionAgregarEditarMedioVerificacionIndicadorResultado($medio_verificacion_indicador_resultado)?true:false;
			}
			else{
				if(isset($id_indicador_resultado)&&$id_indicador_resultado){
					$medio_verificacion_indicador_resultado->setIdIndicadorResultado($id_indicador_resultado);
				}
				if(isset($id_medio_verificacion_indicador_resultado)&&$id_medio_verificacion_indicador_resultado){
					$medio_verificacion_indicador_resultado->setId($id_medio_verificacion_indicador_resultado);
					$medio_verificacion_indicador_resultado->load();
				}
			}

			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_medio_verificacion_indicador_resultado)?'seteado':'no seteado'));
			if($guardado){
				//var_dump($medio_verificacion_indicador_resultado->getData());
//				if($resultado_esperado = $medio_verificacion_indicador_resultado->getResultadoEsperado()){
//					$this->listarIndicador($resultado_esperado->getId());
//				}
				if($indicador_resultado = $medio_verificacion_indicador_resultado->getIndicadorResultado()){
					$this->listarIndicador($indicador_resultado->getIdResultadoEsperado());
				}
				else{
					Admin_App::getInstance()->addErrorMessage('Error, datos incorrectos no se pudo obtener "indicador de resultado"');
				}
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_medio_verificacion_indicador_resultado');
				$layout = Core_App::getLoadedLayout();

				$medio_verificacion_indicador_resultado->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulariox')){
						$formulario->setAjaxTarget($target_container);
					}
				}
				
				foreach($layout->getBlocks('medio_verificacion_indicador_resultado_add_edit_form') as $block){
					$block->setIdToEdit($medio_verificacion_indicador_resultado->getId());
					$block->setObjectToEdit($medio_verificacion_indicador_resultado);
					if(isset($target_container))
						$block->setTargetContainer($target_container);
				}
			}
		}
	}
	protected function listarMedioVerificacion($id_indicador_resultado){
		Core_App::getLayout()->addActions('list_admin_medio_verificacion_indicador_resultado');
		//$this->cambiarUrlAjax('administrator/indicador_resultado/addEdit/');
		if($bloque_listado = Core_App::getLoadedLayout()->getBlock('indicador_resultado_add_edit_form')){
			$indicador_resultado = new Inta_Model_IndicadorResultado();
			$indicador_resultado->setId($id_indicador_resultado);
			if($indicador_resultado->load()){
				$bloque_listado->setObjectToEdit($indicador_resultado);
			}
			else echo "Error en la carga del resultado esperado";
		}
		
	}
	protected function deleteMedioVerificacion($id_medio_verificacion_indicador_resultado){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_MedioVerificacionIndicadorResultado()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar IndicadorResultado.');
		}
		else{
			if(isset($id_medio_verificacion_indicador_resultado)){
				$medio_verificacion_indicador_resultado = new Inta_Model_MedioVerificacionIndicadorResultado();
				$medio_verificacion_indicador_resultado->setId($id_medio_verificacion_indicador_resultado);
				if($medio_verificacion_indicador_resultado->load()){
					Admin_ResultadoEsperado_Helper::actionEliminarMedioVerificacionIndicadorResultado($id_medio_verificacion_indicador_resultado);
					if($indicador_resultado = $medio_verificacion_indicador_resultado->getIndicadorResultado()){
						$this->listarIndicador($indicador_resultado->getIdResultadoEsperado());
					}
					else{
						Admin_App::getInstance()->addErrorMessage('Error, datos incorrectos no se pudo obtener "indicador de resultado"');
					}
					return;
				}
			}
		}
		Admin_App::getInstance()->addErrorMessage('Error en la eliminacion.');
	}

	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_resultado_esperado');
		$this->cambiarUrlAjax('administrator/resultado_esperado/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_resultado_esperado');
	}
	protected function dispatchNode(){
		return;
	}
	protected function medios_verificacion(){
		if(Core_Http_Post::hasParameters()){
			$post_data = Core_Http_Post::getParameters('Core_Object');
			if($post_data->hasIdIndicador()){
				$medio_verificacion = new Inta_Model_MedioVerificacion();
				$medio_verificacion
					->setIdIndicador($post_data->getIdIndicador())
					->setWhere(Db_Helper::equal('id_indicador'))
				;
				$medio_verificacions = $medio_verificacion->search();
				$html_options = '';
				foreach($medio_verificacions as $medio_verificacion){
					$option = new Core_Html_Tag_Custom('option');
					$option
						->setValue($medio_verificacion->getId())
						->setInnerHtml($medio_verificacion->getNombre())
					;
					$html_options .= $option->getHtml();
				}
				echo $html_options;
			}
		}
		die();
	}
}
?>