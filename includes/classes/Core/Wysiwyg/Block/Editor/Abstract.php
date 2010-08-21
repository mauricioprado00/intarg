<?
abstract class Core_Wysiwyg_Block_Editor_Abstract extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setHtmlId($this->generateRandomId())
			//->setTemplate("catalog/product/list.phtml")
//			->setMaxItems(3)
//			->setCurrentRelativeIndex(null)
//			->setCurrentEntity(null)
//			->addCustomBlockType('button', 'Core_Block_List_Button')
		;
	}

}
?>