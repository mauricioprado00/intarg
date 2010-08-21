<?
class Admin_Archivo_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_archivo');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_archivo=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Archivo()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Archivo.');
		}
		else{
			if(isset($id_archivo)){
				Admin_Archivo_Helper::actionEliminarArchivo($id_archivo);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_archivo=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Archivo()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Archivo.');
			//$mensajes[] = 'No tiene permitido editar archivoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_archivo = $post&&$post->hasArchivo()?$post->GetArchivo(true):null;
			$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
			if($post_nodo){
				//insertar el nodo
			}
			else{
				$id_nodo = null;
			}
			$archivo = new Granguia_Model_Archivo();
			$nodo = Granguia_Model_Archivo::NewNodo();//new Granguia_Model_Nodo();
			//
			if(isset($post_archivo)){
				$guardado_nodo = true;
				$archivo->loadFromArray($post_archivo->getData());
				if(isset($post_nodo)){
					$nodo->loadFromArray($post_nodo->getData());
					$nodo->setTypeInstance($archivo, true);
					$guardado_nodo = Admin_Nodo_Helper::actionAgregarEditarNodo($nodo, $post_nodo);
					if($guardado_nodo){
//						$id_nodo = $nodo->getId();
//						$archivo->setIdNodo($id_nodo);
					}
				}
				$guardado_archivo = Admin_Archivo_Helper::actionAgregarEditarArchivo($archivo)?true:false;
				$guardado = $guardado_archivo && $guardado_nodo;
			}
			else{
				if(isset($id_archivo)){
					$archivo->setId($id_archivo);
					$archivo->load();
					if($archivo->hasIdNodo()&&($id_nodo = $archivo->getIdNodo())){
						$nodo->setId($id_nodo);
						if(!$nodo->load()){
							$nodo = Granguia_Model_Archivo::NewNodo();
							Admin_App::getInstance()->addWarningMessage('Error durante la carga del nodo asociado');
							echo Core_Helper::DebugVars($nodo);
						}
					}
					else{
						Admin_App::getInstance()->addInfoMessage('El archivo actualmente no posee nodo.');
					}
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_archivo_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_archivo');
				$layout = Core_App::getLoadedLayout();

				$archivo->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('archivo_add_edit_form') as $block){
					$block->setIdToEdit($archivo->getId());
					$block->setObjectToEdit($archivo);
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
		Core_App::getLayout()->addActions('entity_list', 'list_admin_archivo');
		$this->cambiarUrlAjax('administrator/archivo/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_archivo');
	}
	protected function dispatchNode(){
		return;
	}
}
?>