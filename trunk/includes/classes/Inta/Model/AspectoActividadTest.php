<?
/**
 *@referencia Actividad(id_actividad) Inta_Model_Actividad(id)
 *@referencia     Otracosa   (   id_aspecto  )   Inta_Model_Aspecto   (   id   )   
*/
class Inta_Model_AspectoActividadTest extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_aspecto',
			'nombre_extendido',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
//	private $_actividad = null;
//	public function getActividad(){
//		if(!$this->hasIdActividad()||!($id_actividad = $this->getIdActividad()))
//			return null;
//		if(!isset($this->_actividad)||$this->_actividad->getId()!=$id_actividad){
//			$actividad = new Inta_Model_Actividad();
//			$actividad->setId($id_actividad);
//			if(!$actividad->load())
//				$actividad = null;
//			$this->_actividad = $actividad;
//		}
//		return $this->_actividad;
//	}
//	private $_aspecto = null;
//	public function getAspecto(){
//		if(!$this->hasIdAspecto()||!($id_aspecto = $this->getIdAspecto()))
//			return null;
//		if(!isset($this->_aspecto)||$this->_aspecto->getId()!=$id_aspecto){
//			$aspecto = new Inta_Model_Aspecto();
//			$aspecto->setId($id_aspecto);
//			if(!$aspecto->load())
//				$aspecto = null;
//			$this->_aspecto = $aspecto;
//		}
//		return $this->_aspecto;
//	}
	public function getNombreCompuesto(){
		$nombre = $this->getNombreExtendido();
		if($aspecto = $this->getAspecto()){
			$nombre .= ' - '.$aspecto->getNombre();
		}
		return $nombre;
	}

	public function getDbTableName() 
	{
		return 'inta_aspecto_actividad';
	}
}
?>