<?
class Inta_Model_IndicadorResultado extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_resultado_esperado',
			'id_indicador',
			'id_medio_verificacion',
			'descripcion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}

	public function getDbTableName() 
	{
		return 'inta_indicador_resultado';
	}
}
?>