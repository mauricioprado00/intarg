<?
/**
 *@referencia Actividad(id_actividad) Inta_Model_Actividad(id)
 *@referencia Proyecto(id_proyecto) Inta_Model_Proyecto(id)
*/
class Inta_Model_ProyectoActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_proyecto',
			'monto',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getNombre(){
		if($this->getProyecto()){
			return $this->getProyecto()->getNombre();
		}
		return '';
	}

	public function getDbTableName() 
	{
		return 'inta_proyecto_actividad';
	}
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
//	private $_proyecto = null;
//	public function getProyecto(){
//		if(!$this->hasIdProyecto()||!($id_proyecto = $this->getIdProyecto()))
//			return null;
//		if(!isset($this->_proyecto)||$this->_proyecto->getId()!=$id_proyecto){
//			$proyecto = new Inta_Model_Proyecto();
//			$proyecto->setId($id_proyecto);
//			if(!$proyecto->load())
//				$proyecto = null;
//			$this->_proyecto = $proyecto;
//		}
//		return $this->_proyecto;
//	}
//
?>