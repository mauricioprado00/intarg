<?
class Admin_Estrategia_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_estrategia');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_estrategia=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Estrategia()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Estrategia.');
		}
		else{
			if(isset($id_estrategia)){
				Admin_Estrategia_Helper::actionEliminarEstrategia($id_estrategia);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_estrategia=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Estrategia()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Estrategia.');
			//$mensajes[] = 'No tiene permitido editar estrategiaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_estrategia = $post&&$post->hasEstrategia()?$post->GetEstrategia(true):null;
			$estrategia = new Inta_Model_Estrategia();
			if(isset($post_estrategia)){
				$estrategia->loadFromArray($post_estrategia->getData());
				//echo Core_Helper::DebugVars($estrategia->getData());
				$guardado = 
					Admin_Estrategia_Helper::actionAgregarEditarEstrategia($estrategia)?true:false;
			}
			else{
				if(isset($id_estrategia)){
					$estrategia->setId($id_estrategia);
					$estrategia->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_estrategia)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_estrategia_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_estrategia');
				$layout = Core_App::getLoadedLayout();

				$estrategia->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('estrategia_add_edit_form') as $block){
					$block->setIdToEdit($estrategia->getId());
					$block->setObjectToEdit($estrategia);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_estrategia');
		$this->cambiarUrlAjax('administrator/estrategia/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estrategia');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_estrategia=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_estrategia');
		$layout = Core_App::getLoadedLayout();
		$ordenar_estrategia = $layout->getBlock('ordenar_estrategia');
		$ordenar_estrategia->setIdEstrategia($id_estrategia);
		$estrategia = new Inta_Model_Estrategia();
		$estrategiaes = $estrategia->search('orden');
		$ordenar_estrategia->setEstrategiaes($estrategiaes);
	}
	protected function setorden($ids){
		$orden = 0;
		$estrategia = new Inta_Model_Estrategia();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$estrategia = new Inta_Model_Estrategia();
			$estrategia->setId($id);
			if(!$estrategia->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$estrategia
				->setOrden($orden++)
				->replace()
			;
			//var_dump($estrategia->getData());
		}
		die();
	}
}
?>