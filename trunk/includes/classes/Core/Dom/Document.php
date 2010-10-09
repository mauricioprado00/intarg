<?php
class Core_Dom_Document extends DOMDocument{
	public function __construct(){
		parent::__construct();
		$this->registerNodeClass("DOMElement", "Core_Dom_Element");
	}
}

?>