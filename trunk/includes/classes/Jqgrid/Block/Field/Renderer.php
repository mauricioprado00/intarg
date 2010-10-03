<?
class Jqgrid_Block_Field_Renderer extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('jqgrid/field_renderer.phtml')
			->setObject(null)
			->setFieldName(null)
			->setFieldValue(null)
		;
	}
	public function canRender($fieldname){
		return $fieldname==$this->getFieldName();
	}

}
?>