<?
class Core_Page_Block_Html_Document extends Core_Block_Template{
	function __construct(){
		$this->setTemplate('page/html/document.phtml');
	}
	protected function _prepareLayout(){
		//var_dump(get_class($this->getLayout()->getBlock('html_head')));
		return(parent::_prepareLayout());
	}
}
?>