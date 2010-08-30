<?
class Admin_Proyecto_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_proyecto');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_proyecto=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Proyecto()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Proyecto.');
		}
		else{
			if(isset($id_proyecto)){
				Admin_Proyecto_Helper::actionEliminarProyecto($id_proyecto);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_proyecto=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Proyecto()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Proyecto.');
			//$mensajes[] = 'No tiene permitido editar proyectoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_proyecto = $post&&$post->hasProyecto()?$post->GetProyecto(true):null;
			$proyecto = new Inta_Model_Proyecto();
			if(isset($post_proyecto)){
				$proyecto->loadFromArray($post_proyecto->getData());
				//echo Core_Helper::DebugVars($proyecto->getData());
				$guardado =
					Admin_Proyecto_Helper::actionAgregarEditarProyecto($proyecto)?true:false;
			}
			else{
				if(isset($id_proyecto)){
					$proyecto->setId($id_proyecto);
					$proyecto->load();
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_proyecto)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_proyecto_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_proyecto');
				$layout = Core_App::getLoadedLayout();

				$proyecto->addAutofilterOutput('utf8_decode');

				foreach($layout->getBlocks('proyecto_add_edit_form') as $block){
					$block->setIdToEdit($proyecto->getId());
					$block->setObjectToEdit($proyecto);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_proyecto');
		$this->cambiarUrlAjax('administrator/proyecto/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_proyecto');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_proyecto=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_proyecto');
		$layout = Core_App::getLoadedLayout();
		$ordenar_proyecto = $layout->getBlock('ordenar_proyecto');
		$ordenar_proyecto->setIdProyecto($id_proyecto);
		$proyecto = new Inta_Model_Proyecto();
		$proyectoes = $proyecto->search('orden');
		$ordenar_proyecto->setProyectoes($proyectoes);
	}
	protected function setorden($ids){
		$orden = 0;
		$proyecto = new Inta_Model_Proyecto();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$proyecto = new Inta_Model_Proyecto();
			$proyecto->setId($id);
			if(!$proyecto->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$proyecto
				->setOrden($orden++)
				->replace()
			;
			//var_dump($proyecto->getData());
		}
		die();
	}
}
?>