<?
class Frontend_Barrio_Block_Menu extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('barrio/submenu.phtml');
	}
	public function getBarrios(){
		if(!$this->hasData('barrios')){
			$barrio = new Granguia_Model_Barrio();
			$aBarrios = $barrio->search();
			$this->setData('barrios', $aBarrios);
		}
		return $this->getData('barrios'); 
	}
}
?>