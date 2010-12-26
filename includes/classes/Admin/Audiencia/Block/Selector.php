<?
class Admin_Audiencia_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
	}
	protected function prepareEntityToList(){
		$audiencia = $this->getEntityToList();
		$audiencia
			->setWhere($audiencia->crearFiltroAgencia())
		;
	}
    protected function _prepareLayout()
    {
		$audiencia = new Inta_Model_Audiencia();
    	$this
			->setEntityToList($audiencia)
		;
        return $this;
    }
}
?>