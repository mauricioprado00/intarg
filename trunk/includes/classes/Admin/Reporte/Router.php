<?
class Admin_Reporte_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist','datalist2',
			'ordenar','setorden','datalist3'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_reporte');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_reporte=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Reporte()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Reporte.');
		}
		else{
			if(isset($id_reporte)){
				Admin_Reporte_Helper::actionEliminarReporte($id_reporte);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_reporte=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Reporte()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Reporte.');
			//$mensajes[] = 'No tiene permitido editar reportees.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_reporte = $post&&$post->hasReporte()?$post->GetReporte(true):null;
			$reporte = new Inta_Model_Reporte();
			if(isset($post_reporte)){
				$reporte->loadFromArray($post_reporte->getData());
				//echo Core_Helper::DebugVars($reporte->getData());
				$guardado =
					Admin_Reporte_Helper::actionAgregarEditarReporte($reporte)?true:false;
			}
			else{
				if(isset($id_reporte)){
					$reporte->setId($id_reporte);
					$reporte->load();
				}
			}
			$id_en_post = $post_reporte&&$post_reporte->getId();
			$mostrar_tabs = $guardado || $id_en_post || $reporte->getId();
			$mostrar_listado = $guardado&&$reporte->getId()&&$post_reporte&&$post_reporte->getId();
			
			if(!$mostrar_tabs){
				Core_App::getLayout()
					->addActions('entity_new')
				;
			}			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_reporte)?'seteado':'no seteado'));
			if($mostrar_listado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_reporte_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_reporte');
				$layout = Core_App::getLoadedLayout();

				if($block_add_edit_list_documentos_reporte = $layout->getBlock('add_edit_list_documentos_reporte')){
					$block_add_edit_list_documentos_reporte->setIdEntidad($reporte->getId());
				}
				if($reporte->getId()&&!$id_reporte){
					$this->cambiarUrlAjax('administrator/reporte/addEdit/'.$reporte->getId());
				}

				$reporte->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('reporte_add_edit_form') as $block){
					$block->setIdToEdit($reporte->getId());
					$block->setObjectToEdit($reporte);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_reporte');
		$this->cambiarUrlAjax('administrator/reporte/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_reporte');
	}
	protected function datalist2(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_reporte2');
	}
	protected function datalist3(){
		Core_Http_Header::ContentType('text/xml');
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_reporte3');
	}
	protected function dispatchNode(){
		return;
	}
}
?>