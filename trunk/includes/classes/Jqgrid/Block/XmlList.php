<?
class Jqgrid_Block_XmlList extends Jqgrid_Block_XmlList_Abstract{
	
	private function isExport(){
		//hay que determinar por algun parametro post si hay que exportar
		return Core_Http_Post::getParameters('Core_Object')->hasXmlListExportTo();
		//return true;
	}
	private function getExportHandlerClass(){
		return Core_Http_Post::getParameters('Core_Object')->getXmlListExportTo();
		return 'Jqgrid_XmlList_ExportHandler_Xlst';
	}
	private function getXmlExportHandler(){
		if($this->isExport()){
			if(($class = $this->getExportHandlerClass())&&class_exists($class)){
				if($o = new $class){
					$o
						->setXmlList($this)
					;
					return $o;
				}
			}
		}
	}
	private function handleExport(){
		if($export_handler = $this->getXmlExportHandler()){
			return $export_handler->ExportXml();
		}
		return false;
	}
//	public function toXml(){
//		return parent::_toHtml();
//	}
	public function _toHtml(){
		if($r = $this->handleExport()){
			return $r;
		}
		return parent::_toHtml();
	}
}
?>