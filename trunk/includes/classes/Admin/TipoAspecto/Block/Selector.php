<?
class Admin_TipoAspecto_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
	}
    protected function _prepareLayout()
    {
		$tipo_aspecto = new Inta_Model_TipoAspecto();
    	$this
			->setEntityToList($tipo_aspecto)
		;
        return $this;
    }
}
?>