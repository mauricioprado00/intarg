<?
class Admin_Pagina_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_pagina');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_pagina=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Pagina()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar Pagina.');
		}
		else{
			if(isset($id_pagina)){
				Admin_Pagina_Helper::actionEliminarPagina($id_pagina);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_pagina=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_Pagina()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar Pagina.');
			//$mensajes[] = 'No tiene permitido editar paginaes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_pagina = $post&&$post->hasPagina()?$post->GetPagina(true):null;
			$post_nodo = $post&&$post->hasNodo()?$post->GetNodo(true):null;
			if($post_nodo){
				//insertar el nodo
			}
			else{
				$id_nodo = null;
			}
			$pagina = new Granguia_Model_Pagina();
			Core_App::getUrlModel()->autofilterFieldInput($pagina, 'contenido_html');
			$pagina->addAutofilterOutput('utf8_decode');
			Core_App::getUrlModel()->autofilterFieldOutput($pagina, 'contenido_html');
			$nodo = Granguia_Model_Pagina::NewNodo();//new Granguia_Model_Nodo();
			//
			if(isset($post_pagina)){
				$guardado_nodo = true;
				if(isset($post_nodo)){
					$nodo->loadFromArray($post_nodo->getData());
					$guardado_nodo = Admin_Nodo_Helper::actionAgregarEditarNodo($nodo, $post_nodo);
					if($guardado_nodo){
						$id_nodo = $nodo->getId();
						$pagina->setIdNodo($id_nodo);
					}
				}
				
				$pagina->loadFromArrayWithFilters($post_pagina->getData());
				$guardado_pagina = Admin_Pagina_Helper::actionAgregarEditarPagina($pagina)?true:false;
				$guardado = $guardado_pagina && $guardado_nodo;
			}
			else{
				if(isset($id_pagina)){
					$pagina->setId($id_pagina);
					$pagina->load();
					if($pagina->hasIdNodo()&&($id_nodo = $pagina->getIdNodo())){
						$nodo->setId($id_nodo);
						if(!$nodo->load()){
							$nodo = Granguia_Model_Pagina::NewNodo();
							Admin_App::getInstance()->addErrorMessage($this->__t('Error durante la carga del nodo asociado'));
							echo Core_Helper::DebugVars($nodo);
						}
					}
					else{
						Admin_App::getInstance()->addInfoMessage('El pagina actualmente no posee nodo.');
					}
				}
			}
			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_pagina_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_pagina');
				if($pagina->hasTieneNodo() && $pagina->getTieneNodo()==0){
					Core_App::getLayout()->addActions('addedit_admin_pagina_sin_nodo');
				}
				$layout = Core_App::getLoadedLayout();

				foreach($layout->getBlocks('pagina_add_edit_form') as $block){
					$block->setIdToEdit($pagina->getId());
					$block->setObjectToEdit($pagina);
				}
				
				$nodo->addAutofilterOutput('utf8_decode');
				
				$tipo_nodo = new Granguia_Model_TipoNodo();
				$tipos_nodos = $tipo_nodo->search('nombre','DESC');
				$i = 1;
				foreach((array)$layout->getBlocks('nodo_add_edit_form') as $block){
					$block->setIdToEdit($nodo->getId());
					if($tipos_nodos)
						$block->setTiposNodos($tipos_nodos);
					$block->setObjectToEdit($nodo);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_pagina');
		$this->cambiarUrlAjax('administrator/pagina/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_pagina');
	}
	protected function dispatchNode(){
		return;
	}
}
?>