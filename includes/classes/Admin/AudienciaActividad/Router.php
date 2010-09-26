<?
class Admin_AudienciaActividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete',//'listar','datalist',
			'listarAudienciaActividad'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_audiencia_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_audiencia_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_AudienciaActividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar AudienciaActividad.');
		}
		else{
			if(isset($id_audiencia_actividad)){
				c($audiencia_actividad = new Inta_Model_AudienciaActividad())->setId($id_audiencia_actividad)->load();
				Admin_AudienciaActividad_Helper::actionEliminarAudienciaActividad($id_audiencia_actividad);
				return $this->listarAudienciaActividad($audiencia_actividad->getIdActividad());
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del audiencia_actividad.');
	}
	protected function addEdit($id_actividad=null, $id_audiencia_actividad=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_AudienciaActividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar AudienciaActividad.');
			//$mensajes[] = 'No tiene permitido editar audiencia_actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_audiencia_actividad = $post&&$post->hasAudienciaActividad()?$post->GetAudienciaActividad(true):null;
			$audiencia_actividad = new Inta_Model_AudienciaActividad();
			$guardado = false;
			if(isset($post_audiencia_actividad)){
				$audiencia_actividad->loadFromArray($post_audiencia_actividad->getData());
				$guardado =
					//false;
					Admin_AudienciaActividad_Helper::actionAgregarEditarAudienciaActividad($audiencia_actividad)?true:false;
			}
			else{
				if(isset($id_audiencia_actividad)&&$id_audiencia_actividad){
					$audiencia_actividad->setId($id_audiencia_actividad);
					$audiencia_actividad->load();
				}
				else{
					$audiencia_actividad->setIdActividad($id_actividad);
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_audiencia_actividad_action');
				$this->listarAudienciaActividad($audiencia_actividad->getIdActividad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_audiencia_actividad');
				$layout = Core_App::getLoadedLayout();
				
				$audiencia_actividad->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_audiencia_actividad')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('audiencia_actividad_add_edit_form') as $block){
					$block->setIdToEdit($audiencia_actividad->getId());
					$block->setObjectToEdit($audiencia_actividad);
				}
			}
		}
	}
	protected function listarAudienciaActividad($id_actividad=null){
		Core_App::getLayout()->addActions('listar_admin_audiencia_actividad');
		if($bloque_listado_audiencia_actividad = Core_App::getLoadedLayout()->getBlock('listado_audiencia_actividad')){
			$bloque_listado_audiencia_actividad
				->setIdActividad($id_actividad)
			;
		}
	}
//	protected function listar(){
//		Core_App::getLayout()->addActions('entity_list', 'list_admin_audiencia_actividad');
//		$this->cambiarUrlAjax('administrator/audiencia_actividad/listar');
//	}
//	protected function datalist(){
//		Core_App::getLayout()->setActions(array());//reset
//		Core_App::getLayout()->addActions('datalist', 'datalist_admin_audiencia_actividad');
//	}
	protected function dispatchNode(){
		return;
	}
}
?>