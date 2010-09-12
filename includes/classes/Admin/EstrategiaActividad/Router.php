<?
class Admin_EstrategiaActividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete',//'listar','datalist',
			'listarEstrategiaActividad'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_estrategia_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_estrategia_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_EstrategiaActividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar EstrategiaActividad.');
		}
		else{
			if(isset($id_estrategia_actividad)){
				c($estrategia_actividad = new Inta_Model_EstrategiaActividad())->setId($id_estrategia_actividad)->load();
				Admin_EstrategiaActividad_Helper::actionEliminarEstrategiaActividad($id_estrategia_actividad);
				return $this->listarEstrategiaActividad($estrategia_actividad->getIdActividad());
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del estrategia_actividad.');
	}
	protected function addEdit($id_actividad=null, $id_estrategia_actividad=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_EstrategiaActividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar EstrategiaActividad.');
			//$mensajes[] = 'No tiene permitido editar estrategia_actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_estrategia_actividad = $post&&$post->hasEstrategiaActividad()?$post->GetEstrategiaActividad(true):null;
			$estrategia_actividad = new Inta_Model_EstrategiaActividad();
			$guardado = false;
			if(isset($post_estrategia_actividad)){
				$estrategia_actividad->loadFromArray($post_estrategia_actividad->getData());
				$guardado =
					//false;
					Admin_EstrategiaActividad_Helper::actionAgregarEditarEstrategiaActividad($estrategia_actividad)?true:false;
			}
			else{
				if(isset($id_estrategia_actividad)&&$id_estrategia_actividad){
					$estrategia_actividad->setId($id_estrategia_actividad);
					$estrategia_actividad->load();
				}
				else{
					$estrategia_actividad->setIdActividad($id_actividad);
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_estrategia_actividad_action');
				$this->listarEstrategiaActividad($estrategia_actividad->getIdActividad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_estrategia_actividad');
				$layout = Core_App::getLoadedLayout();
				
				$estrategia_actividad->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_estrategia_actividad')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('estrategia_actividad_add_edit_form') as $block){
					$block->setIdToEdit($estrategia_actividad->getId());
					$block->setObjectToEdit($estrategia_actividad);
				}
			}
		}
	}
	protected function listarEstrategiaActividad($id_actividad=null){
		Core_App::getLayout()->addActions('listar_admin_estrategia_actividad');
		if($bloque_listado_estrategia_actividad = Core_App::getLoadedLayout()->getBlock('listado_estrategia_actividad')){
			$bloque_listado_estrategia_actividad
				->setIdActividad($id_actividad)
			;
		}
	}
//	protected function listar(){
//		Core_App::getLayout()->addActions('entity_list', 'list_admin_estrategia_actividad');
//		$this->cambiarUrlAjax('administrator/estrategia_actividad/listar');
//	}
//	protected function datalist(){
//		Core_App::getLayout()->setActions(array());//reset
//		Core_App::getLayout()->addActions('datalist', 'datalist_admin_estrategia_actividad');
//	}
	protected function dispatchNode(){
		return;
	}
}
?>