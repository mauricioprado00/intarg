<?php
class  Core_Dom_Element extends DOMElement{
    public function __toString() {
        return $this->nodeValue;
    }
    public function saveXML($formatOutput=false){
    	
    	$this->ownerDocument->formatOutput = $formatOutput;
    	return $this->ownerDocument->saveXML($this);
    	
	}
	public function saveXML2(){
		return simplexml_import_dom($this)->asXML();
	}
	public function createDocument(){
		$class = get_class($this->ownerDocument);
		$doc = new $class();
		$new_node = $doc->importNode($this, true);
		$doc->appendChild($new_node);
		//echo $doc->saveXML();
		return $doc;
	}
	public function gattr($name){
		if($this->attributes&&$this->attributes->getNamedItem($name)){
			return $this->attributes->getNamedItem($name)->nodeValue;
		}
		return null;
	}
}
?>