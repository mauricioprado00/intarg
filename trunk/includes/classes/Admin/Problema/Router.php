<?
class Admin_Problema_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_problema');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_problema=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Problema()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Problema.');
		}
		else{
			if(isset($id_problema)){
				Admin_Problema_Helper::actionEliminarProblema($id_problema);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_problema=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Problema()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Problema.');
			//$mensajes[] = 'No tiene permitido editar problemaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_problema = $post&&$post->hasProblema()?$post->GetProblema(true):null;
			$problema = new Inta_Model_Problema();
			if(isset($post_problema)){
				$problema->loadFromArray($post_problema->getData());
				//echo Core_Helper::DebugVars($problema->getData());
				$guardado = 
					Admin_Problema_Helper::actionAgregarEditarProblema($problema)?true:false;
			}
			else{
				if(isset($id_problema)){
					$problema->setId($id_problema);
					$problema->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_problema)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_problema_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_problema');
				$layout = Core_App::getLoadedLayout();
				if($problema->getId()&&!$id_problema){
					$this->cambiarUrlAjax('administrator/problema/addEdit/'.$problema->getId());
				}

				$problema->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('problema_add_edit_form') as $block){
					$block->setIdToEdit($problema->getId());
					$block->setObjectToEdit($problema);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_problema');
		$this->cambiarUrlAjax('administrator/problema/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_problema');
	}
	protected function dispatchNode(){
		return;
	}
}
?>