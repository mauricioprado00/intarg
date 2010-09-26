<?
class Admin_Translate_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden','enable','disable'
		);
	}
	protected function enable(){
		Admin_Translate_Debug::getInstance()->EnableDebug();
		Admin_App::getInstance()->addSuccessMessage($this->__t('Se ha habilitado el seguimiento de traducciones'));
	}
	protected function disable(){
		Admin_Translate_Debug::getInstance()->DisableDebug();
		Admin_App::getInstance()->addSuccessMessage($this->__t('Se ha deshabilitado el seguimiento de traducciones'));
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_translate');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_translate=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Traduccion()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Translate.');
		}
		else{
			if(isset($id_translate)){
				Admin_Translate_Helper::actionEliminarTranslate($id_translate);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_translate=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Traduccion()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Translate.');
			//$mensajes[] = 'No tiene permitido editar translatees.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_translate = $post&&$post->hasTranslate()?$post->GetTranslate(true):null;
			$translate = new Inta_Model_Traduccion();
			if(isset($post_translate)){
				$translate->loadFromArray($post_translate->getData());
				//echo Core_Helper::DebugVars($translate->getData());
				$guardado = 
					Admin_Translate_Helper::actionAgregarEditarTranslate($translate)?true:false;
			}
			else{
				if(isset($id_translate)){
					$translate->setId($id_translate);
					$translate->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_translate)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_translate_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_translate');
				$layout = Core_App::getLoadedLayout();

				$translate->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('translate_add_edit_form') as $block){
					$block->setIdToEdit($translate->getId());
					$block->setObjectToEdit($translate);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_translate');
		$this->cambiarUrlAjax('administrator/translate/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_translate');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_translate=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_translate');
		$layout = Core_App::getLoadedLayout();
		$ordenar_translate = $layout->getBlock('ordenar_translate');
		$ordenar_translate->setIdTranslate($id_translate);
		$translate = new Inta_Model_Traduccion();
		$translatees = $translate->search('orden');
		$ordenar_translate->setTranslatees($translatees);
	}
	protected function setorden($ids){
		$orden = 0;
		$translate = new Inta_Model_Traduccion();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$translate = new Inta_Model_Traduccion();
			$translate->setId($id);
			if(!$translate->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$translate
				->setOrden($orden++)
				->replace()
			;
			//var_dump($translate->getData());
		}
		die();
	}
}
?>