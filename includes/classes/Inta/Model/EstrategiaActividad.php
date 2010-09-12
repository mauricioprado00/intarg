<?
class Inta_Model_EstrategiaActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_estrategia',
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
	private $_estrategia = null;
	public function getEstrategia(){
		if(!$this->hasIdEstrategia()||!($id_estrategia = $this->getIdEstrategia()))
			return null;
		if(!isset($this->_estrategia)||$this->_estrategia->getId()!=$id_estrategia){
			$estrategia = new Inta_Model_Estrategia();
			$estrategia->setId($id_estrategia);
			if(!$estrategia->load())
				$estrategia = null;
			$this->_estrategia = $estrategia;
		}
		return $this->_estrategia;
	}
	public function getNombre(){
		if($this->getEstrategia()){
			return $this->getEstrategia()->getNombre();
		}
		return '';
	}

	public function getDbTableName() 
	{
		return 'inta_estrategia_actividad';
	}
}
?>