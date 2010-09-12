<?
class Admin_UsuarioActividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete',//'listar','datalist',
			'listarUsuarioActividad'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_usuario_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_usuario_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_UsuarioActividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar UsuarioActividad.');
		}
		else{
			if(isset($id_usuario_actividad)){
				c($usuario_actividad = new Inta_Model_UsuarioActividad())->setId($id_usuario_actividad)->load();
				Admin_UsuarioActividad_Helper::actionEliminarUsuarioActividad($id_usuario_actividad);
				return $this->listarUsuarioActividad($usuario_actividad->getIdActividad());
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del usuario_actividad.');
	}
	protected function addEdit($id_actividad=null, $id_usuario_actividad=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_UsuarioActividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar UsuarioActividad.');
			//$mensajes[] = 'No tiene permitido editar usuario_actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_usuario_actividad = $post&&$post->hasUsuarioActividad()?$post->GetUsuarioActividad(true):null;
			$usuario_actividad = new Inta_Model_UsuarioActividad();
			$guardado = false;
			if(isset($post_usuario_actividad)){
				$usuario_actividad->loadFromArray($post_usuario_actividad->getData());
				$guardado =
					//false;
					Admin_UsuarioActividad_Helper::actionAgregarEditarUsuarioActividad($usuario_actividad)?true:false;
			}
			else{
				if(isset($id_usuario_actividad)&&$id_usuario_actividad){
					$usuario_actividad->setId($id_usuario_actividad);
					$usuario_actividad->load();
				}
				else{
					$usuario_actividad->setIdActividad($id_actividad);
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_usuario_actividad_action');
				$this->listarUsuarioActividad($usuario_actividad->getIdActividad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_usuario_actividad');
				$layout = Core_App::getLoadedLayout();
				
				$usuario_actividad->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_usuario_actividad')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('usuario_actividad_add_edit_form') as $block){
					$block->setIdToEdit($usuario_actividad->getId());
					$block->setObjectToEdit($usuario_actividad);
				}
			}
		}
	}
	protected function listarUsuarioActividad($id_actividad=null){
		Core_App::getLayout()->addActions('listar_admin_usuario_actividad');
		if($bloque_listado_usuario_actividad = Core_App::getLoadedLayout()->getBlock('listado_usuario_actividad')){
			$bloque_listado_usuario_actividad
				->setIdActividad($id_actividad)
			;
		}
	}
//	protected function listar(){
//		Core_App::getLayout()->addActions('entity_list', 'list_admin_usuario_actividad');
//		$this->cambiarUrlAjax('administrator/usuario_actividad/listar');
//	}
//	protected function datalist(){
//		Core_App::getLayout()->setActions(array());//reset
//		Core_App::getLayout()->addActions('datalist', 'datalist_admin_usuario_actividad');
//	}
	protected function dispatchNode(){
		return;
	}
}
?>