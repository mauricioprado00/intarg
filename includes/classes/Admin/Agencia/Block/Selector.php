<?
class Admin_Agencia_Block_Selector extends Admin_Block_Selector{
	public function __construct(){
		parent::__construct();
		$this->setEntityToList(new Inta_Model_Agencia());
	}
	protected function prepareEntityToList(){
		if($this->hasData('only_alowed_to_user')){
			$agencia = $this->getEntityToList();
		//	var_dump($agencia->crearFiltroSoloPermitidasAlUsuario());
			$agencia->setWhere($agencia->crearFiltroSoloPermitidasAlUsuario());
		}
	}
	
}
?>