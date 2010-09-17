<?
class Admin_Aspecto_Block_Selector_ChainedTipoAspecto extends Admin_Chained_Block_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Inta_Model_Aspecto())
			->setAttributeToFilter('id_tipo_aspecto')
		;
	}
	public function getChildrenLayoutAlias(){
		return 'selector_tipo_aspecto';
	}
}
?>