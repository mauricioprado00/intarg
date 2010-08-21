<?
class Admin_Menu_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_menu');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_menu=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Menu()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Menu.');
		}
		else{
			if(isset($id_menu)){
				Admin_Menu_Helper::actionEliminarMenu($id_menu);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_menu=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Menu()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Menu.');
			//$mensajes[] = 'No tiene permitido editar menues.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_menu = $post&&$post->hasMenu()?$post->GetMenu(true):null;
			$menu = new Granguia_Model_Menu();
			if(isset($post_menu)){
				$menu->loadFromArray($post_menu->getData());
				$guardado = 
					Admin_Menu_Helper::actionAgregarEditarMenu($menu)?true:false;
			}
			else{
				if(isset($id_menu)){
					$menu->setId($id_menu);
					$menu->load();
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_menu_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_menu');
				$layout = Core_App::getLoadedLayout();

				$menu->addAutofilterOutput('utf8_decode');
				
				
				
				$nodo = new Granguia_Model_NodoView();
				$nodo->setEsActiva(1);
				$nodo->setWhere(Db_Helper::equal('es_activa'));
				//echo $nodo->searchGetSql('tipo, nombre');
				$nodos = $nodo->search('id_tipo_nodo,nombre,id');
				$nodo = new Granguia_Model_NodoView();
				$nodo->setTipo('Url Externa')->setNombre('Ingresar')->setId('');
				array_unshift($nodos, $nodo);
				foreach($layout->getBlocks('menu_add_edit_form') as $block){
					$block->setIdToEdit($menu->getId());
					if($nodos)
						$block->setOpcionesNodos($nodos);
					$block->setObjectToEdit($menu);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_menu');
		$this->cambiarUrlAjax('administrator/menu/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_menu');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_menu=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_menu');
		$layout = Core_App::getLoadedLayout();
		$ordenar_menu = $layout->getBlock('ordenar_menu');
		$ordenar_menu->setIdMenu($id_menu);
		$menu = new Granguia_Model_Menu();
		$menues = $menu->search('orden');
		$ordenar_menu->setMenues($menues);
	}
	protected function setorden($ids){
		$orden = 0;
		$menu = new Granguia_Model_Menu();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$menu = new Granguia_Model_Menu();
			$menu->setId($id);
			if(!$menu->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$menu
				->setOrden($orden++)
				->replace()
			;
			//var_dump($menu->getData());
		}
		die();
	}
}
?>