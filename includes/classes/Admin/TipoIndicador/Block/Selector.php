<?
class Admin_TipoIndicador_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
	}
	protected function listEntityes(){
		$list = array(
			new Core_Object(array('id'=>'resultado', 'nombre'=>'Resultado')),
			new Core_Object(array('id'=>'actividad', 'nombre'=>'Actividad')),
		);
		return $list;
	}
    protected function _prepareLayout()
    {
		$tipo_indicador = new Core_Object();
		$this->setEntityToList($tipo_indicador);
        return $this;
    }
}
?>