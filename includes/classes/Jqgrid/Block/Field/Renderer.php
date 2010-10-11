<?
class Jqgrid_Block_Field_Renderer extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('jqgrid/field_renderer.phtml')
			->setObject(null)
			->setFieldName(null)
			->setFieldValue(null)
			->setReturnCData(false)
		;
		$this->setBooleanData('return_cdata');
	}
	public function canRender($fieldname){
		return $fieldname==$this->getFieldName();
	}
	public function setObject($object){
		$this->setData('object', $object);
		return $this;
	}
	public function setFieldName($field_name){
		$this->setData('field_name', $field_name);
		return $this;
	}
	public function setFieldValue($field_value){
		$this->setData('field_value', $field_value);
		return $this;
	} 

}
?>