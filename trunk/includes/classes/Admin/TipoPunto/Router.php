<?
class Admin_TipoPunto_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_tipo_punto');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_tipo_punto=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_TipoPunto()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar TipoPunto.');
		}
		else{
			if(isset($id_tipo_punto)){
				Admin_TipoPunto_Helper::actionEliminarTipoPunto($id_tipo_punto);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_tipo_punto=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_TipoPunto()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar TipoPunto.');
			//$mensajes[] = 'No tiene permitido editar tipo_puntoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_tipo_punto = $post&&$post->hasTipoPunto()?$post->GetTipoPunto(true):null;
			$tipo_punto = new Granguia_Model_TipoPunto();
			if(isset($post_tipo_punto)){
				$tipo_punto->loadFromArray($post_tipo_punto->getData());
				$guardado = 
					Admin_TipoPunto_Helper::actionAgregarEditarTipoPunto($tipo_punto)?true:false;
			}
			else{
				if(isset($id_tipo_punto)){
					$tipo_punto->setId($id_tipo_punto);
					$tipo_punto->load();
				}
			}

			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_tipo_punto_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_tipo_punto');
				$layout = Core_App::getLoadedLayout();

				$tipo_punto->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('tipo_punto_add_edit_form') as $block){
					$block->setIdToEdit($tipo_punto->getId());
					$block->setObjectToEdit($tipo_punto);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_tipo_punto');
		$this->cambiarUrlAjax('administrator/tipo_punto/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_tipo_punto');
	}
	protected function dispatchNode(){
		return;
	}
}
?>