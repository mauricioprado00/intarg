<?
class Admin_Barrio_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_barrio');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_barrio=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Barrio()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Barrio.');
		}
		else{
			if(isset($id_barrio)){
				Admin_Barrio_Helper::actionEliminarBarrio($id_barrio);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_barrio=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Barrio()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Barrio.');
			//$mensajes[] = 'No tiene permitido editar barrioes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_barrio = $post&&$post->hasBarrio()?$post->GetBarrio(true):null;
			$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
			if($post_nodo){
				//insertar el nodo
			}
			else{
				$id_nodo = null;
			}
			$barrio = new Granguia_Model_Barrio();
			$nodo = Granguia_Model_Barrio::NewNodo();//new Granguia_Model_Nodo();
			//
			if(isset($post_barrio)){
				$guardado_nodo = true;
				$barrio->loadFromArray($post_barrio->getData());
				if(isset($post_nodo)){
					$nodo->loadFromArray($post_nodo->getData());
					$nodo->setTypeInstance($barrio, true);
					$guardado_nodo = Admin_Nodo_Helper::actionAgregarEditarNodo($nodo, $post_nodo);
					if($guardado_nodo){
//						$id_nodo = $nodo->getId();
//						$barrio->setIdNodo($id_nodo);
					}
				}
				$guardado_barrio = Admin_Barrio_Helper::actionAgregarEditarBarrio($barrio)?true:false;
				$guardado = $guardado_barrio && $guardado_nodo;
			}
			else{
				if(isset($id_barrio)){
					$barrio->setId($id_barrio);
					$barrio->load();
					if($barrio->hasIdNodo()&&($id_nodo = $barrio->getIdNodo())){
						$nodo->setId($id_nodo);
						if(!$nodo->load()){
							$nodo = Granguia_Model_Barrio::NewNodo();
							Admin_App::getInstance()->addWarningMessage('Error durante la carga del nodo asociado');
							echo Core_Helper::DebugVars($nodo);
						}
					}
					else{
						Admin_App::getInstance()->addInfoMessage('El barrio actualmente no posee nodo.');
					}
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_barrio_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_barrio');
				$layout = Core_App::getLoadedLayout();

				$barrio->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('barrio_add_edit_form') as $block){
					$block->setIdToEdit($barrio->getId());
					$block->setObjectToEdit($barrio);
				}

				$nodo->addAutofilterOutput('utf8_decode');
				
				$tipo_nodo = new Granguia_Model_TipoNodo();
				$tipos_nodos = $tipo_nodo->search('nombre','DESC');
				foreach($layout->getBlocks('nodo_add_edit_form') as $block){
					$block->setIdToEdit($nodo->getId());
					if($tipos_nodos)
						$block->setTiposNodos($tipos_nodos);
					$block->setObjectToEdit($nodo);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_barrio');
		$this->cambiarUrlAjax('administrator/barrio/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_barrio');
	}
	protected function dispatchNode(){
		return;
	}
}
?>