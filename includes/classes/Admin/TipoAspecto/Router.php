<?
class Admin_TipoAspecto_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_tipo_aspecto');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_tipo_aspecto=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_TipoAspecto()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar TipoAspecto.');
		}
		else{
			if(isset($id_tipo_aspecto)){
				Admin_TipoAspecto_Helper::actionEliminarTipoAspecto($id_tipo_aspecto);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_tipo_aspecto=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_TipoAspecto()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar TipoAspecto.');
			//$mensajes[] = 'No tiene permitido editar tipo_aspectoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_tipo_aspecto = $post&&$post->hasTipoAspecto()?$post->GetTipoAspecto(true):null;
			$tipo_aspecto = new Inta_Model_TipoAspecto();
			$guardado = false;
			if(isset($post_tipo_aspecto)){
				$tipo_aspecto->loadFromArray($post_tipo_aspecto->getData());
				//echo Core_Helper::DebugVars($tipo_aspecto->getData());
				$guardado =
					//false;
					Admin_TipoAspecto_Helper::actionAgregarEditarTipoAspecto($tipo_aspecto)?true:false;
			}
			else{
				if(isset($id_tipo_aspecto)){
					$tipo_aspecto->setId($id_tipo_aspecto);
					$tipo_aspecto->load();
				}
//				if(!$tipo_aspecto->getId())
//					$tipo_aspecto->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_tipo_aspecto_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_tipo_aspecto');
				$layout = Core_App::getLoadedLayout();
				if($tipo_aspecto->getId()&&!$id_tipo_aspecto){
					$this->cambiarUrlAjax('administrator/tipo_aspecto/addEdit/'.$tipo_aspecto->getId());
				}
				
				$tipo_aspecto->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('tipo_aspecto_add_edit_form') as $block){
					$block->setIdToEdit($tipo_aspecto->getId());
					$block->setObjectToEdit($tipo_aspecto);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_tipo_aspecto');
		$this->cambiarUrlAjax('administrator/tipo_aspecto/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_tipo_aspecto');
	}
	protected function dispatchNode(){
		return;
	}
}
?>