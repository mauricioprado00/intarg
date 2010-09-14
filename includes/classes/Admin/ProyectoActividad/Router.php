<?
class Admin_ProyectoActividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete',//'listar','datalist',
			'listarProyectoActividad'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_proyecto_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_proyecto_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ProyectoActividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar ProyectoActividad.');
		}
		else{
			if(isset($id_proyecto_actividad)){
				c($proyecto_actividad = new Inta_Model_ProyectoActividad())->setId($id_proyecto_actividad)->load();
				Admin_ProyectoActividad_Helper::actionEliminarProyectoActividad($id_proyecto_actividad);
				return $this->listarProyectoActividad($proyecto_actividad->getIdActividad());
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del proyecto_actividad.');
	}
	protected function addEdit($id_actividad=null, $id_proyecto_actividad=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ProyectoActividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar ProyectoActividad.');
			//$mensajes[] = 'No tiene permitido editar proyecto_actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_proyecto_actividad = $post&&$post->hasProyectoActividad()?$post->GetProyectoActividad(true):null;
			$proyecto_actividad = new Inta_Model_ProyectoActividad();
			$guardado = false;
			if(isset($post_proyecto_actividad)){
				$proyecto_actividad->loadFromArray($post_proyecto_actividad->getData());
				$guardado =
					//false;
					Admin_ProyectoActividad_Helper::actionAgregarEditarProyectoActividad($proyecto_actividad)?true:false;
			}
			else{
				if(isset($id_proyecto_actividad)&&$id_proyecto_actividad){
					$proyecto_actividad->setId($id_proyecto_actividad);
					$proyecto_actividad->load();
				}
				else{
					$proyecto_actividad->setIdActividad($id_actividad);
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_proyecto_actividad_action');
				$this->listarProyectoActividad($proyecto_actividad->getIdActividad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_proyecto_actividad');
				$layout = Core_App::getLoadedLayout();
				
				$proyecto_actividad->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_proyecto_actividad')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('proyecto_actividad_add_edit_form') as $block){
					$block->setIdToEdit($proyecto_actividad->getId());
					$block->setObjectToEdit($proyecto_actividad);
				}
			}
		}
	}
	protected function listarProyectoActividad($id_actividad=null){
		Core_App::getLayout()->addActions('listar_admin_proyecto_actividad');
		if($bloque_listado_proyecto_actividad = Core_App::getLoadedLayout()->getBlock('listado_proyecto_actividad')){
			$bloque_listado_proyecto_actividad
				->setIdActividad($id_actividad)
			;
		}
	}
//	protected function listar(){
//		Core_App::getLayout()->addActions('entity_list', 'list_admin_proyecto_actividad');
//		$this->cambiarUrlAjax('administrator/proyecto_actividad/listar');
//	}
//	protected function datalist(){
//		Core_App::getLayout()->setActions(array());//reset
//		Core_App::getLayout()->addActions('datalist', 'datalist_admin_proyecto_actividad');
//	}
	protected function dispatchNode(){
		return;
	}
}
?>