<?php
class Jqgrid_XmlList_ExportHandler_Xlst extends Jqgrid_XmlList_ExportHandler{
	public function __construct(){
		parent::__construct();
		$this->setXslResource('xsl/jqgrid/export_handler/generic.xsl');
	}
	private $_config = null;
	protected function getConfig(){
		if(!isset($this->_config)){
			$json_config = Core_Http_Post::getParameters('Core_Object')->getData('json_config');
			if($config = @json_decode($json_config)){
				$this->_config = $config;
			}
		}
		return $this->_config;
	}
	protected function getCaption(){
		if($config = $this->getConfig()){
			return $config->grid->caption;
		}
	}

	protected function getXmlString(){
		$xml_list = $this->getXmlList();
		if($config = $this->getConfig()){
			//var_dump($config->grid->postData);
			$writer = new XMLWriter(); 
			//$writer->openURI('php://output');
			$writer->openMemory();
			 
			$writer->startElement("grid");
				$post = Core_Http_Post::getParameters('object');
				$parameters = $config->grid->postData;
				$writer->startElement("range");
				if($post->rango=='todo'){
					$writer->text('all');
					$parameters->rows = -1;
					$parameters->page = 1;
					//echo "todododod";
				}
				else{
					$writer->text('page');
				}
				$writer->endElement();
				if(!$post->utilizar_filtros){
					$parameters->_search = false;
					$parameters->searchField = '';
					$parameters->searchString = '';
					$parameters->searchOper = '';
				}
			
				$writer->startElement("caption");
				$writer->text($this->getCaption());
				$writer->endElement();
				
			$writer->endElement();
			
			$writer->startElement("columns");
			foreach($config->grid->colModel as $idx=>$columna){
				if($columna->hideinexport)
					continue;
				//$writer->setCData($key, $value);
				$writer->startElement('column');
					$writer->startElement('width');
						$writer->text($columna->width);
					$writer->endElement();
					$writer->startElement('align');
						$writer->text($columna->align);
					$writer->endElement();
					$writer->startElement('title');
						$writer->text($config->grid->colNames[$idx]);
					$writer->endElement();
				$writer->endElement();
			}
			$writer->endElement();
//			$writer->endDocument();
			$xml_columnas = $writer->outputMemory(true);
			
//			echo $xml_columnas;
//			var_dump($config->grid);
//			die();
//			var_dump($post);
//			die();
			$xml_list->setUseCData(false);
			$xml_list->setParameters($parameters);
		}
		else $xml_columnas = '';
//		die();
//		die();
		$xml_filas = $xml_list->toXml();
		$xml = "
	<export>
		$xml_columnas
		$xml_filas
	</export>
		";
//		var_dump($xml);
//		die();
		return $xml;
	}
	public function ExportXml(){
		
		$xsl_resource = $this->getXslResource();
		$xsl_resource = Core_App::getLayout()->getDesignFilePath(CONF_SUBPATH_RESOURCE.$xsl_resource);
		
		$xml = new DOMDocument;


		//$xml->loadXML('<x>'.$this->getXmlString().$this->getXmlString().'</x>');
//		header('content-type:text/plain');
//		echo $this->getXmlString();
//		die();
		$xml->loadXML(@$this->getXmlString());
//		
		$xsl = new DOMDocument;
		$xsl->load($xsl_resource);
//		
//		// Configure the transformer
		$proc = new XSLTProcessor;
		$proc->importStyleSheet($xsl); // attach the xsl rules
//		
		return $proc->transformToXML($xml);
		
		return $xml_list->toXml();
	}
}
?>