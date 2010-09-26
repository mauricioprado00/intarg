<?
class Admin_Block_Selector extends Core_Block_Template{
	public function __construct(){
		$this
			->setTemplate('page/selector.phtml')
			->setValueField('id')
			->setTextField('nombre')
		;
	}
//	protected function getPrevHtml(){
//		return '';
//	}
	private $control_id = null;
	protected final function getControlId(){
		if(!isset($this->control_id)){
			$this->control_id = $this->generateRandomId();
		}
		return $this->control_id;
	}
	private $_entityes = null;
	protected function prepareEntityToList(){
		
	}
	protected function listEntityes(){
		if(!isset($this->_entityes)){
			$this->prepareEntityToList();
			$entity = $this->getEntityToList();
			$this->_entityes = $entity->search();
		}
		return $this->_entityes;
	}
	protected function isSelectedEntity($entity){
		$selected_value = $this->hasSelectedValue()&&strlen($this->getSelectedValue())?$this->getSelectedValue():null;
		$value_field = $this->getValueField();
		return isset($selected_value)&&$entity->getData($value_field)==$selected_value;
	}
	protected function canFindSelectedEntity(){
		return $this->hasSelectedValue()&&strlen($this->getSelectedValue())?true:false;
	}
	protected function getSelectedEntity(){
		if(!$this->canFindSelectedEntity())
			return null;
		if($this->listEntityes()){
			foreach($this->_entityes as $entity)
				if($this->isSelectedEntity($entity))
					return $entity;
		}
		return null;
	}
	protected function isEntityListLoaded(){
		return $this->_entityes?true:false;
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
	public function createOptions(){
		$selected_value = $this->hasSelectedValue()&&strlen($this->getSelectedValue())?$this->getSelectedValue():null;
		$value_field = $this->getValueField();
		$text_field = $this->getTextField();
		
		$options = array();
		$entityes = $this->listEntityes();
		if(!$this->hasSelectedValue()){
			$option = c(Core::getObject('Core_Html_Tag_Custom', 'option'))
				->setValue('')
				->setInnerHtml('[Seleccione]')
			;
			$options[] = $option;
		}
		foreach($entityes as $entity){
			$option = c(Core::getObject('Core_Html_Tag_Custom', 'option'))
				->setValue($entity->getData($value_field))
				->setInnerHtml($entity->getData($text_field))
			;
			if($this->isSelectedEntity($entity))
				$option->setSelected('selected');
			$options[] = $option;
		}
		if(!$options){
			$options = array($this->getEmptyOption());
		}
		return $options;
	}
	protected function getEmptyOption(){
		$option = c(Core::getObject('Core_Html_Tag_Custom', 'option'))
			->setInnerHtml($this->getEmptyOptionMessage())
		;
		return $option;	
	}
	protected function getEmptyOptionMessage(){
		return '[No hay opciones]';
	}

}
/*
	$selected_value = $this->hasSelectedValue()&&strlen($this->getSelectedValue())?$this->getSelectedValue():null;
	$value_field = $this->getValueField();
	$text_field = $this->getTextField();
	
	$entityes = $this->listEntityes();
	$html_select = '';
	foreach($entityes as $entity){
		$option = c(Core::getObject('Core_Html_Tag_Custom', 'option'))
			->setValue($entity->getData($value_field))
			->setInnerHtml($entity->getData($text_field))
		;
		if($this->isSelectedEntity($entity))
			$option->setSelected('selected');
		$html_select .= $option->getHtml();
	}

*/
?>