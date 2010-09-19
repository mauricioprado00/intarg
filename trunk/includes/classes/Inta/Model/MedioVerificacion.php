<?php
/**
 *@referencia Indicador(id_indicador) Inta_Model_Indicador(id)
*/
class Inta_Model_MedioVerificacion extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'id_indicador',
			'nombre',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}

	public function getDbTableName() 
	{
		return 'inta_medio_verificacion';
	}
}
?>