<?
class Admin_Indicador_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden','addEditPopup'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_indicador');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_indicador=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Indicador()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Indicador.');
		}
		else{
			if(isset($id_indicador)){
				Admin_Indicador_Helper::actionEliminarIndicador($id_indicador);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_indicador=null, $nombre=null, $tipo_indicador=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Indicador()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Indicador.');
			//$mensajes[] = 'No tiene permitido editar indicadores.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_indicador = $post&&$post->hasIndicador()?$post->GetIndicador(true):null;
			$indicador = new Inta_Model_Indicador();
			if(isset($post_indicador)){
				$indicador->loadFromArray($post_indicador->getData());
				//echo Core_Helper::DebugVars($indicador->getData());
				$guardado = 
					Admin_Indicador_Helper::actionAgregarEditarIndicador($indicador)?true:false;
			}
			else{
				if(isset($id_indicador)){
					$indicador->setId($id_indicador);
					$indicador->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_indicador)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_indicador_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_indicador');
				$layout = Core_App::getLoadedLayout();
				if($indicador->getId()&&!$id_indicador){
					$this->cambiarUrlAjax('administrator/indicador/addEdit/'.$indicador->getId());
				}

				if(!$indicador->getId()){
					if(isset($nombre))
						$indicador->setNombre($nombre);
					if(isset($tipo_indicador))
						$indicador->setTipoIndicador($tipo_indicador);
				}

				$indicador->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('indicador_add_edit_form') as $block){
					$block->setIdToEdit($indicador->getId());
					$block->setObjectToEdit($indicador);
				}
			}
		}
	}
	protected function addEditPopup($id_audiencia=null, $nombre=null, $tipo_indicador=null){
		//var_dump(var_export(Core_App::getLayout()->getActions(), true));
		//array ( 0 => 'default', 1 => 'superadmin', 2 => 'modo_ajax', 3 => 'modulo', 4 => 'modulo_admin_indicador', )
		//Core_App::getLayout()->setActions('empty');
		
		
		//var_dump(var_export(Core_App::getLayout()->getActions(), true));
		$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
		$post_indicador = $post&&$post->hasIndicador()?$post->GetIndicador(true):null;
		$indicador = new Inta_Model_Indicador();
		$guardado = false;
		if(isset($post_indicador)){
			$indicador->loadFromArray($post_indicador->getData());
			//echo Core_Helper::DebugVars($indicador->getData());
			$guardado =
				//false;
				Admin_Indicador_Helper::actionAgregarEditarIndicador($indicador)?true:false;
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
			$control = c($selector_indicador = $bmain->appendBlock('<selector_indicador_abm />'))
					->setSelectedValue($indicador->getId());
			$sc = $selector_indicador->getSelectControl();
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
//			var_dump($indicador->getData());
			return true;
		}
		else{
			$this->addEdit($id_indicador, $nombre, $tipo_indicador);
			if($bmain = Core_App::getLayout()->getBlock('contenedor_ajax')){
				$bmain->setHtmlClass('');
			}
			Core_App::getLayout()->getBlock('formulariox')->setActionUrl('indicador/addEditPopup');
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
		Core_App::getLayout()->addActions('entity_list', 'list_admin_indicador');
		$this->cambiarUrlAjax('administrator/indicador/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_indicador');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_indicador=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_indicador');
		$layout = Core_App::getLoadedLayout();
		$ordenar_indicador = $layout->getBlock('ordenar_indicador');
		$ordenar_indicador->setIdIndicador($id_indicador);
		$indicador = new Inta_Model_Indicador();
		$indicadores = $indicador->search('orden');
		$ordenar_indicador->setIndicadores($indicadores);
	}
	protected function setorden($ids){
		$orden = 0;
		$indicador = new Inta_Model_Indicador();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$indicador = new Inta_Model_Indicador();
			$indicador->setId($id);
			if(!$indicador->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$indicador
				->setOrden($orden++)
				->replace()
			;
			//var_dump($indicador->getData());
		}
		die();
	}
}
?>