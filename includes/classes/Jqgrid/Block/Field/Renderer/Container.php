<?
class Jqgrid_Block_Field_Renderer_Container extends Jqgrid_Block_Field_Renderer{
	public function __construct(){
		parent::__construct();
		$this
			->setObject(null)
			->setFieldName(null)
			->setFieldValue(null)
		;
	}
	public function _toHtml(){
		return $this->getChildHtml();
	}
	protected function _beforeToHtml(){
		$this->AssingParamsToChilds();
	}
	public function AssingParamsToChilds(){
		foreach($this->getChild() as $child){
			$child
				->setObject($this->getObject())
				->setFieldName($this->getFieldName())
				->setFieldValue($this->getFieldValue())
			;
		}
	}

}
?>