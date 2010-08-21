<?
class Frontend_BannerCarrousel_Block_BannerCarrousel extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('page/carrousel.phtml');
	}
	public function getBannersCarrousel(){
                $bannersCarrousel = new Granguia_Model_BannerCarrousel();
                $bannersCarrousel->setEsActiva('1');
                $bannersCarrousel->setWhere(Db_Helper::equal('es_activa'));
                return $bannersCarrousel->search();
	}
}
?>