<?php
class Jqgrid_Block_XmlList_Renderer extends Core_Block_XmlTemplate{
	public function toXml(){
		if(!$this->hasXsl()){
			return parent::toXml();//XmlTemplate::toXml
		}
		return $this->transformXml($this->getXsl());
	}
	private function transformXml($xsl_resource){
		$xsl_resource = Core_App::getLayout()->getDesignFilePath(CONF_SUBPATH_RESOURCE.$xsl_resource);
		
		$xml = new DOMDocument;
		$xml->loadXML(parent::toXml());

		$xsl = new DOMDocument;
		$xsl->load($xsl_resource);

		$proc = new XSLTProcessor;
		$proc->importStyleSheet($xsl); // attach the xsl rules

		return $proc->transformToXML($xml);
	}
}
?>