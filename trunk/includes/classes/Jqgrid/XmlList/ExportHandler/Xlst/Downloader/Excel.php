<?php
class Jqgrid_XmlList_ExportHandler_Xlst_Downloader_Excel extends Jqgrid_XmlList_ExportHandler_Xlst{
	public function __construct(){
		parent::__construct();
		$this->setXslResource('xsl/jqgrid/export_handler/excel.xsl');
	}
	public function ExportXml(){
		header('content-type:application/vnd.ms-excel');
		$caption = $this->getCaption();
		$filename = preg_replace('/[^a-zA-Z0-9-_]/', '_', $caption).'.xml';
		Core_Http_Header::ContentDisposition($filename);
		return parent::ExportXml();
	}
}
?>