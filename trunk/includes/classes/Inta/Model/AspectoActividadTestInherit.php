<?
/**
 *@referencia Algomas(id_aspecto) Inta_Model_Aspecto(id)   
*/
class Inta_Model_AspectoActividadTestInherit extends Inta_Model_AspectoActividadTest{
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