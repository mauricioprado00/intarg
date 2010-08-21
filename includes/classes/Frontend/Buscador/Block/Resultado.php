<?
class Frontend_Buscador_Block_Resultado extends Core_Block_List_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate("buscador/resultado.phtml")
			->setMaxItems(Granguia_Model_Config::findConfigValue('frontend_buscador/int_cantidad_items_buscador', 3))
		;
	}
	protected function search(){
		if(!$this->hasSearchObject()||!$this->getSearchObject())
			return null;
		return $this->getSearchObject()->search(null, null, $this->getMaxItems(), $this->getPagina()*$this->getMaxItems(),get_class($this->getSearchObject()));
	} 
	protected function searchCount(){
		if(!$this->hasSearchObject()||!$this->getSearchObject())
			return 0;
		return $this->getSearchObject()->searchCount();
	}
}
?>