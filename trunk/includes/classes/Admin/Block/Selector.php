<?
class Admin_Block_Selector extends Core_Block_Template{
	public function __construct(){
		$this
			->setTemplate('page/selector.phtml')
			->setValueField('id')
			->setTextField('nombre')
		;
	}
	protected function listEntityes(){
		$entity = $this->getEntityToList();
		return $entity->search();
	}
	public function getSelectControl(){
		if(!$this->hasSelectControl()){
			$select_control = Core::getObject('Core_Html_Tag_Custom', 'select');
			foreach($this->getData() as $key=>$value){
				if(strpos($key, 'html_')===0){
					$select_control->setData(substr($key, 5), $value);
				}
			}
			$this->setSelectControl($select_control);
		}
		return parent::getData('select_control');
	}

}
?>