<?
class Admin_Agencia_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_agencia');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_agencia=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Agencia()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Agencia.');
		}
		else{
			if(isset($id_agencia)){
				Admin_Agencia_Helper::actionEliminarAgencia($id_agencia);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_agencia=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Agencia()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Agencia.');
			//$mensajes[] = 'No tiene permitido editar agenciaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_agencia = $post&&$post->hasAgencia()?$post->GetAgencia(true):null;
			$agencia = new Inta_Model_Agencia();
			if(isset($post_agencia)){
				$agencia->loadFromArray($post_agencia->getData());
				//echo Core_Helper::DebugVars($agencia->getData());
				$guardado = 
					Admin_Agencia_Helper::actionAgregarEditarAgencia($agencia)?true:false;
			}
			else{
				if(isset($id_agencia)){
					$agencia->setId($id_agencia);
					$agencia->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_agencia)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_agencia_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_agencia');
				$layout = Core_App::getLoadedLayout();

				$agencia->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('agencia_add_edit_form') as $block){
					$block->setIdToEdit($agencia->getId());
					$block->setObjectToEdit($agencia);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_agencia');
		$this->cambiarUrlAjax('administrator/agencia/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_agencia');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_agencia=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_agencia');
		$layout = Core_App::getLoadedLayout();
		$ordenar_agencia = $layout->getBlock('ordenar_agencia');
		$ordenar_agencia->setIdAgencia($id_agencia);
		$agencia = new Inta_Model_Agencia();
		$agenciaes = $agencia->search('orden');
		$ordenar_agencia->setAgenciaes($agenciaes);
	}
	protected function setorden($ids){
		$orden = 0;
		$agencia = new Inta_Model_Agencia();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$agencia = new Inta_Model_Agencia();
			$agencia->setId($id);
			if(!$agencia->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$agencia
				->setOrden($orden++)
				->replace()
			;
			//var_dump($agencia->getData());
		}
		die();
	}
}
?>