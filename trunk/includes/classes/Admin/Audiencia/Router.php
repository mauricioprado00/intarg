<?
class Admin_Audiencia_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_audiencia');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_audiencia=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Audiencia()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Audiencia.');
		}
		else{
			if(isset($id_audiencia)){
				Admin_Audiencia_Helper::actionEliminarAudiencia($id_audiencia);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_audiencia=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Audiencia()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Audiencia.');
			//$mensajes[] = 'No tiene permitido editar audienciaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_audiencia = $post&&$post->hasAudiencia()?$post->GetAudiencia(true):null;
			$audiencia = new Inta_Model_Audiencia();
			if(isset($post_audiencia)){
				$audiencia->loadFromArray($post_audiencia->getData());
				//echo Core_Helper::DebugVars($audiencia->getData());
				$guardado = 
					Admin_Audiencia_Helper::actionAgregarEditarAudiencia($audiencia)?true:false;
			}
			else{
				if(isset($id_audiencia)){
					$audiencia->setId($id_audiencia);
					$audiencia->load();
				}
				if(!$audiencia->getId())
					$audiencia->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_audiencia)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_audiencia_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_audiencia');
				$layout = Core_App::getLoadedLayout();

				$audiencia->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('audiencia_add_edit_form') as $block){
					$block->setIdToEdit($audiencia->getId());
					$block->setObjectToEdit($audiencia);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_audiencia');
		$this->cambiarUrlAjax('administrator/audiencia/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_audiencia');
	}
	protected function dispatchNode(){
		return;
	}
}
?>