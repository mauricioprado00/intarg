<?
class Admin_ResultadoEsperado_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden',
			'medios_verificacion'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_resultado_esperado');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_resultado_esperado=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ResultadoEsperado()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar ResultadoEsperado.');
		}
		else{
			if(isset($id_resultado_esperado)){
				Admin_ResultadoEsperado_Helper::actionEliminarResultadoEsperado($id_resultado_esperado);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_resultado_esperado=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_ResultadoEsperado()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar ResultadoEsperado.');
			//$mensajes[] = 'No tiene permitido editar resultado_esperadoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_indicadores = $post&&$post->hasIndicadores()?$post->getIndicadores():null;
			$post_eliminar_indicadores = $post&&$post->hasEliminarIndicadores()?$post->getEliminarIndicadores():null;
			$post_resultado_esperado = $post&&$post->hasResultadoEsperado()?$post->GetResultadoEsperado(true):null;
			$resultado_esperado = new Inta_Model_ResultadoEsperado();
			//echo Core_Helper::DebugVars($post_problemas_asociados);
			if(isset($post_resultado_esperado)){
				$resultado_esperado->loadFromArray($post_resultado_esperado->getData());
				$agregando = $resultado_esperado->getId()?false:true;
				//echo Core_Helper::DebugVars($resultado_esperado->getData());
				$guardado = 
					Admin_ResultadoEsperado_Helper::actionAgregarEditarResultadoEsperado($resultado_esperado, $post_indicadores, $post_eliminar_indicadores)?true:false;
			}
			else{
				$agregando = !isset($id_resultado_esperado) || !$id_resultado_esperado;
				if(isset($id_resultado_esperado)){
					$resultado_esperado->setId($id_resultado_esperado);
					$resultado_esperado->load();
				}
				if(!$resultado_esperado->getId())
					$resultado_esperado->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
			}
			if($agregando)
				Core_App::getLayout()
					->addActions('entity_new')
				;

			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_resultado_esperado)?'seteado':'no seteado'));
			if($guardado&&!$agregando){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_resultado_esperado_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_resultado_esperado');
				$layout = Core_App::getLoadedLayout();

				$resultado_esperado->addAutofilterOutput('utf8_decode');
				
				$audiencia = new Inta_Model_Audiencia();
				$audiencia->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
				$audiencia->setWhere(Db_Helper::equal('id_agencia'));
				$audiencias = $audiencia->search();
				
				
				$ids_audiencia = array();
				foreach($audiencias as $audiencia)
					$ids_audiencia[] = $audiencia->getId();
				$problema = new Inta_Model_Problema();
				if($resultado_esperado->getId())
					$problema->setWhere(Db_Helper::in('id_audiencia', true, $ids_audiencia),' AND (',Db_Helper::equal('id_resultado_esperado', 0),' OR ',Db_Helper::equal('id_resultado_esperado', $resultado_esperado->getId()),')');
				else 
					$problema->setWhere(Db_Helper::in('id_audiencia', true, $ids_audiencia),' AND (',Db_Helper::equal('id_resultado_esperado', 0),')');
				$problemas = $problema->search();
				//echo Core_Helper::DebugVars($ids_audiencia);
				//echo Core_Helper::DebugVars($problema->searchGetSql());
				
				foreach($layout->getBlocks('resultado_esperado_add_edit_form') as $block){
					$block->setIdToEdit($resultado_esperado->getId());
					$block->setObjectToEdit($resultado_esperado);
					$block->setProblemas($problemas);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_resultado_esperado');
		$this->cambiarUrlAjax('administrator/resultado_esperado/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_resultado_esperado');
	}
	protected function dispatchNode(){
		return;
	}
	protected function medios_verificacion(){
		if(Core_Http_Post::hasParameters()){
			$post_data = Core_Http_Post::getParameters('Core_Object');
			if($post_data->hasIdIndicador()){
				$medio_verificacion = new Inta_Model_MedioVerificacion();
				$medio_verificacion
					->setIdIndicador($post_data->getIdIndicador())
					->setWhere(Db_Helper::equal('id_indicador'))
				;
				$medio_verificacions = $medio_verificacion->search();
				$html_options = '';
				foreach($medio_verificacions as $medio_verificacion){
					$option = new Core_Html_Tag_Custom('option');
					$option
						->setValue($medio_verificacion->getId())
						->setInnerHtml($medio_verificacion->getNombre())
					;
					$html_options .= $option->getHtml();
				}
				echo $html_options;
			}
		}
		die();
	}
}
?>