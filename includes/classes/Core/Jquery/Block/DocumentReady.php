<?
class Core_Jquery_Block_DocumentReady extends Core_Page_Block_Html_Head_Script{
	public function __construct(){
		parent::__construct();
		$this
			->setCharset(null)
			->setInlineScript(true)
			//->setTemplate('jquery/document_ready_script.phtml')
			->setInlineContent($cont)
		;
	}
	public function _toHtml(){
		if($this->countChildren()==0)
			return '';
		$content = $this->getTemplateHtml('jquery/document_ready_script.phjs');
		$this->setInlineContent($content);
		return(parent::_toHtml());
	}
}
?>