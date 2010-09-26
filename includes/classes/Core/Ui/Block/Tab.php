<?
class Core_Ui_Block_Tab extends Core_Block_Template{
	public function _construct(){
		parent::_construct();
		$this
			->setTranslate('title')
			->setTemplate('ui/tab.phtml')
		;
	}
}
?>