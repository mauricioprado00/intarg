<?
class Core_Ui_Block_Tabset extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this->setTemplate('ui/tabset.phtml');
	}
}
?>