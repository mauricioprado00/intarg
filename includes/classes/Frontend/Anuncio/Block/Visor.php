<?
class Frontend_Anuncio_Block_Visor extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('anuncio/visor.phtml');
	}
	public function getAnuncioDestacado(){
		if(!Core_App::getInstance()->hasAnuncioDestacado())
			return false;
		return Core_App::getInstance()->getAnuncioDestacado();
//		$layout = $this->getLayout();
//		$mapa = $layout->getBlock('mapa');
//		if($mapa){
//			return $mapa->getAnuncioDestacado();
//		}
//		return null;
	}
}
?>