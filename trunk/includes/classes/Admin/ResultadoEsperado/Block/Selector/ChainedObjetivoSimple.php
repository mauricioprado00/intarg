<?
class Admin_ResultadoEsperado_Block_Selector_ChainedObjetivoSimple extends Admin_Chained_Block_Abstract{
	public function __construct(){
		parent::__construct();
		$this
			->setEntityToList(new Inta_Model_ResultadoEsperado())
			->setAttributeToFilter('id_objetivo')
			->setTextField('descripcion')
		;
	}
	public function getChildrenLayoutAlias(){
		return 'selector_objetivo_simple';
	}
}
?>