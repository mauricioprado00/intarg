<?
class Admin_Aspecto_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_aspecto');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_aspecto=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Aspecto()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Aspecto.');
		}
		else{
			if(isset($id_aspecto)){
				Admin_Aspecto_Helper::actionEliminarAspecto($id_aspecto);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_aspecto=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Aspecto()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Aspecto.');
			//$mensajes[] = 'No tiene permitido editar aspectoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_aspecto = $post&&$post->hasAspecto()?$post->GetAspecto(true):null;
			$aspecto = new Inta_Model_Aspecto();
			$guardado = false;
			if(isset($post_aspecto)){
				$aspecto->loadFromArray($post_aspecto->getData());
				//echo Core_Helper::DebugVars($aspecto->getData());
				$guardado =
					//false;
					Admin_Aspecto_Helper::actionAgregarEditarAspecto($aspecto)?true:false;
			}
			else{
				if(isset($id_aspecto)){
					$aspecto->setId($id_aspecto);
					$aspecto->load();
				}
				if(!$aspecto->getId())
					$aspecto->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_aspecto_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_aspecto');
				$layout = Core_App::getLoadedLayout();
				
//				if($block_listado_documentos = $layout->getBlock('listado_documentos')){
//					$block_listado_documentos->setIdEntidad($aspecto->getId());
//				}
				if($block_add_edit_list_documentos_aspecto = $layout->getBlock('add_edit_list_documentos_aspecto')){
					$block_add_edit_list_documentos_aspecto->setIdEntidad($aspecto->getId());
				}
				if($aspecto->getId()&&!$id_aspecto){
					$this->cambiarUrlAjax('administrator/aspecto/addEdit/'.$aspecto->getId());
				}

				$aspecto->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('aspecto_add_edit_form') as $block){
					$block->setIdToEdit($aspecto->getId());
					$block->setObjectToEdit($aspecto);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_aspecto');
		$this->cambiarUrlAjax('administrator/aspecto/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_aspecto');
	}
	protected function dispatchNode(){
		return;
	}
}
?>