<?
class Admin_Objetivo_Block_Selector_ChainedAgencia extends Admin_Chained_Block_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Inta_Model_Objetivo())
			->setAttributeToFilter('id_agencia')
		;
	}
	public function getChildrenLayoutAlias(){
		return 'selector_agencia';
	}
}
?>