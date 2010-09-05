<?php
class Inta_Model_MedioVerificacionIndicadorResultado extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_indicador_resultado',
			'id_medio_verificacion',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}

	public function getDbTableName() 
	{
		return 'inta_medio_verificacion_indicador_resultado';
	}
}
?>