<?php
class Core_DataModel_Document extends DOMDocument{
	public function __construct(){
		parent::__construct();
		$this->registerNodeClass("DOMElement", "Core_DataModel_Element");
	}
	private function lookupModelNode($for=null){
		foreach($this->childNodes as $child){
			if($child->nodeName=='model'){
				if(!isset($for))
					return $child;
				if($child->attributes->getNamedItem('for')){
					if($child->attributes->getNamedItem('for')->nodeValue==$for){
						//var_dump('found.'.$child->attributes->getNamedItem("for")->nodeValue." >>".__FILE__.__LINE__);
						return $child;
						//return $child->createDocument();
					}
				}
			}
		}
		return null;
	}
	private function lookupFields($filters=null){
		if($model = $this->lookupModelNode()){
			foreach($model->childNodes as $child){
				if($child->nodeName=='field'){
					var_dump('field.');
				}
			}
		}
	}
	public function getModelComponents(){
		if($model = $this->getModelNode()){
			return $model->childNodes;
		}
	}
	public function getModelNode(){
		if($this->childNodes->length){
			if($this->childNodes->item(0)){
				if($this->childNodes->item(0)->nodeName=='model'){
					return $this->childNodes->item(0);
				}
			}
		}
	}
	public function lookupChildModel($for){
		if($model_node = $this->getModelNode()){
			if($child = $model_node->getDataModelFromComponent($for)){
				return $child;
			}
		}
	}
	public function lookupModel($for){
		if($child = $this->lookupModelNode($for)){
			return $child->createDocument();
		}
		return null;
	}

//	public function lookupModel($for){
//		foreach($this->childNodes as $child){
//			if($child->nodeName=='root'){
//				return $child->lookupModel($for);
//			}
//		}
//	}
}

?>