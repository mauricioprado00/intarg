<?
class Admin_Indicador_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
	}
    protected function _prepareLayout()
    {
		$tipo_audiencia = new Inta_Model_Indicador();
    	$this
			->setEntityToList($tipo_audiencia)
		;
        return $this;
    }
}
?>