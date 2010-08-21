<?
class Admin_Categoria_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist','tree','change'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_categoria');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_categoria=null,$goback='list'){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Categoria()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Categorias.');
		}
		else{
			if(isset($id_categoria)){
				Admin_Categoria_Helper::actionEliminarCategoria($id_categoria);
			}
		}
		if($goback=='tree')
			$this->tree();
		else
			$this->listar();
	}
	protected function addEdit($id_categoria=null,$goback='list'){
		if($id_categoria=='-')$id_categoria=null;
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Categoria()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Categorias.');
			//$mensajes[] = 'No tiene permitido editar categoriaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_categoria = $post&&$post->hasCategoria()?$post->GetCategoria(true):null;
			$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
			if($post_nodo){
				//insertar el nodo
			}
			else{
				$id_nodo = null;
			}
			$categoria = new Granguia_Model_Categoria();
			$nodo = Granguia_Model_Categoria::NewNodo();//new Granguia_Model_Nodo();
			//
			if(isset($post_categoria)){
				$guardado_nodo = true;
				$categoria->loadFromArray($post_categoria->getData());
				if(isset($post_nodo)){
					$nodo->loadFromArray($post_nodo->getData());
					$nodo->setTypeInstance($categoria, true);
					$guardado_nodo = Admin_Nodo_Helper::actionAgregarEditarNodo($nodo, $post_nodo);
					if($guardado_nodo){
//						$id_nodo = $nodo->getId();
//						$categoria->setIdNodo($id_nodo);
					}
				}
				$guardado_categoria = Admin_Categoria_Helper::actionAgregarEditarCategoria($categoria)?true:false;
				$guardado = $guardado_categoria && $guardado_nodo;
			}
			else{
				if(isset($id_categoria)){
					$categoria->setId($id_categoria);
					$categoria->load();
					if($categoria->hasIdNodo()&&($id_nodo = $categoria->getIdNodo())){
						$nodo->setId($id_nodo);
						if(!$nodo->load()){
							$nodo = Granguia_Model_Categoria::NewNodo();
							Admin_App::getInstance()->addErrorMessage('Error durante la carga del nodo asociado');
							echo Core_Helper::DebugVars($nodo);
						}
					}
					else{
						Admin_App::getInstance()->addInfoMessage('El categoria actualmente no posee nodo.');
					}
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_categoria_action');
				if($post->hasGoback()&&$post->getGoback()=='tree')
					$this->tree();
				else
					$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_categoria');
				$layout = Core_App::getLoadedLayout();

				if(isset($goback)){
					//$layout = Core_App::getLoadedLayout();
					
					#metodo 1
//					$goback = htmlentities($goback).$add_edit_form->getNameInLayout();
//					$layout->appendXmlReferences('
//						<reference name="elform">
//							<block type="Core_Page/Html_Tag_Input_Hidden" name="goback" value="'.$goback.'" />
//						</reference>'
//					);
					if($add_edit_form){
						#metodo 2
						$elform = $layout->getBlock('elform');
						$elform->appendBlock('<input_hidden name="goback" />')->setValue($goback);
						#metodo 3 (sin alias)
						//$elform->appendBlock('<block type="Core_Page/Html_Tag_Input_Hidden" name="goback" />')->setValue($goback);
						#metodo 4 (require cambios en el template)
//						$obj = new Core_Object();
//						$obj->setName('goback')->setValue($goback);
//						$elform->appHidden($obj);
					}
				}

				$categoria->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('categoria_add_edit_form') as $block){
					$block->setIdToEdit($categoria->getId());
					$block->setObjectToEdit($categoria);
					$block->setTree(Admin_Categoria_Helper::getTree());
				}
				
				$nodo->addAutofilterOutput('utf8_decode');
				
				$tipo_nodo = new Granguia_Model_TipoNodo();
				$tipos_nodos = $tipo_nodo->search('nombre','DESC');
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
		Core_App::getLayout()->addActions('entity_list', 'list_admin_categoria');
		$this->cambiarUrlAjax('administrator/categoria/listar');
	}
	protected function tree(){
		Core_App::getLayout()->addActions('tree_admin_categoria');
		$tree = Core_App::getLoadedLayout()
				->getBlock('tree_categoria');
		$tree->setTree(Admin_Categoria_Helper::getTree());
		$this->cambiarUrlAjax('administrator/categoria/tree');
	}
	protected function change(){
		$post = Core_Http_Post::getParameters('Core_Object');
		if(!$post->getId()){
			echo "no data";
			die();
		}
		$categoria = new Granguia_Model_Categoria();
		$categoria->setId($post->getId());
		if(!$categoria->load()){
			echo "cant load";
			die();
		}
		//var_dump($categoria);
		$categoria->setIdCategoriaPadre($post->getIdCategoriaPadre());
		$categoria->update();
		$categoria->Posicionar($post->getOrden());
		echo "yeee";
		die();
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_categoria');
	}
	protected function dispatchNode(){
		return;
	}
}
?>