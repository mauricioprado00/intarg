<?php
class Inta_Model_Indicador extends Core_Model_Abstract{
	public function init(){
		parent::init();
		$datafields = array(
			'id',
			'nombre',
			'tipo_indicador',
		);
		foreach($datafields as $datafield)
			$this->setData($datafield);
	}
	public function getDbTableName()
	{
		return 'inta_indicador';
	}
}
?>
