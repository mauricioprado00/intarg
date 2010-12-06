<?php 
class Core_Xslt_Server extends Core_Object{
	public function __constuct(){
		parent::__construct();
		
	}
	private $xml_string = null;
	private $source = null;
	private $data_model = null;
	private $arr_xslt = array();
	private $use_header = true;
	public function setUseHeader($val){
		$this->use_header = $val?true:false;
		return $this;
	}
	private function reset(){
		$this->xml_string = null;
		$this->source = null;
		$this->data_model = null;
		$this->arr_xslt = array();
	}
	public function setSource($source, $data_model=null){
		if(is_string($source)){
			$this->xml_string = $source;
		}
		elseif(is_object($source)){
			if($source instanceof Core_Object){
				$this->source = $source;
				$this->data_model = $data_model;
			}
		}
	}
	public function appendStyle($file, $weight=0){
		$this->arr_xslt[intval($weight)][] = $file;
	}
	public function resetStyles(){
		$this->arr_xslt = array();
	}
	private function getSourceXml(){
		if(isset($this->xml_string))
			return $this->xml_string;
		if(isset($this->source))
			return $this->source->toXmlString($this->data_model);
		return null;
	}
	public function toXmlString($data_model=null){
		if(isset($data_model)){
			$this->data_model = $data_model;
		}
		if(!isset($this->source)){
			return null;
		}
		if(!count($this->arr_xslt)){
			return $this->getSourceXml();//source->toXmlString($this->data_model);
		}
		return $this->transform();
	}
	private function transform(){
		$xml_string = $this->getSourceXml();//source->toXmlString($this->data_model);
		ksort($this->arr_xslt);
		foreach($this->arr_xslt as $weight=>$arr_files){
			foreach($arr_files as $file){
				$xml_string = $this->_transform($xml_string, $file);
			}
		}
		//return "\n\n\n\n".simplexml_load_string($xml_string)->asXML();
		if(!$this->use_header){
			$xml = new DOMDocument;
			$xml->loadXML($xml_string);
			return $xml->saveXML($xml->firstChild);
		}
		return $xml_string;
	}
	private function _transform($xml_string, $xls_file){
		$xml = new DOMDocument;
		$xml->loadXML($xml_string);

		$xsl = new DOMDocument;
		$xsl->load($xls_file);

		$proc = new XSLTProcessor;
		$proc->registerPHPFunctions();
		$proc->importStyleSheet($xsl); // attach the xsl rules

		return $proc->transformToXML($xml);
	}
}

?>