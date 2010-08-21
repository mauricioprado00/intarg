<?php
class Frontend_Anuncio_Block_Minisitio extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('anuncio/minisitio.phtml');
	}
	protected function cargarAnuncio(){
		if($this->hasAnuncio())
			return true;
		$anuncio = new Granguia_Model_Anuncio();
		$anuncio->loadFromArray($this->getData());//cargo las propiedades asignadas al bloque (en el layout)
		$continuar = false;
		foreach($anuncio->getData() as $key=>$value)
			if(isset($value)){
				$continuar = true;
				break;
			}
		if($continuar){
			if($anuncio->load(null, true)){
				$this->setAnuncio($anuncio);
				return true;
			}
		}
		if($anuncio = Core_App::getInstance()->getAnuncio()){
			$this->setAnuncio($anuncio);
			return true;
		}
		return false;
	} 
	protected function prepararAnuncio(){
		if($this->cargarAnuncio()){
			Core_App::getUrlModel()->autofilterFieldOutput($this->getAnuncio(), 'minisitio');
		}
	}
	protected function _beforeToHtml(){
		$this->prepararAnuncio();
	}
}
?>