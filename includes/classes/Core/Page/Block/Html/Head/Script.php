<?
class Core_Page_Block_Html_Head_Script extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setBooleanData('inline_script')
			->setTemplate('page/html/head/script.phtml')
			->setCharset('ISO-8859-1')
			->setInlineContent(true)
			->setVersion(Core_App::getInstance()->getVersion())
		;
	}
	public function getUid(){
		return md5($this->getCharset().$this->getInlineContent().$this->getVersion());
	}
}
?>