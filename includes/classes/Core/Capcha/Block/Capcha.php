<?
class Core_Capcha_Block_Capcha extends Core_Block_Template{
	function __construct(){
		parent::__construct();
		//$this->addCustomBlockType('script', 'Core_Page_Block_Html_Head_Script');
		//$this->addCustomBlockType('css', 'Core_Page_Block_Html_Head_Css');
		$this->setTemplate('capcha/capcha.phtml');
		$this
			->setSize(12)
			->setStyle('')
			->setRequestName(Core_Capcha_Helper::getDefaultName())
		;
	}
}
?>