<?
class Inta_Model_Problema extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_objetivo',
			'id_audiencia',
			'nombre',
			'importancia_economica',
			'impacto_ambiental',
			'importancia_social',
			'familias_implicadas',
			'valor_agregado_potencial',
			'impacto_desarrollo',
			'prioridad',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName() 
	{
		return 'inta_problema';
	}
}
?>