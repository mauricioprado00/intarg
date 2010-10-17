<?
class Admin_MedioVerificacion_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden','addEditPopup'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_medio_verificacion');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_medio_verificacion=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_MedioVerificacion()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar MedioVerificacion.');
		}
		else{
			if(isset($id_medio_verificacion)){
				Admin_MedioVerificacion_Helper::actionEliminarMedioVerificacion($id_medio_verificacion);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_medio_verificacion=null, $nombre=null, $id_indicador=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_MedioVerificacion()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar MedioVerificacion.');
			//$mensajes[] = 'No tiene permitido editar medio_verificaciones.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_medio_verificacion = $post&&$post->hasMedioVerificacion()?$post->GetMedioVerificacion(true):null;
			$medio_verificacion = new Inta_Model_MedioVerificacion();
			if(isset($post_medio_verificacion)){
				$medio_verificacion->loadFromArray($post_medio_verificacion->getData());
				//echo Core_Helper::DebugVars($medio_verificacion->getData());
				$guardado = 
					Admin_MedioVerificacion_Helper::actionAgregarEditarMedioVerificacion($medio_verificacion)?true:false;
			}
			else{
				if(isset($id_medio_verificacion)){
					$medio_verificacion->setId($id_medio_verificacion);
					$medio_verificacion->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_medio_verificacion)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_medio_verificacion_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_medio_verificacion');
				$layout = Core_App::getLoadedLayout();
				if($medio_verificacion->getId()&&!$id_medio_verificacion){
					$this->cambiarUrlAjax('administrator/medio_verificacion/addEdit/'.$medio_verificacion->getId());
				}
				
				if(!$medio_verificacion->getId()){
					if(isset($id_indicador)){
						$medio_verificacion->setIdIndicador($id_indicador);
					}
					if(isset($nombre)){
						$medio_verificacion->setNombre($nombre);
					}
				}

				$medio_verificacion->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('medio_verificacion_add_edit_form') as $block){
					$block->setIdToEdit($medio_verificacion->getId());
					$block->setObjectToEdit($medio_verificacion);
				}
			}
		}
	}
	
	protected function addEditPopup($id_audiencia=null, $nombre=null, $id_indicador=null){
		//var_dump(var_export(Core_App::getLayout()->getActions(), true));
		//array ( 0 => 'default', 1 => 'superadmin', 2 => 'modo_ajax', 3 => 'modulo', 4 => 'modulo_admin_medio_verificacion', )
		//Core_App::getLayout()->setActions('empty');
		
		
		//var_dump(var_export(Core_App::getLayout()->getActions(), true));
		$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
		$post_medio_verificacion = $post&&$post->hasMedioVerificacion()?$post->GetMedioVerificacion(true):null;
		$medio_verificacion = new Inta_Model_MedioVerificacion();
		$guardado = false;
		if(isset($post_medio_verificacion)){
			$medio_verificacion->loadFromArray($post_medio_verificacion->getData());
			//echo Core_Helper::DebugVars($medio_verificacion->getData());
			$guardado =
				//false;
				Admin_MedioVerificacion_Helper::actionAgregarEditarMedioVerificacion($medio_verificacion)?true:false;
		}
		if($guardado){
			Core_App::getLayout()->setActions('empty');
			$bmain = Core_App::getLoadedLayout()->getBlock('contenedor_main');
			$post = Core_Http_Post::getParameters('Core_Object');
			$configuracion_select_more = null;
			if($post->hasConfiguracionSelect()){
				$configuracion_select = $post->getConfiguracionSelect();
			}
			$configuracion_select_more = null;
			if($post->hasConfiguracionSelectMore()){
				$configuracion_select_more = $post->getConfiguracionSelectMore();
			}
			//var_dump(get_class($bmain));
			$bmain->appendBlock('<script inline_script="inline_script"><![CDATA[jQuery.ScreenBlock(false);]]></script>');
			$control = c($selector_medio_verificacion = $bmain->appendBlock('<selector_medio_verificacion_abm />'))
					->setSelectedValue($medio_verificacion->getId());
			$sc = $selector_medio_verificacion->getSelectControl();
			if($configuracion_select){
				$new = array_merge($sc->getData(), $configuracion_select);
				$sc->setData($new);
			}
			if($configuracion_select_more){
				$new = array_merge($control->getData(), $configuracion_select_more);
				foreach($new as $key=>$value){
					$control->setData($key, $value);
				}
			}
			
			//var_dump(Core_App::getLayout()->getActions());
			//echo "ok agregado";
//			var_dump($configuracion_select);
//			var_dump($medio_verificacion->getData());
			return true;
		}
		else{
			$this->addEdit($id_medio_verificacion, $nombre, $id_indicador);
			if($bmain = Core_App::getLayout()->getBlock('contenedor_ajax')){
				$bmain->setHtmlClass('');
			}
			Core_App::getLayout()->getBlock('formulariox')->setActionUrl('medio_verificacion/addEditPopup');
			if(Core_Http_Post::hasParameters()){
				$post = Core_Http_Post::getParameters('Core_Object');
				if($post->hasConfiguracionSelect()){
					foreach(Core_App::getLayout()->getBlocks('formulariox') as $block){
//						$i = $block->appendBlock('<script inline_script="inline_script"><![CDATA[
//						alert("hola mundo");
//						]]></script>');
						if($post->hasConfiguracionSelect())
						foreach($post->getConfiguracionSelect() as $key=>$value){
							$i = $block->appendBlock('<input_text />');// ->appendXmlBlocks('<input_text />');
							$i
								->setHtmlName('configuracion_select['.$key.']')
								->setValue($value)
							;
						}
						if($post->hasConfiguracionSelectMore())
						foreach($post->getConfiguracionSelectMore() as $key=>$value){
							$i = $block->appendBlock('<input_text />');// ->appendXmlBlocks('<input_text />');
							$i
								->setHtmlName('configuracion_select_more['.$key.']')
								->setValue($value)
							;
						}
					}
				}
			}
		}
	}

	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_medio_verificacion');
		$this->cambiarUrlAjax('administrator/medio_verificacion/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_medio_verificacion');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_medio_verificacion=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_medio_verificacion');
		$layout = Core_App::getLoadedLayout();
		$ordenar_medio_verificacion = $layout->getBlock('ordenar_medio_verificacion');
		$ordenar_medio_verificacion->setIdMedioVerificacion($id_medio_verificacion);
		$medio_verificacion = new Inta_Model_MedioVerificacion();
		$medio_verificaciones = $medio_verificacion->search('orden');
		$ordenar_medio_verificacion->setMedioVerificaciones($medio_verificaciones);
	}
	protected function setorden($ids){
		$orden = 0;
		$medio_verificacion = new Inta_Model_MedioVerificacion();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$medio_verificacion = new Inta_Model_MedioVerificacion();
			$medio_verificacion->setId($id);
			if(!$medio_verificacion->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$medio_verificacion
				->setOrden($orden++)
				->replace()
			;
			//var_dump($medio_verificacion->getData());
		}
		die();
	}
}
?>