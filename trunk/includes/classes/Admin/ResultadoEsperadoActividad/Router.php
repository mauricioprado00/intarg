<?
class Admin_ResultadoEsperadoActividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete',//'listar','datalist',
			'listarResultadoEsperadoActividad'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_resultado_esperado_actividad');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_resultado_esperado_actividad=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ResultadoEsperadoActividad()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar ResultadoEsperadoActividad.');
		}
		else{
			if(isset($id_resultado_esperado_actividad)){
				c($resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad())->setId($id_resultado_esperado_actividad)->load();
				Admin_ResultadoEsperadoActividad_Helper::actionEliminarResultadoEsperadoActividad($id_resultado_esperado_actividad);
				return $this->listarResultadoEsperadoActividad($resultado_esperado_actividad->getIdActividad());
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del resultado_esperado_actividad.');
	}
	protected function addEdit($id_actividad=null, $id_resultado_esperado_actividad=null, $id_objetivo=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ResultadoEsperadoActividad()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar ResultadoEsperadoActividad.');
			//$mensajes[] = 'No tiene permitido editar resultado_esperado_actividades.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_resultado_esperado_actividad = $post&&$post->hasResultadoEsperadoActividad()?$post->GetResultadoEsperadoActividad(true):null;
			$resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad();
			$guardado = false;
			if(isset($post_resultado_esperado_actividad)){
				$resultado_esperado_actividad->loadFromArray($post_resultado_esperado_actividad->getData());
				$guardado =
					//false;
					Admin_ResultadoEsperadoActividad_Helper::actionAgregarEditarResultadoEsperadoActividad($resultado_esperado_actividad)?true:false;
			}
			else{
				if(isset($id_resultado_esperado_actividad)&&$id_resultado_esperado_actividad){
					$resultado_esperado_actividad->setId($id_resultado_esperado_actividad);
					$resultado_esperado_actividad->load();
				}
				else{
					$resultado_esperado_actividad->setIdActividad($id_actividad);
					if($id_objetivo){
						$resultado_esperado_actividad->setIdObjetivo($id_objetivo);
					}
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_resultado_esperado_actividad_action');
				$this->listarResultadoEsperadoActividad($resultado_esperado_actividad->getIdActividad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_resultado_esperado_actividad');
				$layout = Core_App::getLoadedLayout();
				
				$resultado_esperado_actividad->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_resultado_esperado_actividad')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('resultado_esperado_actividad_add_edit_form') as $block){
					$block->setIdToEdit($resultado_esperado_actividad->getId());
					$block->setObjectToEdit($resultado_esperado_actividad);
				}
			}
		}
	}
	protected function listarResultadoEsperadoActividad($id_actividad=null){
		Core_App::getLayout()->addActions('listar_admin_resultado_esperado_actividad');
		if($bloque_listado_resultado_esperado_actividad = Core_App::getLoadedLayout()->getBlock('listado_resultado_esperado_actividad')){
			$bloque_listado_resultado_esperado_actividad
				->setIdActividad($id_actividad)
			;
		}
	}
//	protected function listar(){
//		Core_App::getLayout()->addActions('entity_list', 'list_admin_resultado_esperado_actividad');
//		$this->cambiarUrlAjax('administrator/resultado_esperado_actividad/listar');
//	}
//	protected function datalist(){
//		Core_App::getLayout()->setActions(array());//reset
//		Core_App::getLayout()->addActions('datalist', 'datalist_admin_resultado_esperado_actividad');
//	}
	protected function dispatchNode(){
		return;
	}
}
?>