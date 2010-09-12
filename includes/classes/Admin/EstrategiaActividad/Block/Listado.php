<?
class Admin_EstrategiaActividad_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('estrategia_actividad/listado.phtml')
		;
	} 
	private $_estrategia_actividads = null;
	private $_id_actividad = null;
	public function getEstrategiaActividads(){
		if(isset($this->_estrategia_actividads)){
			if($this->_id_actividad!=$this->getIdActividad()){
				$this->_estrategia_actividads = null;
			}
		}
		if(!isset($this->_estrategia_actividads)){
			$this->_estrategia_actividads = c($estrategia_actividad = new Inta_Model_EstrategiaActividad())
				->setIdActividad($this->getIdActividad())
				->setWhere(Db_Helper::equal('id_actividad'))
				->search(null, null, nlll, null, get_class($estrategia_actividad))
			;
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_actividad = $this->getIdActividad();
			if(!$this->_estrategia_actividads)
				$this->_estrategia_actividads = null;
		}
		return $this->_estrategia_actividads;
	}
}
?>