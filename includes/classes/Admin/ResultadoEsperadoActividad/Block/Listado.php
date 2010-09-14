<?
class Admin_ResultadoEsperadoActividad_Block_Listado extends Core_Block_Template{
	public function __construct(){
		parent::__construct();
		$this
			->setTemplate('resultado_esperado_actividad/listado.phtml')
		;
	} 
	private $_resultado_esperado_actividads = null;
	private $_id_actividad = null;
	public function getResultadoEsperadoActividads(){
		if(isset($this->_resultado_esperado_actividads)){
			if($this->_id_actividad!=$this->getIdActividad()){
				$this->_resultado_esperado_actividads = null;
			}
		}
		if(!isset($this->_resultado_esperado_actividads)){
			$this->_resultado_esperado_actividads = c($resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad())
				->setIdActividad($this->getIdActividad())
				->setWhere(Db_Helper::equal('id_actividad'))
				->search(null, null, nlll, null, get_class($resultado_esperado_actividad))
			;
			//echo Core_Helper::DebugVars($this->getData());
			$this->_id_actividad = $this->getIdActividad();
			if(!$this->_resultado_esperado_actividads)
				$this->_resultado_esperado_actividads = null;
		}
		return $this->_resultado_esperado_actividads;
	}
}
?>