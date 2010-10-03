<?
class Admin_TipoAudiencia_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_tipo_audiencia');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_tipo_audiencia=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_TipoAudiencia()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar TipoAudiencia.');
		}
		else{
			if(isset($id_tipo_audiencia)){
				Admin_TipoAudiencia_Helper::actionEliminarTipoAudiencia($id_tipo_audiencia);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_tipo_audiencia=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_TipoAudiencia()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar TipoAudiencia.');
			//$mensajes[] = 'No tiene permitido editar tipo_audienciaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_tipo_audiencia = $post&&$post->hasTipoAudiencia()?$post->GetTipoAudiencia(true):null;
			$tipo_audiencia = new Inta_Model_TipoAudiencia();
			if(isset($post_tipo_audiencia)){
				$tipo_audiencia->loadFromArray($post_tipo_audiencia->getData());
				//echo Core_Helper::DebugVars($tipo_audiencia->getData());
				$guardado = 
					Admin_TipoAudiencia_Helper::actionAgregarEditarTipoAudiencia($tipo_audiencia)?true:false;
			}
			else{
				if(isset($id_tipo_audiencia)){
					$tipo_audiencia->setId($id_tipo_audiencia);
					$tipo_audiencia->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_tipo_audiencia)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_tipo_audiencia_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_tipo_audiencia');
				$layout = Core_App::getLoadedLayout();
				if($tipo_audiencia->getId()&&!$id_tipo_audiencia){
					$this->cambiarUrlAjax('administrator/tipo_audiencia/addEdit/'.$tipo_audiencia->getId());
				}

				$tipo_audiencia->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('tipo_audiencia_add_edit_form') as $block){
					$block->setIdToEdit($tipo_audiencia->getId());
					$block->setObjectToEdit($tipo_audiencia);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_tipo_audiencia');
		$this->cambiarUrlAjax('administrator/tipo_audiencia/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_tipo_audiencia');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_tipo_audiencia=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_tipo_audiencia');
		$layout = Core_App::getLoadedLayout();
		$ordenar_tipo_audiencia = $layout->getBlock('ordenar_tipo_audiencia');
		$ordenar_tipo_audiencia->setIdTipoAudiencia($id_tipo_audiencia);
		$tipo_audiencia = new Inta_Model_TipoAudiencia();
		$tipo_audienciaes = $tipo_audiencia->search('orden');
		$ordenar_tipo_audiencia->setTipoAudienciaes($tipo_audienciaes);
	}
	protected function setorden($ids){
		$orden = 0;
		$tipo_audiencia = new Inta_Model_TipoAudiencia();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$tipo_audiencia = new Inta_Model_TipoAudiencia();
			$tipo_audiencia->setId($id);
			if(!$tipo_audiencia->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$tipo_audiencia
				->setOrden($orden++)
				->replace()
			;
			//var_dump($tipo_audiencia->getData());
		}
		die();
	}
}
?>