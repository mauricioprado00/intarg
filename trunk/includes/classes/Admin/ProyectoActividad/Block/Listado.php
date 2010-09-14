<?
class Admin_ProyectoActividad_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('proyecto_actividad/listado.phtml')
		;
	} 
	private $_proyecto_actividads = null;
	private $_id_actividad = null;
	public function getProyectoActividads(){
		if(isset($this->_proyecto_actividads)){
			if($this->_id_actividad!=$this->getIdActividad()){
				$this->_proyecto_actividads = null;
			}
		}
		if(!isset($this->_proyecto_actividads)){
			$this->_proyecto_actividads = c($proyecto_actividad = new Inta_Model_ProyectoActividad())
				->setIdActividad($this->getIdActividad())
				->setWhere(Db_Helper::equal('id_actividad'))
				->search(null, null, nlll, null, get_class($proyecto_actividad))
			;
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_actividad = $this->getIdActividad();
			if(!$this->_proyecto_actividads)
				$this->_proyecto_actividads = null;
		}
		return $this->_proyecto_actividads;
	}
}
?>