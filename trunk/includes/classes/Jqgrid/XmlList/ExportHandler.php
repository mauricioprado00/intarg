<?php
abstract class Jqgrid_XmlList_ExportHandler extends Core_Object{
	public function __construct(){
		parent::__construct();
	}
	private $xml_list = null;
	public function setXmlList($xml_list){
		$this->xml_list = $xml_list;
	}
	protected function getXmlList(){
		return $this->xml_list;
	}
	
	abstract public function ExportXml();
	
}
?>