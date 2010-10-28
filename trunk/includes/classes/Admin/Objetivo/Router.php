<?
class Admin_Objetivo_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_objetivo');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_objetivo=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Objetivo()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Objetivo.');
		}
		else{
			if(isset($id_objetivo)){
				Admin_Objetivo_Helper::actionEliminarObjetivo($id_objetivo);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_objetivo=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Objetivo()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Objetivo.');
			//$mensajes[] = 'No tiene permitido editar objetivoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_problemas_asociados = $post&&$post->hasProblemasAsociados()?$post->getProblemasAsociados():null;
			$post_objetivo = $post&&$post->hasObjetivo()?$post->GetObjetivo(true):null;
			$objetivo = new Inta_Model_Objetivo();
			//echo Core_Helper::DebugVars($post_problemas_asociados);
			if(isset($post_objetivo)){
				$objetivo->loadFromArray($post_objetivo->getData());
				//echo Core_Helper::DebugVars($objetivo->getData());
				$guardado = 
					Admin_Objetivo_Helper::actionAgregarEditarObjetivo($objetivo, $post_problemas_asociados)?true:false;
			}
			else{
				if(isset($id_objetivo)){
					$objetivo->setId($id_objetivo);
					$objetivo->load();
				}
				if(!$objetivo->getId())
					$objetivo->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_objetivo)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_objetivo_action');
				$this->listar();
			}
			else{
				$objetivo->addAutofilterOutput('utf8_decode');
				
				$audiencia = new Inta_Model_Audiencia();
				$audiencia->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
				$audiencia->setWhere(Db_Helper::equal('id_agencia'));
				$audiencias = $audiencia->search();
				
				
				$ids_audiencia = array();
				foreach($audiencias as $audiencia)
					$ids_audiencia[] = $audiencia->getId();
				$arr_problema = Admin_Helper::getInstance()->getAgencia()->getListProblema();
				
//				$problema = new Inta_Model_Problema();
//				if($objetivo->getId())
//					$problema->setWhere(Db_Helper::in('id_audiencia', true, $ids_audiencia),' AND (',Db_Helper::equal('id_objetivo', 0),' OR ',Db_Helper::equal('id_objetivo', $objetivo->getId()),')');
//				else 
//					$problema->setWhere(Db_Helper::in('id_audiencia', true, $ids_audiencia),' AND (',Db_Helper::equal('id_objetivo', 0),')');
//				$problemas = $problema->search();
				//echo Core_Helper::DebugVars($ids_audiencia, Admin_Helper::getInstance()->getIdAgencia());
				if(!$arr_problema){
					Admin_App::getInstance()->AddWarningMessage("No hay problemas sin asignar, no puede crear el objetivo");
					return $this->listar();
				}



				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_objetivo');
				$layout = Core_App::getLoadedLayout();

				if($objetivo->getId()&&!$id_objetivo){
					$this->cambiarUrlAjax('administrator/objetivo/addEdit/'.$objetivo->getId());
				}
				//echo Core_Helper::DebugVars($ids_audiencia);
				//echo Core_Helper::DebugVars($problema->searchGetSql());
				foreach($layout->getBlocks('objetivo_add_edit_form') as $block){
					$block->setIdToEdit($objetivo->getId());
					$block->setObjectToEdit($objetivo);
					$block->setProblemas($arr_problema);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_objetivo');
		$this->cambiarUrlAjax('administrator/objetivo/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_objetivo');
	}
	protected function dispatchNode(){
		return;
	}
}
?>