<?
class Admin_Actividad_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist','listar_planificadas','listar_iniciadas',
			'datalist2',
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
	protected function addEdit($id_actividad=null,$id_resultado_esperado=null){
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
//			Mat, meto el link actividad_proyecto
			$aActividadProyecto = array();
			$contador = 0;
//			var_dump($post->actividad_proyecto['id_proyecto']);
//			var_dump($post->actividad_proyecto['monto_proyecto']);
			if(isset($post->actividad_proyecto))
			foreach($post->actividad_proyecto['id_proyecto'] As $id_proyecto){
			    $actividad_proyecto = new Inta_Model_ActividadProyecto();
			    $actividad_proyecto->setIdProyecto($id_proyecto);
			    $actividad_proyecto->setMonto($post->actividad_proyecto['monto_proyecto'][$contador]);
			    array_push($aActividadProyecto,$actividad_proyecto);
			    $contador++;
			}
//			Mat, meto el link con resultado esperado
			$aResultadoEsperadoActividad = array();
			if(isset($post->resultado_esperado_actividad))
				foreach($post->resultado_esperado_actividad['id_resultado_esperado'] As $id_resultado_esperado){
				    $resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad();
				    $resultado_esperado_actividad->setIdResultadoEsperado($id_resultado_esperado);
				    array_push($aResultadoEsperadoActividad,$resultado_esperado_actividad);
				}
//			$post_actividad = $post&&$post->hasActividad()?$post->GetActividad(true):null;
			$actividad = new Inta_Model_Actividad();
			$volver_a_listado_re = false;
			if(isset($post_actividad)){
				$actividad->loadFromArray($post_actividad->getData());
				if(!$actividad->getAno())
					$actividad->setAno(date('Y'));
//			echo Core_Helper::DebugVars($actividad->getData());
				$guardado = 
					Admin_Actividad_Helper::actionAgregarEditarActividad($actividad,$aActividadProyecto,$aResultadoEsperadoActividad)?true:false;
			}
			else{
				if(isset($id_actividad)){
					$actividad->setId($id_actividad);
					$actividad->load();
				}
				if(!$id_actividad && $id_resultado_esperado){
					$volver_a_listado_re = true;
					//guardar en sesion
					$resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad();
					$resultado_esperado_actividad
						->setIdActividad(0)
						->setIdResultadoEsperado($id_resultado_esperado)
						->replace()
					;
				}
			}
			if(!$volver_a_listado_re && $post &&$post->hasData('volver_a_listado_re')){
				$volver_a_listado_re = true;
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
			if($volver_a_listado_re && $guardado){
//				$url = 'administrator/ajax/resultado_esperado/listar';
//				Core_Http_Header::Redirect(Core_App::getUrlModel()->getUrl($url));
				$r = new Admin_ResultadoEsperado_Router();
				return $r->route('listar');
			}
			elseif($mostrar_listado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_actividad_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_actividad');
				if($actividad->getId()){
					Core_App::getLayout()->addActions('edit_actividad_estado_'.$actividad->getEstado());
				}
				$layout = Core_App::getLoadedLayout();

				if($block_add_edit_list_documentos_actividad = $layout->getBlock('add_edit_list_documentos_actividad')){
					$block_add_edit_list_documentos_actividad->setIdEntidad($actividad->getId());
				}
				if($block_add_edit_list_aspecto_actividad = $layout->getBlock('add_edit_list_aspecto_actividad')){
					$block_add_edit_list_aspecto_actividad->setIdActividad($actividad->getId());
				}
				if($block_add_edit_list_audiencia_actividad = $layout->getBlock('add_edit_list_audiencia_actividad')){
					$block_add_edit_list_audiencia_actividad->setIdActividad($actividad->getId());
				}
				if($block_add_edit_list_estrategia_actividad = $layout->getBlock('add_edit_list_estrategia_actividad')){
					$block_add_edit_list_estrategia_actividad->setIdActividad($actividad->getId());
				}
				if($block_add_edit_list_usuario_actividad = $layout->getBlock('add_edit_list_usuario_actividad')){
					$block_add_edit_list_usuario_actividad->setIdActividad($actividad->getId());
				}
				if($block_add_edit_list_proyecto_actividad = $layout->getBlock('add_edit_list_proyecto_actividad')){
					$block_add_edit_list_proyecto_actividad->setIdActividad($actividad->getId());
				}
				if($block_add_edit_list_resultado_esperado_actividad = $layout->getBlock('add_edit_list_resultado_esperado_actividad')){
					$block_add_edit_list_resultado_esperado_actividad->setIdActividad($actividad->getId());
				}
				if($actividad->getId()&&!$id_actividad){
					$this->cambiarUrlAjax('administrator/actividad/addEdit/'.$actividad->getId());
				}


				$actividad->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('actividad_add_edit_form') as $block){
					$block->setIdToEdit($actividad->getId());
					$block->setObjectToEdit($actividad);
				}
				foreach($layout->getBlocks('formulario_edicion_actividad') as $block){
					if($volver_a_listado_re){
						$i = $block->appendBlock('<input_text />');// ->appendXmlBlocks('<input_text />');
						$i
							->setValue('1')
							->setHtmlName('volver_a_listado_re')
						;
					}
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
		if($args = func_get_args()){
			if($block = Core_App::getLoadedLayout()->getBlock('xml_data_admin_actividad')){
				if(count($args)%2===0){
					$filtros = array();
					$fieldname = null;
					for($i=0;$i<count($args);$i++){
						if(($i%2)==0){
							$fieldname = $args[$i];
							continue;
						}
						$filtros[$fieldname] = $args[$i];
					}
				}
				$filtros['id_agencia'] = Admin_Helper::getInstance()->getIdAgenciaSeleccionada();
				$block->setHardFiltros($filtros);
			}
		}
	}
	protected function datalist2(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_actividad2');
		if($args = func_get_args()){
			$wheres = array();
			if($block = Core_App::getLoadedLayout()->getBlock('xml_data_admin_actividad')){
				if(count($args)%2===0){
					$filtros = array();
					$fieldname = null;
					for($i=0;$i<count($args);$i++){
						if(($i%2)==0){
							$fieldname = $args[$i];
							continue;
						}
						//$filtros[$fieldname] = $args[$i];
						$wheres[] = Db_Helper::equal($fieldname, $args[$i]);
					}
				}
				//$filtros['id_agencia'] = Admin_Helper::getInstance()->getIdAgencia();
				$wheres[] = Db_Helper::equal('responsable_id_agencia', Admin_Helper::getInstance()->getIdAgenciaSeleccionada());
				$block->setHardFiltros($wheres);
//				var_dump($wheres);
//				die();
			}
		}
	}
	protected function listar_planificadas(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_actividad');
		if($block = Core_App::getLoadedLayout()->getBlock('listado_datos_grid')){
			$block
				->setCaption('Actividades Planificadas')
				//->setSource('administrator/actividad/datalist/estado/planificado')
				->setSource('administrator/actividad/datalist2/actividad_estado/planificado')
			;
		}
		$this->cambiarUrlAjax('administrator/actividad/listar_planificadas');
	}
	protected function listar_iniciadas(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_actividad');
		if($block = Core_App::getLoadedLayout()->getBlock('listado_datos_grid')){
			$block
				->setCaption('Actividades en Curso')
				//->setSource('administrator/actividad/datalist/estado/parcial')
				->setSource('administrator/actividad/datalist2/actividad_estado/parcial')
			;
		}
		$this->cambiarUrlAjax('administrator/actividad/listar_iniciadas');
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