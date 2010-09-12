<?
class Admin_Documento_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden',
			'listarDocumentos'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_documento');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_documento=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Documento()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Documento.');
		}
		else{
			if(isset($id_documento)){
				$documento = c(new Inta_Model_Documento())->setId($id_documento);
				if($documento->load()){
					Admin_Documento_Helper::actionEliminarDocumento($id_documento);
					$this->listarDocumentos($documento->getTipoEntidad(), $documento->getIdEntidad());
					return;
				}
			}
		}
		Admin_App::getInstance()->addShieldMessage('Ocurrio un error durante eliminacion del documento.');
	}
	protected function addEdit($tipo_entidad=null,$id_entidad=null,$id_documento=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Inta_Model_Documento()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Documento.');
			//$mensajes[] = 'No tiene permitido editar documentoes.';
			$this->listar();
			//return;
		}
		else{
			if(isset($tipo_entidad,$id_entidad)){
				$resource_type = $tipo_entidad.'_'.$id_entidad;
				Inta_Model_Documento::checkTipoRecurso($resource_type, $tipo_entidad.'/'.$id_entidad);
			}
			else{
				$resource_type = null;
			}
			
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_documento = $post&&$post->hasDocumento()?$post->GetDocumento(true):null;
			$documento = new Inta_Model_Documento();
			if(isset($post_documento)){
				$documento->loadFromArray($post_documento->getData());
				//echo Core_Helper::DebugVars($documento->getData());
				$guardado = 
					Admin_Documento_Helper::actionAgregarEditarDocumento($documento)?true:false;
			}
			else{
				if(isset($id_documento)&&$id_documento){
					$documento
						->setId($id_documento)
					;
					$documento->load();
				}
				else{
					$documento
						->setTipoEntidad($tipo_entidad)
						->setIdEntidad($id_entidad)
					;
				}
			}
			//Admin_App::getInstance()->addShieldMessage(date('His').(isset($post_documento)?'seteado':'no seteado'));
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_documento_action');
				$this->listarDocumentos($documento->getTipoEntidad(), $documento->getIdEntidad());
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_documento');
				$layout = Core_App::getLoadedLayout();

				$documento->addAutofilterOutput('utf8_decode');
				$get = Core_Http_Get::getParameters('Core_Object');
				$target_container = $get->hasTarget()?$get->getTarget():null;
				if(isset($target_container)){
					if($formulario = $layout->getBlock('formulario_edicion_documento')){
						$formulario->setAjaxTarget($target_container);
					}
				}

				foreach($layout->getBlocks('documento_add_edit_form') as $block){
					$block
						->setIdToEdit($documento->getId())
						->setObjectToEdit($documento)
					;
					if(isset($resource_type)){
						$block
							->setResourceType($resource_type)
						;
					}
				}
			}
		}
	}
	protected function listarDocumentos($tipo_entidad=null,$id_entidad=null){
		Core_App::getLayout()->addActions('listar_admin_documentos');
		if($bloque_listado_documentos = Core_App::getLoadedLayout()->getBlock('listado_documentos')){
			$bloque_listado_documentos
				->setIdEntidad($id_entidad)
				->setTipoEntidad($tipo_entidad)
			;
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_documento');
		$this->cambiarUrlAjax('administrator/documento/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_documento');
	}
	protected function dispatchNode(){
		return;
	}
}
?>