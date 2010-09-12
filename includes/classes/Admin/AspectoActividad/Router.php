<?
class Admin_AspectoActividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete',//'listar','datalist',
			'listarAspectoActividad'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_aspecto_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_aspecto_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_AspectoActividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar AspectoActividad.');
		}
		else{
			if(isset($id_aspecto_actividad)){
				c($aspecto_actividad = new Inta_Model_AspectoActividad())->setId($id_aspecto_actividad)->load();
				Admin_AspectoActividad_Helper::actionEliminarAspectoActividad($id_aspecto_actividad);
				return $this->listarAspectoActividad($aspecto_actividad->getIdActividad());
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del aspecto_actividad.');
	}
	protected function addEdit($id_actividad=null, $id_aspecto_actividad=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_AspectoActividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar AspectoActividad.');
			//$mensajes[] = 'No tiene permitido editar aspecto_actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_aspecto_actividad = $post&&$post->hasAspectoActividad()?$post->GetAspectoActividad(true):null;
			$aspecto_actividad = new Inta_Model_AspectoActividad();
			$guardado = false;
			if(isset($post_aspecto_actividad)){
				$aspecto_actividad->loadFromArray($post_aspecto_actividad->getData());
				$guardado =
					//false;
					Admin_AspectoActividad_Helper::actionAgregarEditarAspectoActividad($aspecto_actividad)?true:false;
			}
			else{
				if(isset($id_aspecto_actividad)&&$id_aspecto_actividad){
					$aspecto_actividad->setId($id_aspecto_actividad);
					$aspecto_actividad->load();
				}
				else{
					$aspecto_actividad->setIdActividad($id_actividad);
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_aspecto_actividad_action');
				$this->listarAspectoActividad($aspecto_actividad->getIdActividad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_aspecto_actividad');
				$layout = Core_App::getLoadedLayout();
				
				$aspecto_actividad->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_aspecto_actividad')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('aspecto_actividad_add_edit_form') as $block){
					$block->setIdToEdit($aspecto_actividad->getId());
					$block->setObjectToEdit($aspecto_actividad);
				}
			}
		}
	}
	protected function listarAspectoActividad($id_actividad=null){
		Core_App::getLayout()->addActions('listar_admin_aspecto_actividad');
		if($bloque_listado_aspecto_actividad = Core_App::getLoadedLayout()->getBlock('listado_aspecto_actividad')){
			$bloque_listado_aspecto_actividad
				->setIdActividad($id_actividad)
			;
		}
	}
//	protected function listar(){
//		Core_App::getLayout()->addActions('entity_list', 'list_admin_aspecto_actividad');
//		$this->cambiarUrlAjax('administrator/aspecto_actividad/listar');
//	}
//	protected function datalist(){
//		Core_App::getLayout()->setActions(array());//reset
//		Core_App::getLayout()->addActions('datalist', 'datalist_admin_aspecto_actividad');
//	}
	protected function dispatchNode(){
		return;
	}
}
?>