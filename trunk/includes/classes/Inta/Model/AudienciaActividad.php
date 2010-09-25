<?
/**
 *@referencia Actividad(id_actividad) Inta_Model_Actividad(id)
 *@referencia Audiencia(id_audiencia) Inta_Model_Audiencia(id)
*/
class Inta_Model_AspectoActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_audiencia',
			'asistencia',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
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