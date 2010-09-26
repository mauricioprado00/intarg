<?
class Admin_AudienciaActividad_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('audiencia_actividad/listado.phtml')
		;
	} 
	private $_audiencia_actividads = null;
	private $_id_actividad = null;
	public function getAudienciaActividads(){
		if(isset($this->_audiencia_actividads)){
			if($this->_id_actividad!=$this->getIdActividad()){
				$this->_audiencia_actividads = null;
			}
		}
		if(!isset($this->_audiencia_actividads)){
			$this->_audiencia_actividads = c($audiencia_actividad = new Inta_Model_AudienciaActividad())
				->setIdActividad($this->getIdActividad())
				->setWhere(Db_Helper::equal('id_actividad'))
				->search(null, null, nlll, null, get_class($audiencia_actividad))
			;
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_actividad = $this->getIdActividad();
			if(!$this->_audiencia_actividads)
				$this->_audiencia_actividads = null;
		}
		return $this->_audiencia_actividads;
	}
}
?>