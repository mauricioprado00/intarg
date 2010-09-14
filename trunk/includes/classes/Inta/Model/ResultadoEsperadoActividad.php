<?
class Inta_Model_ResultadoEsperadoActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_resultado_esperado',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	private $_actividad = null;
	public function getActividad(){
		if(!$this->hasIdActividad()||!($id_actividad = $this->getIdActividad()))
			return null;
		if(!isset($this->_actividad)||$this->_actividad->getId()!=$id_actividad){
			$actividad = new Inta_Model_Actividad();
			$actividad->setId($id_actividad);
			if(!$actividad->load())
				$actividad = null;
			$this->_actividad = $actividad;
		}
		return $this->_actividad;
	}
	private $_resultado_esperado = null;
	public function getResultadoEsperado(){
		if(!$this->hasIdResultadoEsperado()||!($id_resultado_esperado = $this->getIdResultadoEsperado()))
			return null;
		if(!isset($this->_resultado_esperado)||$this->_resultado_esperado->getId()!=$id_resultado_esperado){
			$resultado_esperado = new Inta_Model_ResultadoEsperado();
			$resultado_esperado->setId($id_resultado_esperado);
			if(!$resultado_esperado->load())
				$resultado_esperado = null;
			$this->_resultado_esperado = $resultado_esperado;
		}
		return $this->_resultado_esperado;
	}
	public function getNombre(){
		if($this->getResultadoEsperado()){
			return strip_tags($this->getResultadoEsperado()->getDescripcion());
		}
		return '';
	}

	public function getDbTableName() 
	{
		return 'inta_resultado_esperado_actividad';
	}
}
?>