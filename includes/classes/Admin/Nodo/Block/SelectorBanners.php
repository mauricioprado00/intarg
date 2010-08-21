<?
class Admin_Nodo_Block_SelectorBanners extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setData('validator_js_name','validar_seleccionar_banners');
		$this->setTemplate('nodo/selector_banners.phtml');
	}
	public function getBannersDinamicos(){
		$nodo = $this->getObjectToEdit();
		return $nodo->getBannersNoUsados();
		
		if(!$this->hasBannersDinamicos()){
			$banner_dinamico = new Granguia_Model_BannerDinamico();
			$banner_dinamicos = $banner_dinamico->search();
			$this->setData('banners_dinamicos', $banner_dinamicos);
		}
		return $this->getData('banners_dinamicos');
	}
	public function getNodoBannersDerecha(){
		$nodo = $this->getObjectToEdit();
		return $nodo->getNodoBannersDinamicosDerecha();
	}
	public function getNodoBannersAbajo(){
		$nodo = $this->getObjectToEdit();
		return $nodo->getNodoBannersDinamicosAbajo();
	}
}
?>