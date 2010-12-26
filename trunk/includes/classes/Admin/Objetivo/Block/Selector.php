<?
class Admin_Objetivo_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setModoListadoAgenciaSeleccionada(true);
		$this
			->setEntityToList(new Inta_Model_Objetivo())
			->setTextField('nombre')
		;
	}
	protected function prepareEntityToList(){
		$objetivo = $this->getEntityToList();
		$objetivo
			->setWhere($objetivo->crearFiltroAgencia())
		;
	}
//    protected function _prepareLayout()
//    {
//		$objetivo = new Inta_Model_Objetivo();
//    	$this
//			->setEntityToList($objetivo)
//		;
//        return $this;
//    }
}
?>