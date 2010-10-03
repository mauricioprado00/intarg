<?php
class Jqgrid_XmlList_ExportHandler_Xlst_Downloader extends Jqgrid_XmlList_ExportHandler_Xlst{
//	public function __construct(){
//		parent::__construct();
//		$this->setXslResource('xsl/jqgrid/export_handler/generic.xsl');
//	}
	public function ExportXml(){
		header('content-type:text/html');
		$caption = $this->getCaption();
		$filename = preg_replace('/[^a-zA-Z0-9-_]/', '_', $caption).'.html';
		Core_Http_Header::ContentDisposition($filename);
		return parent::ExportXml();
	}
}
?>