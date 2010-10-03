<?
class Core_Block_XmlTemplate extends Core_Block_Template{
	private $template;
	public function getHtml(){
		return($this->_toHtml());
	}
	public function toXml(){
		return parent::_toHtml();
	}
	public function _toHtml(){
		if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
			header("Content-type: application/xhtml+xml;charset=utf-8"); } else {
			header("Content-type: text/xml;charset=utf-8");
		}
		return(
			'<'.'?xml version=\'1.0\' encoding=\'utf-8\'?'.'>'."\n".
			$this->toXml()
		);
//		$c = $this->getTemplateHtml(); 
//		return("\n<template>$c\n</template>");
	}
}
?>