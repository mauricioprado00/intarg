<?
class Admin_Usuario_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		
		$this
			->setTextField('nombre')
			//->setTextFormat('%s, %s')
		;
	}
    protected function _prepareLayout()
    {
		$usuario = new Inta_Model_Usuario();
		if(!$this->hasTodos()||!$this->getTodos())
			$usuario
		    	->setIdAgencia(Admin_Helper::getInstance()->getIdAgenciaSeleccionada())
		    	->setWhere(Db_Helper::equal('id_agencia'))
    	;
    	$this
			->setEntityToList($usuario)
		;
        return $this;
    }
}
?>