<?
class Admin_AspectoActividad_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('aspecto_actividad/listado.phtml')
		;
	} 
	private $_aspecto_actividads = null;
	private $_id_actividad = null;
	public function getAspectoActividads(){
		if(isset($this->_aspecto_actividads)){
			if($this->_id_actividad!=$this->getIdActividad()){
				$this->_aspecto_actividads = null;
			}
		}
		if(!isset($this->_aspecto_actividads)){
			$this->_aspecto_actividads = c($aspecto_actividad = new Inta_Model_AspectoActividad())
				->setIdActividad($this->getIdActividad())
				->setWhere(Db_Helper::equal('id_actividad'))
				->search(null, null, nlll, null, get_class($aspecto_actividad))
			;
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_actividad = $this->getIdActividad();
			if(!$this->_aspecto_actividads)
				$this->_aspecto_actividads = null;
		}
		return $this->_aspecto_actividads;
	}
}
?>