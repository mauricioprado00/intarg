<?
class Admin_Nodo_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'validate'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_nodo');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_nodo=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Nodo()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos||true){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Nodo.');
		}
		else{
			if(isset($id_nodo)){
				Admin_Nodo_Helper::actionEliminarNodo($id_nodo);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_nodo=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$permisos = true;
		$guardado = false;
		if(!isset($id_nodo)||$id_nodo==''){//esto es para no dejar dar de alta nodos desde aca
			$post = Core_Http_Post::getParameters('Core_Object');
			if(!$post||!count($post->getData())||!$post->GetNodo()||!$post->GetNodo(true)->getId())
				$permisos = false;
		}
		$permisos &= Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Nodo()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido '.(!$id_nodo?'agregar':'editar').' Nodo.');
			//$mensajes[] = 'No tiene permitido editar nodoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
			$nodo = new Granguia_Model_Nodo();
			if(isset($post_nodo)){
				$nodo->loadFromArray($post_nodo->getData());
				$guardado = 
					Admin_Nodo_Helper::actionAgregarEditarNodo($nodo, $post_nodo)?true:false;
			}
			else{
				if(isset($id_nodo)){
					$nodo->setId($id_nodo);
					$nodo->load();
					if($nodo->getPathUrl()=='administrator/%'){
						Core_App::getLayout()->addActions('security_restriction');
						Admin_App::getInstance()->addShieldMessage('No tiene permitido '.(!$id_nodo?'agregar':'editar').' el nodo de la página de administración.');
						//$mensajes[] = 'No tiene permitido editar nodoes.';
						$this->listar();
						return;
					}
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_nodo_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_nodo');
				$layout = Core_App::getLoadedLayout();
				
				$nodo->addAutofilterOutput('utf8_decode');
				
				$tipo_nodo = new Granguia_Model_TipoNodo();
				$tipos_nodos = $tipo_nodo->search('nombre');
				foreach($layout->getBlocks('nodo_add_edit_form') as $block){
					$block->setIdToEdit($nodo->getId());
					if($tipos_nodos)
						$block->setTiposNodos($tipos_nodos);
					$block->setObjectToEdit($nodo);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_nodo');
		$this->cambiarUrlAjax('administrator/nodo/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_nodo');
	}
	protected function validate(){
		$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
		$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
		$nodo = new Granguia_Model_Nodo();
		if(isset($post_nodo)){
			$nodo->loadFromArray($post_nodo->getData());
			echo Admin_Nodo_Helper::getValidationMessage($nodo);
			die();
		}
		die('kradkk'.__FILE__.__LINE__);
	}
	protected function dispatchNode(){
		return;
	}
}
?>