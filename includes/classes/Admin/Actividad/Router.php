<?
class Admin_Actividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Actividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Actividad.');
		}
		else{
			if(isset($id_actividad)){
				Admin_Actividad_Helper::actionEliminarActividad($id_actividad);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_actividad=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Actividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Actividad.');
			//$mensajes[] = 'No tiene permitido editar actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
//                        echo "<pre>";
//                        var_dump($post);
//                        echo "</pre>";die();
			$post_actividad = $post&&$post->hasActividad()?$post->GetActividad(true):null;
			$actividad = new Inta_Model_Actividad();
			if(isset($post_actividad)){
				$actividad->loadFromArray($post_actividad->getData());
				//echo Core_Helper::DebugVars($actividad->getData());
				$guardado = 
					Admin_Actividad_Helper::actionAgregarEditarActividad($actividad)?true:false;
			}
			else{
				if(isset($id_actividad)){
					$actividad->setId($id_actividad);
					$actividad->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_actividad)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_actividad_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_actividad');
				$layout = Core_App::getLoadedLayout();

				$actividad->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('actividad_add_edit_form') as $block){
					$block->setIdToEdit($actividad->getId());
					$block->setObjectToEdit($actividad);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_actividad');
		$this->cambiarUrlAjax('administrator/actividad/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_actividad');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_actividad=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_actividad');
		$layout = Core_App::getLoadedLayout();
		$ordenar_actividad = $layout->getBlock('ordenar_actividad');
		$ordenar_actividad->setIdActividad($id_actividad);
		$actividad = new Inta_Model_Actividad();
		$actividades = $actividad->search('orden');
		$ordenar_actividad->setActividades($actividades);
	}
	protected function setorden($ids){
		$orden = 0;
		$actividad = new Inta_Model_Actividad();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$actividad = new Inta_Model_Actividad();
			$actividad->setId($id);
			if(!$actividad->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$actividad
				->setOrden($orden++)
				->replace()
			;
			//var_dump($actividad->getData());
		}
		die();
	}
}
?>