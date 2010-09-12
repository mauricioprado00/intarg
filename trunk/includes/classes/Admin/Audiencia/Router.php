<?
class Admin_Audiencia_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_audiencia');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_audiencia=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Audiencia()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Audiencia.');
		}
		else{
			if(isset($id_audiencia)){
				Admin_Audiencia_Helper::actionEliminarAudiencia($id_audiencia);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_audiencia=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Audiencia()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Audiencia.');
			//$mensajes[] = 'No tiene permitido editar audienciaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_audiencia = $post&&$post->hasAudiencia()?$post->GetAudiencia(true):null;
			$audiencia = new Inta_Model_Audiencia();
			$guardado = false;
			if(isset($post_audiencia)){
				$audiencia->loadFromArray($post_audiencia->getData());
				//echo Core_Helper::DebugVars($audiencia->getData());
				$guardado =
					//false;
					Admin_Audiencia_Helper::actionAgregarEditarAudiencia($audiencia)?true:false;
			}
			else{
				if(isset($id_audiencia)){
					$audiencia->setId($id_audiencia);
					$audiencia->load();
				}
				if(!$audiencia->getId())
					$audiencia->setIdAgencia(Admin_Helper::getInstance()->getIdAgencia());
			}
			//aca un brain killer:
			/*
1er)entro a agregar
	post: no, id_en_post: no, id: no, guardado: no, mostrar_tabs: no, listado: no
2do)envio el form luego de entrar a agregar (da error al guardar) 
	post: si, id_en_post: no, id: no, guardado: no, mostrar_tabs: no, listado: no 
3er)envio el form luego de entrar a agregar (no da error)
	post: si, id: si, id_en_post: no, guardado: si, mostrar_tabs: si, listado: no
4to)envio el form luego de completar los demas tabs de la nueva entidad
	post: si, id: si, id_en_post: si, guardado: si, mostrar_tabs: si, listado: si
5to)edito uno
	post: si, id: si, id_en_post: no, guardado: no, mostrar_tabs: si, listado: no
6to)edito uno y da error al guardar
	post: si, id: si, id_en_post: si, guardado: no, mostrar_tabs: si, listado: no

			*/
			$id_en_post = $post_audiencia&&$post_audiencia->getId();
			$mostrar_tabs = $guardado || $id_en_post || $audiencia->getId();
			$mostrar_listado = $guardado&&$audiencia->getId()&&$post_audiencia&&$post_audiencia->getId();
			
			if(!$mostrar_tabs){
				Core_App::getLayout()
					->addActions('entity_new')
				;
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_audiencia)?'seteado':'no seteado'));
			if($mostrar_listado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_audiencia_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_audiencia');
				$layout = Core_App::getLoadedLayout();
				
//				if($block_listado_documentos = $layout->getBlock('listado_documentos')){
//					$block_listado_documentos->setIdEntidad($audiencia->getId());
//				}
				if($block_add_edit_list_documentos_audiencia = $layout->getBlock('add_edit_list_documentos_audiencia')){
					$block_add_edit_list_documentos_audiencia->setIdEntidad($audiencia->getId());
				}

				$audiencia->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('audiencia_add_edit_form') as $block){
					$block->setIdToEdit($audiencia->getId());
					$block->setObjectToEdit($audiencia);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_audiencia');
		$this->cambiarUrlAjax('administrator/audiencia/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_audiencia');
	}
	protected function dispatchNode(){
		return;
	}
}
?>