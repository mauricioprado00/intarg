<?
class Admin_BannerDinamico_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_banner_dinamico');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_banner_dinamico=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_BannerDinamico()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar BannerDinamico.');
		}
		else{
			if(isset($id_banner_dinamico)){
				Admin_BannerDinamico_Helper::actionEliminarBannerDinamico($id_banner_dinamico);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_banner_dinamico=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_BannerDinamico()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar BannerDinamico.');
			//$mensajes[] = 'No tiene permitido editar banner_dinamicoes.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_banner_dinamico = $post&&$post->hasBannerDinamico()?$post->GetBannerDinamico(true):null;
			$banner_dinamico = new Granguia_Model_BannerDinamico();
			Core_App::getUrlModel()->autofilterFieldInput($banner_dinamico, 'contenido');
			$banner_dinamico->addAutofilterOutput('utf8_decode');
			Core_App::getUrlModel()->autofilterFieldOutput($banner_dinamico, 'contenido');
			$banner_dinamico->linksMode(true);
			if(isset($post_banner_dinamico)){
				$banner_dinamico->loadFromArrayWithFilters($post_banner_dinamico->getData());
				$guardado = 
					Admin_BannerDinamico_Helper::actionAgregarEditarBannerDinamico($banner_dinamico)?true:false;
			}
			else{
				if(isset($id_banner_dinamico)){
					$banner_dinamico->setId($id_banner_dinamico);
					$banner_dinamico->load();
				}
			}

			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_banner_dinamico_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_banner_dinamico');
				$layout = Core_App::getLoadedLayout();

				//$banner_dinamico->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('banner_dinamico_add_edit_form') as $block){
					$block->setIdToEdit($banner_dinamico->getId());
					$block->setObjectToEdit($banner_dinamico);
				}
				
				if($banner_dinamico->getId()){
					Admin_App::getInstance()->addWarningMessage('Atencion: modificar el tamaño del banner puede afectar la visualización si el banner ya fué asignado a una categoría o nodo.');
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_banner_dinamico');
		$this->cambiarUrlAjax('administrator/banner_dinamico/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_banner_dinamico');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_banner_dinamico=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_banner_dinamico');
		$layout = Core_App::getLoadedLayout();
		$ordenar_banners_carrousel = $layout->getBlock('ordenar_banner_dinamico');
		$ordenar_banners_carrousel->setIdBannerDinamico($id_banner_dinamico);
		$banner_dinamico = new Granguia_Model_BannerDinamico();
		$banner_dinamico = $banner_dinamico->search('orden');
		$ordenar_banners_carrousel->setBannersCarrousel($banner_dinamico);
	}
	protected function setorden($ids){
		$orden = 0;
		$banner_dinamico = new Granguia_Model_BannerDinamico();
		if(!$ids)
			die();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$banner_dinamico = new Granguia_Model_BannerDinamico();
			$banner_dinamico->setId($id);
			if(!$banner_dinamico->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$banner_dinamico
				->setOrden($orden++)
				->replace()
			;
			//var_dump($banner_dinamico->getData());
		}
		die();
	}
}
?>