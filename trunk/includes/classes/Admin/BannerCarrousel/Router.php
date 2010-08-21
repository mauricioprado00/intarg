<?
class Admin_BannerCarrousel_Router extends Core_Router_Abstract{
	protected function initialize(){
		//$this->addRouter('admin','Router.Admin');
		$this->addActions(
			'cerrar_sesion',
			'addEdit','delete','listar','datalist',
			'ordenar','setorden'
		);
	}
	protected function onThrought(){
		Core_App::getLayout()->addActions('modulo','modulo_admin_banner_carrousel');
	}
	private function cambiarUrlAjax($link_url){
		$helper_url_ajax = Core_App::getLoadedLayout()
				->getBlock('helper_url_ajax');
		if($helper_url_ajax){
			$helper_url_ajax->setCurrentLinkUrl(Core_App::getUrlModel()->getUrl($link_url));
		}
	}
	protected function delete($id_banner_carrousel=null){
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_BannerCarrousel()), 'd');
		Core_App::getInstance()->clearLastErrorMessages();
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido borrar BannerCarrousel.');
		}
		else{
			if(isset($id_banner_carrousel)){
				Admin_BannerCarrousel_Helper::actionEliminarBannerCarrousel($id_banner_carrousel);
			}
		}
		$this->listar();
	}
	protected function addEdit($id_banner_carrousel=null){
		Core_App::getInstance()->clearLastErrorMessages();
		$guardado = false;
		$permisos = Admin_User_Model_User::getLogedUser()->checkPrivilegio(get_class(new Granguia_Model_BannerCarrousel()), 'w');
		if(!$permisos){
			Core_App::getLayout()->addActions('security_restriction');
			Admin_App::getInstance()->addShieldMessage('No tiene permitido editar BannerCarrousel.');
			//$mensajes[] = 'No tiene permitido editar banner_carrouseles.';
			$this->listar();
			//return;
		}
		else{
			$post = Core_Http_Post::hasParameters()?Core_Http_Post::getParameters('Core_Object'):null;
			$post_banner_carrousel = $post&&$post->hasBannerCarrousel()?$post->GetBannerCarrousel(true):null;
			$banner_carrousel = new Granguia_Model_BannerCarrousel();
			if(isset($post_banner_carrousel)){
				$banner_carrousel->loadFromArray($post_banner_carrousel->getData());
				$guardado = 
					Admin_BannerCarrousel_Helper::actionAgregarEditarBannerCarrousel($banner_carrousel)?true:false;
			}
			else{
				if(isset($id_banner_carrousel)){
					$banner_carrousel->setId($id_banner_carrousel);
					$banner_carrousel->load();
				}
			}

			if($guardado){
				Core_App::getLayout()->addActions('entity_addedit_action', 'addedit_admin_banner_carrousel_action');
				$this->listar();
			}
			else{
				Core_App::getLayout()->addActions('entity_addedit', 'addedit_admin_banner_carrousel');
				$layout = Core_App::getLoadedLayout();

				$banner_carrousel->addAutofilterOutput('utf8_decode');
				
				foreach($layout->getBlocks('banner_carrousel_add_edit_form') as $block){
					$block->setIdToEdit($banner_carrousel->getId());
					$block->setObjectToEdit($banner_carrousel);
				}
			}
		}
	}
	protected function listar(){
		Core_App::getLayout()->addActions('entity_list', 'list_admin_banner_carrousel');
		$this->cambiarUrlAjax('administrator/banner_carrousel/listar');
	}
	protected function datalist(){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('datalist', 'datalist_admin_banner_carrousel');
	}
	protected function dispatchNode(){
		return;
	}
	protected function ordenar($id_banner_carrousel=null){
		Core_App::getLayout()->setActions(array());//reset
		Core_App::getLayout()->addActions('ordenar_banner_carrousel');
		$layout = Core_App::getLoadedLayout();
		$ordenar_banners_carrousel = $layout->getBlock('ordenar_banner_carrousel');
		$ordenar_banners_carrousel->setIdBannerCarrousel($id_banner_carrousel);
		$banner_carrousel = new Granguia_Model_BannerCarrousel();
		$banner_carrousel->setEsActiva(1);
		$banner_carrousel->setWhere(Db_Helper::equal('es_activa'));
		$banner_carrousel = $banner_carrousel->search('orden');
		$ordenar_banners_carrousel->setBannersCarrousel($banner_carrousel);
	}
	protected function setorden($ids){
		$orden = 0;
		$banner_carrousel = new Granguia_Model_BannerCarrousel();
		if(!$ids)
			die();
		$banner_carrousel->resetOrden();
		foreach(explode(',', $ids) as $id){
			if(!strlen($id))
				continue;
			$banner_carrousel = new Granguia_Model_BannerCarrousel();
			$banner_carrousel->setId($id);
			if(!$banner_carrousel->load()){
				var_dump('cant load '.$id);
				continue;
			}
			$banner_carrousel
				->setOrden($orden++)
				->replace()
			;
			//var_dump($banner_carrousel->getData());
		}
		die();
	}
}
?>