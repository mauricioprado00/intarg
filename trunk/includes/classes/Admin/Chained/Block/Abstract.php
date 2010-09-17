<?
class Admin_Chained_Block_Abstract extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('chained/selector.phtml')
		;
	}
	protected function canFilter(){
		return $this->chained && $this->chained->getSelectedValue();
	}
	protected function setFilter(){
		$entity = $this->getEntityToList();
		$entity->setWhere(Db_Helper::equal($this->getAttributeToFilter(), $this->chained->getSelectedValue()));
	}
	protected function setChildSelectedValue(){
		$entity = $this->getSelectedEntity();
		$this->chained->setSelectedValue($entity->getData($this->getAttributeToFilter()));
	}
	private $chained_options;
	private $child_selected_value;
	public function setChainOptions(&$chain_options){
		if($chain_options&&is_array($chain_options)){
			if($this->chained instanceof Admin_Chained_Block_Abstract)
				$this->chained->setChainOptions($chain_options);
			else{
				$this->chained->setSelectedValue(array_shift($chain_options));
			}
		}
		//var_dump($chain_options);
//		if($this->canFilter()){
//			$this->setFilter();
//		}
//		else{
//		}
		$this->chained_options = $chain_options;
		if($chain_options)
			$this->setSelectedValue(array_shift($chain_options));
		return $this;
	}
//	protected function getPrevHtml(){
//		return $this->chained->toHtml();
//	}
	protected function prepareEntityToList(){
		if($this->canFilter()){
			$this->setFilter();
		}
	}
	protected function getSelectedEntity(){
		if(!$this->isEntityListLoaded()){
			$entity = $this->getEntityToList();
			$entity->setWhere(Db_Helper::equal($this->getValueField(), $this->getSelectedValue()));
			if($r = $entity->search(null, 'ASC', 1)){
				return $r[0];
			}
			return null;
		}
		return parent::getSelectedEntity();
	}
	public function setSelectedValue($value){
		parent::setData('selected_value', $value);
		if($value){
			$this->setChildSelectedValue();
//			if($this->canFilter()){
//				$this->setFilter();
//			}
//			else{
//				die();
//				echo "no puedo filtrar";
//			}
		}
		return $this;
	}
	
	/*Chained*/
	private $chained = null;
	public function onAfterLayoutLoad(){
		$this->chained = 
			c($selector_estrategia = $this->appendBlock('<'.$this->getChildrenLayoutAlias().' name="chained" />'))
		;
	}
	protected function canListSuboptions(){
		return $this->canFilter();
	}
	public function createOptions(){
		if(!$this->canListSuboptions()){
			return array($this->getCantListOption());
		}
		return parent::createOptions();
	}
	protected function getCantListOption(){
		$option = c(Core::getObject('Core_Html_Tag_Custom', 'option'))
			->setInnerHtml($this->getCantListOptionMessage())
		;
		return $option;	
	}
	protected function getCantListOptionMessage(){
		return '[seleccione opcion anterior]';
	}

}
?>