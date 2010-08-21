<?
class Core_Jquery_Block_DocumentReadyScript extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setBooleanData('inline_script')
			//->setTemplate('jquery/document_ready_script.phtml')
			->setInlineContent(true)
		;
	}
	public function _toHtml(){
		if($this->getInlineScript()){
			return "\n".$this->getInlineContent()."\n";
		}
		return "\n".parent::_toHtml()."\n";
	}
}
?>