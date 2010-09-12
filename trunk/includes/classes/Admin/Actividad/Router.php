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
                        $post_actividad = $post&&$post->hasActividad()?$post->GetActividad(true):null;
//                        Mat, meto el link actividad_proyecto
                        $aActividadProyecto = array();
                        $contador = 0;
//                        var_dump($post->actividad_proyecto['id_proyecto']);
//                        var_dump($post->actividad_proyecto['monto_proyecto']);
						if(isset($post->actividad_proyecto))
                        foreach($post->actividad_proyecto['id_proyecto'] As $id_proyecto){
                            $actividad_proyecto = new Inta_Model_ActividadProyecto();
                            $actividad_proyecto->setIdProyecto($id_proyecto);
                            $actividad_proyecto->setMonto($post->actividad_proyecto['monto_proyecto'][$contador]);
                            array_push($aActividadProyecto,$actividad_proyecto);
                            $contador++;
                        }
//                        Mat, meto el link con resultado esperado
                        $aResultadoEsperadoActividad = array();
                        if(isset($post->resultado_esperado_actividad))
                        foreach($post->resultado_esperado_actividad['id_resultado_esperado'] As $id_resultado_esperado){
                            $resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad();
                            $resultado_esperado_actividad->setIdResultadoEsperado($id_resultado_esperado);
                            array_push($aResultadoEsperadoActividad,$resultado_esperado_actividad);
                        }
//			$post_actividad = $post&&$post->hasActividad()?$post->GetActividad(true):null;
			$actividad = new Inta_Model_Actividad();
			if(isset($post_actividad)){
				$actividad->loadFromArray($post_actividad->getData());
				//echo Core_Helper::DebugVars($actividad->getData());
				$guardado = 
					Admin_Actividad_Helper::actionAgregarEditarActividad($actividad,$aActividadProyecto,$aResultadoEsperadoActividad)?true:false;
			}
			else{
				if(isset($id_actividad)){
					$actividad->setId($id_actividad);
					$actividad->load();
				}
			}
			$id_en_post = $post_actividad&&$post_actividad->getId();
			$mostrar_tabs = $guardado || $id_en_post || $actividad->getId();
			$mostrar_listado = $guardado&&$actividad->getId()&&$post_actividad&&$post_actividad->getId();
			
			if(!$mostrar_tabs){
				Core_App::getLayout()
					->addActions('entity_new')
				;
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_actividad)?'seteado':'no seteado'));
			if($mostrar_listado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_actividad_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_actividad');
				$layout = Core_App::getLoadedLayout();

				if($block_add_edit_list_documentos_actividad = $layout->getBlock('add_edit_list_documentos_actividad')){
					$block_add_edit_list_documentos_actividad->setIdEntidad($actividad->getId());
				}

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