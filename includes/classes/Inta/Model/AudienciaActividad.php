<?
/**
 *@referencia Actividad(id_actividad) Inta_Model_Actividad(id)
 *@referencia Audiencia(id_audiencia) Inta_Model_Audiencia(id)
*/
class Inta_Model_AudienciaActividad extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_actividad',
			'id_audiencia',
			'asistencia',
			'cantidad_esperada',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getNombreCompuesto(){
		//$nombre = $this->getNombreExtendido();
		if($audiencia = $this->getAudiencia()){
			$nombre = $audiencia->getNombre();
		}
		if($this->getAsistencia()){
			$cantidad = $this->getAsistencia() . ' de ' . $this->getCantidadEsperada();
		}
		else $cantidad = $this->getCantidadEsperada();
		$nombre .= ': '.$cantidad.'';
		return $nombre;
	}

	public function canEditAsistencia(){
		$actividad = $this->getActividad();
		if($actividad){
			return !$actividad->esPlanificada();
		}
		return false;
	}
	public function canEditCantidadEsperada(){
		$actividad = $this->getActividad();
		if($actividad){
			return $actividad->esPlanificada();
		}
		return false;
	}
	public function getDbTableName() 
	{
		return 'inta_audiencia_actividad';
	}
}

?>