<?php
class  Core_DataModel_Element extends Core_Dom_Element{
	public function getTargetFieldname(){
		
		if(!$this->attributes||!$this->attributes->getNamedItem('name'))
			return null;
		return $this->attributes->getNamedItem('name')->nodeValue;
	}
	private function lookupModelNode($for=null){
		foreach($this->childNodes as $child){
			if($child->nodeName=='model'){
				if(!isset($for))
					return $child;
				if($child->attributes->getNamedItem('for')){
					if($child->attributes->getNamedItem('for')->nodeValue==$for){
						return $child;
					}
				}
			}
		}
		return null;
	}
	public function getDataModelFromComponent($for=null){
		if($model_node = $this->lookupModelNode($for)){
			if($doc = $model_node->createDocument()){
				return $doc;
			}
		}
	}
	public function getDataModelFromComponentMethodSingle($return_for){
		if($model_node = $this->lookupModelNode()){
			//echo $this->saveXML();
			$for =  $this->gattr('name');
			//echo $model_node->gattr('for');
			//$attr_for = $model_node->gattr('for');
			$submodel = $model_node->saveXML();
			$new_model = '<model for="'.$return_for.'"><field name="'.$for.'">'.$submodel.'</field></model>';

			$doc = new Core_DataModel_Document();
			$doc->loadXML($new_model);
			$new_data_model = $doc->lookupModel($return_for);
			//echo $new_model;
			return $new_data_model;
			//die();
		}
	}
	public function getDataModelFromComponentMethodMultiple($return_for){
		if($model_node = $this->lookupModelNode()){
			//echo $this->saveXML();
			$for =  $this->gattr('name');
			//echo $model_node->gattr('for');
			//$attr_for = $model_node->gattr('for');
			$submodel = $model_node->saveXML();
			$new_model = '<model for="'.$return_for.'">'.$submodel.'</model>';

			$doc = new Core_DataModel_Document();
			$doc->loadXML($new_model);
			$new_data_model = $doc->lookupModel($return_for);
			//echo $new_data_model->saveXML();die();
			return $new_data_model;
			//die();
		}
	}
}
?>